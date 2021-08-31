<?php

namespace App\Services;

use App\Models\User;
use App\Models\CquoinUser;
use Illuminate\Support\Str;

class CquoinUserService  extends BaseService
{
    //variable that will hold cquoin user model
    protected $admin;

    public function __construct(CquoinUser $model)
    {
        parent::__construct($model);
        $this->admin = $this->model;
    }


    public function create($payload)
    {
        $data = $payload->only($this->admin->getFillable());
        $data["uuid"] = Str::random(30);
        return $this->admin->create($data);
    }




}
