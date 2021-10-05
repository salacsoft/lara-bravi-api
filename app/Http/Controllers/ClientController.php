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

    public function __construct(ClientService $service)
    {
        $this->modelService = $service;
        $this->modelAlias = " Client ";
    }



}
