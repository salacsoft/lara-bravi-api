<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientService  extends BaseService
{
    //variable that will hold client model
    protected $client;

    public function __construct(Client $model)
    {
        parent::__construct($model);
        $this->client = $this->model;
    }

}
