<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountManagerExport;
use App\Services\AccountManagerService;
use Carbon\Carbon;

class AccountManagerController extends Controller
{
    //
    use ResponseTrait;

    public function __construct(AccountManagerService $service){
        $this->modelService = $service;
        $this->modelAlias = "Account Manager";
    }

    public function export(Request $request){
        $exportFileTypes = array(
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'excel' => \Maatwebsite\Excel\Excel::XLSX,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
        );

        $exportFileName = array(
            'csv' => 'account-managers '.Carbon::now()->format('Y-m-d').'.csv',
            'excel' => 'account-managers '.Carbon::now()->format('Y-m-d').'.xlsx',
            'pdf' => 'account-managers '.Carbon::now()->format('Y-m-d').'.pdf',
        );



        return (new AccountManagerExport($request->selectedIds ?? []))
            ->download(
                $exportFileName[$request->exportType] ?? $exportFileName['excel'],
                $exportFileTypes[$request->exportType ?? 'excel'],
            );
    }
}
