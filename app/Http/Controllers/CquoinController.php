<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CquoinUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Services\CquoinUserService;
use Illuminate\Support\Facades\Log;
use App\Notifications\WelcomeNewUser;
use App\Http\Resources\CquoinResource;
use App\Http\Requests\CquoinUserRequest;

class CquoinController extends Controller
{
    use ResponseTrait;

    protected $cquoin;
    protected $user;

    public function __construct(CquoinUserService $cquoin, UserService $user)
    {
        $this->cquoin = $cquoin;
        $this->user = $user;
        $this->modelService = $cquoin;
        $this->modelAlias = " username ";
    }


    /**
     * @param App\Http\Requests\CquoinUserRequest;
     * @return App\Traits\ResponseTrait JSON
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->create($request);
            $cquoin = $this->cquoin->create($request);
            $cquoin->user()->save($user);

            DB::commit();

            return $this->success(
                "New user successfuly registered",
                $cquoin,
                201
            );

        }catch (Exception $ex)
        {
            Log::error($ex->getMesgae());
            DB::rollback();
            return $this->failure($ex->getMessage, $ex->getStatus());
        }
        // $user->notify(new WelcomeNewUser);
        return $cquoin;
    }

    public function getUser(int $id)
    {
        $cquoinUser = $this->cquoin->find($id);
        return $this->success(
            "Cquoin User Retrieve",
             new CquoinResource($cquoinUser),
             200);
    }
}
