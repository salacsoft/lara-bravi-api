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
    public function __construct($model)
    {
        $this->model = $model;
        $this->tableName = $this->model->getTable();
        $this->defaultSortKey = $this->model->defaultSortKey;
    }

    public function getTable()
    {
        return $tableName;
    }

    public function getAll($pagination = 0)
    {
        if ($pagination != "all" && intval($pagination) > 0)
            return $this->model->orderBy($this->defaultSortKey, "asc")->paginate($pagination);
        else
        return $this->model->orderBy($this->defaultSortKey, "asc")->paginate(1000000);
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


    public function create($attributes)
    {
        return $this->model->create($attributes);
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
