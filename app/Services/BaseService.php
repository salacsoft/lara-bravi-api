<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BaseService
{
    protected $tableName ;
    protected $model ;
    public function __construct($model)
    {
        $this->model = $model;
        $this->tableName = $this->model->getTable();
    }

    public function getTable()
    {
        return $tableName;
    }

    public function getAll($pagination = 10)
    {
        return $this->model->paginate($pagination);
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



}
