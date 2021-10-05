<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $modelService ;
    protected $modelAlias;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        return $this->modelService->getAll($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validated = $request->validate($this->modelService->validatePayload());
        DB::beginTransaction();
        try {
            $record = $this->modelService->create($request);
            $response = response()->json(["message" => "New $this->modelAlias saved.", "data" => $record], 201);
            DB::commit();
            return $response;

        } catch(\Exception $ex) {
            Log::info($ex->getMessage());
            DB::rollback();
            return response()->json(["message" => $ex->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->modelService->requestValidator->rules());
        DB::beginTransaction();
        try {
            $record = $this->modelService->store($request);
            if ($record["success"] == true) {
                $response = response()->json($record, 201);
                DB::commit();
                return $response;
            }

        } catch(\Exception $ex) {
            Log::info($ex->getMessage());
            Log::info($ex);
            DB::rollback();
            return response()->json(["message" => $ex->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $record = $this->modelService->find($id);
        return response()->json($record, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
