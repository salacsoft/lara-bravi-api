<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\GroupService;
use Illuminate\Support\Str;
use App\Exports\GroupExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class GroupController extends Controller
{
    use ResponseTrait;

    public function __construct(GroupService $groupservice){
        $this->modelService = $groupservice;
        $this->modelAlias = "Group";
    }

    public function export(Request $request){
        $exportFileTypes = array(
            'csv' => \Maatwebsite\Excel\Excel::CSV,
            'excel' => \Maatwebsite\Excel\Excel::XLSX,
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
        );

        $exportFileName = array(
            'csv' => 'groups '.Carbon::now()->format('Y-m-d').'.csv',
            'excel' => 'groups '.Carbon::now()->format('Y-m-d').'.xlsx',
            'pdf' => 'groups '.Carbon::now()->format('Y-m-d').'.pdf',
        );

        return (new GroupExport($request->selectedIds ?? []))
            ->download(
                $exportFileName[$request->exportType] ?? $exportFileName['excel'],
                $exportFileTypes[$request->exportType ?? 'excel'],
            );
    }
}
