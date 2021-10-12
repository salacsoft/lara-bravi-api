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
        Excel::store(new ClientExport($request->selected ?? []), 'clients.xlsx', 'public');
        $pathToFile = Storage::disk('public')->path('clients.xlsx');
        return response()->download($pathToFile);
    }



}
