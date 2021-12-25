<?php

namespace App\Services;

use App\Models\User;
use App\Models\CquoinUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ClientResource;
use App\Http\Requests\CquoinUserRequest;

class CquoinUserService  extends BaseService
{
    //variable that will hold cquoin user model
    protected $admin;

    public function __construct(CquoinUser $model)
    {
        //calling the parent constructor to set the model
        parent::__construct($model);
        $this->admin = $this->model;

        //searchables columns should be declared on your model class, most of the time these are the fillable columns
        $this->searchableColumns = $this->model->searchableColumns;

        //defaultSortkey will be the order by to be use
        $this->defaultSortKey = $this->model->defaultSortKey ?? "first_name";

        //this the request validator, you can create by using php artisan make:request
        $this->requestValidator = new CquoinUserRequest;
    }

}
