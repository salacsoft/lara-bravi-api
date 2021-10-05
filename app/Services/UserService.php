<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserService  extends BaseService
{


    protected $user;

    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->user = $this->model;
        $this->searchableColumns = $this->model->searchableColumns;
        $this->defaultSortKey = $this->model->defaultSortKey ?? "username";
        $this->requestValidator = new UserRequest;
    }


    // public function create($payload)
    // {
    //     $data = $payload->only($this->user->getFillable());
    //     $data["uuid"] = Str::random(30);
    //     return $user =$this->user->create($data);
    // }

    public function findByEmail(string $email)
    {
        return $this->user->where("email", $email)->first();
    }



}
