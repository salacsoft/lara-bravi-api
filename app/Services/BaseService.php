<?php

namespace App\Services;

class BaseService
{
    protected $model ;
    public function __construct($model)
    {
        $this->model = $model;
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
}
