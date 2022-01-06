<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseService
{
    protected $tableName ;
    protected $model ;
    protected $searchableColumns = [];
    public $defaultSortKey = "id";
    protected $fileStoragePath = "public";
    public $requestValidator ;
    protected $modelResource;
    protected $imagePath = "storage";


    public function __construct($model)
    {
        //pass the model use by the service
        $this->model = $model;

        //get the table name of the model
        $this->tableName = $this->model->getTable();

        //defaultSortkey will be the order by to be use
        $this->defaultSortKey = $this->model->defaultSortKey;

        //searchables columns should be declared on your model class, most of the time these are the fillable columns
        $this->searchableColumns = $this->model->getFillable();

    }




    //get the list of records with pagination
    public function getAll($request)
    {
        $paginate = $request->paginate ?? 10;
        $orderBy  = $request->orderBy ?? $this->model->defaultSortKey;
        $lookUp   = $request->search ?? "";
        $result   = $this->model
                    ->where(function($query) use ($lookUp) {
                        $query->whereLike($this->searchableColumns, $lookUp);
                    })
                    ->where(function($query) use ($orderBy) {
                        if (is_array($orderBy)) {
                            foreach($orderBy as $orderField) {
                                $query->orderBy($orderField ,"asc");
                            }
                        } else {
                            $query->orderBy($orderBy, "asc");
                        }
                    })
                    ->paginate($paginate);
        return $result;
    }



    //find using the uuid column
    public function findUuid(string $uuid)
    {
        $data = $this->model->where("uuid", $uuid)->first();
        return new $this->modelResource($data);
    }

    //find using the incremental id of the table
    public function find(int $id)
    {
        $data = $this->model->findOrFail($id);
        return new $this->modelResource($data);
    }

    //find using the field and value pass to this function
    public function getBy($column, $value)
    {
        $data = $this->model->where($column, $value)->first();
        return new $this->modelResource($data);
    }


    //create record using create command, fillable must be set on the model to allow mass updating
    public function create($request)
    {
        $validated = $request->validate($this->requestValidator->rules());
        $columns = $this->model->getFillable();
        $payload = $request->only($columns);
        if (in_array("uuid", $columns )) {
            $payload["uuid"] = Str::uuid(30);
        }
        return $this->model->create($payload);
    }

    //loop through the fillable columns and match on the payload to insert record on the table
    public function store($request, $id = null)
    {

        if ($id !== null) {
            $this->model = $this->find($id);
        }
        $uuid = Str::uuid(30);
        $columns = $this->model->getFillable();
        $fileColumns = $this->model->fileColumns;
        foreach($columns as $column) {
            if ($fileColumns && in_array($column, $fileColumns)) {
                $this->model[$column] = $this->storeFile($request, $column, $uuid);
            }else {
                $this->model[$column] = $request[$column] ?? $this->model[$column];
            }

        }

        if (in_array("uuid", $columns ) and $id === null) {
            $this->model["uuid"] = $uuid;
        }

        $this->model->save();

        return new $this->modelResource($this->model);
    }


    //function to get the file attached from the payload and store on the storage folder
    public function storeFile($request, $columnName, $filename): string
    {
        if ($request->hasFile($columnName)) {
            $file = $request->file($columnName);
            $name = $filename.$request->file($columnName)->getClientOriginalExtension();
            $path = $request->file($columnName)->store($this->fileStoragePath);
            return str_replace("public","storage", $path);
        }

        return null;
    }


    //pass the id od the record to be delete
    public function delete($id)
    {
        $record = $this->find($id);
         if($record){
             $record->delete();
             return array(
                 "success" => true,
                 "message" => "Record deleted"
             );
         }
    }

    public function lookFor($criteria)
    {
        return $this->client
            ->whereLike($this->searchableColumns, $criteria["search"])
            ->orderBy($criteria["sortBy"] ?? $this->defaultSortKey, "asc")
            ->paginate($criteria["paginate"]);
    }


    //get the list of soft deleted records with pagination
    public function getAllSoftDeleted($request)
    {
        $paginate = $request->paginate ?? 10;
        $orderBy  = $request->orderBy ?? $this->model->defaultSortKey;
        $lookUp   = $request->search ?? "";
        $result   = $this->model
                    ->onlyTrashed()
                    ->where(function($query) use ($lookUp) {
                        $query->whereLike($this->model->searchableColumns, $lookUp);
                    })
                    ->orderBy($orderBy, "asc")
                    ->paginate($paginate);
        return $result;
    }


    //find using the incremental id of the table
    public function findSoftDelete(int $id)
    {
        return $this->model->onlyTrashed()->findOrFail($id);
    }


}
