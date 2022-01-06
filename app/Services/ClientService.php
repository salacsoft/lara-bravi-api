<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;

class ClientService  extends BaseService
{
    //variable that will hold client model
    protected $client;

    public function __construct(Client $model)
    {
        //calling the parent constructor to set the model
        parent::__construct($model);
        $this->client = $this->model;

        //searchables columns should be declared on your model class, most of the time these are the fillable columns
        $this->searchableColumns = $this->model->searchableColumns;

        //defaultSortkey will be the order by to be use
        $this->defaultSortKey = $this->model->defaultSortKey ?? "client_name";

        //this the request validator, you can create by using php artisan make:request
        $this->requestValidator = new ClientRequest;

        $this->modelResource = "App\Http\Resources\ClientResource";
    }

    public function getAll($request)
    {
        $result = Parent::getAll($request);
        return  ClientResource::collection($result);
    }

}
