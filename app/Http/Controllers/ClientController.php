<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ClientExport;
use App\Traits\ResponseTrait;
use App\Services\ClientService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ClientRequest;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    use ResponseTrait;
    protected $clientService;

    public function __construct(ClientService $service)
    {
        $this->modelService = $service;
        $this->modelAlias = " Client ";
    }

    public function exportList(Request $request)
    {
        // $responseHeaders = array(
        //     // "Content-type" => "application/csv",
        //     // "Content-Disposition" => "attachment; filename=test.pdf",
        //     // "Pragma" => "no-cache",
        //     // "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        //     // 'Content-Transfer-Encoding' => 'binary',
        //     // "Expires" => "0"
        // );

        // Excel::store(new ClientExport(2018), 'clien2.xlsx');
        Log::info("payload-------------");
        Log::info($request->selected);
        Log::info("payload-------------");

        Excel::store(new ClientExport($request->selected ?? []), 'invoices.xlsx', 'public');
        $pathToFile = Storage::disk('public')->path('invoices.xlsx');
        // Log::info("path ". $pathToFile);

        return response()->download($pathToFile);

        // $file = File::get($pathToFile);
        // $response = Response::make($file, 200);
        // $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // return $response;

        // return Excel::store(new ClientExport, '/public/clients-list.xlsx',null, \Maatwebsite\Excel\Excel::CSV);
    }



}
