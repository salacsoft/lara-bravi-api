<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BaseService
{
    protected $tableName ;
    protected $model ;
    protected $searchableColumns = [];
    public $defaultSortKey = "id";
    protected $fileStoragePath = "public";
    public $requestValidator ;

    public function __construct($model)
    {
        $this->model = $model;
        $this->tableName = $this->model->getTable();
        $this->defaultSortKey = $this->model->defaultSortKey;
        $this->searchableColumns = $this->model->searchableColumns;
    }

    public function getTable()
    {
        return $tableName;
    }


    public function getAll($request)
    {
        $paginate = $request->paginate ?? 1000000;
        $orderBy = $request->orderBy ?? $this->model->defaultSortKey;
        $query = $this->model;
        if($request->search){
            $query->whereLike($this->model->searchableColumns, $request->search);
        }
        return $query->orderBy($orderBy, "asc")->paginate($paginate);
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }

    public function findUuid(string $uuid)
    {
        return $this->model->where("uuid", $uuid)->first();
    }


    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getBy($column, $value)
    {
        return $this->model->where($column, $value)->first();
    }


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


    public function store($request)
    {

        $uuid = Str::uuid(30);
        $columns = $this->model->getFillable();
        $fileColumns = $this->model->fileColumns;

        foreach($columns as $column) {
            if ($fileColumns && in_array($column, $fileColumns)) {
                $this->model[$column] = $this->storeFile($request, $column, $uuid);
            }else {
                $this->model[$column] = $request[$column] ?? null;
            }

        }
        if (in_array("uuid", $columns )) {
            $this->model["uuid"] = $uuid;
        }

        $this->model->save();
        return array("success" => true, "data" => $this->model);
    }


    public function storeFile($request, $columnName, $filename): string
    {
        if ($request->hasFile($columnName)) {
            $file = $request->file($columnName);
            $name = $filename.$request->file($columnName)->getClientOriginalExtension();
            return $request->file($columnName)->store($this->fileStoragePath);
        }

        return null;
    }

    public function update($attributes, $id)
    {
        $record = $this->find($id);
        return $record->update($attributes);
    }

    public function delete($id)
    {
        $record = $this->find($id);
        return $record->delete();
    }

    public function lookFor($criteria)
    {
        return $this->client
            ->whereLike($this->searchableColumns, $criteria["search"])
            ->orderBy($criteria["sortBy"] ?? $this->defaultSortKey, "asc")
            ->paginate($criteria["paginate"]);
    }


}
