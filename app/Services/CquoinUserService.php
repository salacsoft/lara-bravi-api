<?php

namespace App\Services;

use App\Models\User;
use App\Models\CquoinUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CquoinUserRequest;

class CquoinUserService  extends BaseService
{
    //variable that will hold cquoin user model
    protected $admin;

    public function __construct(CquoinUser $model)
    {
        parent::__construct($model);
        $this->admin = $this->model;
        $this->searchableColumns = $this->model->searchableColumns;
        $this->defaultSortKey = $this->model->defaultSortKey ?? "first_name";
        $this->requestValidator = new CquoinUserRequest;

    }


    // public function create($payload)
    // {
    //     $data = $payload->only($this->admin->getFillable());
    //     $data["uuid"] = Str::random(30);
    //     return $this->admin->create($data);
    // }




}
