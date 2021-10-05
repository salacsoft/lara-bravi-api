<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientRequest;

class ClientService  extends BaseService
{
    //variable that will hold client model
    protected $client;

    public function __construct(Client $model)
    {
        parent::__construct($model);
        $this->client = $this->model;
        $this->searchableColumns = $this->model->searchableColumns;
        $this->defaultSortKey = $this->model->defaultSortKey ?? "client_name";
        $this->requestValidator = new ClientRequest;
    }

}
