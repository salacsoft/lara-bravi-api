<?php

namespace App\Http\Controllers;

use Exception;
use DB;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\ClientService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    use ResponseTrait;
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $list = $this->clientService->getAll($request->paginate ?? 0);
        return  ClientResource::collection($list);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {

        $payload = $request->only($this->clientService->getFillable());
        $payload["uuid"]   = Str::random(20);
        $client = $this->clientService->create($payload);
        return $this->success(
            "New Client successfuly registered",
            $client,
            201
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        try {
            $client = $this->clientService->find($id);
            return $this->success(
                "Client retreived",
                new ClientResource($client),
                200
            );
        }catch(Exception $ex){
            if ($ex instanceof NotFoundHttpException); {
                return $this->fail(
                    "Sorry, no record found!",
                    404
                );
            }
            return $this->fail(
                $ex->getMessage(),
                500
            );
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, $id)
    {
        $payload = $request->only($this->clientService->getFillable());
        try {
            $this->clientService->update($payload, $id);
            return $this->success(
                "Client updated"
            );
        } catch(Exception $ex) {
            return $this->fail(
                $ex->getMessage(),
                500
            );
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $client = $this->clientService->delete($id);
            return $this->success(
                "Client Deleted"
            );
        }catch(Exception $ex){
            if ($ex instanceof NotFoundHttpException); {
                return $this->fail(
                    "Sorry, no record found!",
                    404
                );
            }
            return $this->fail(
                $ex->getMessage(),
                500
            );
        }
    }
}
