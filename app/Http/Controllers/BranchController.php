<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\BranchService;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Str;
use App\Exports\BranchExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BranchController extends Controller
{
		use ResponseTrait;

    protected $branchService;

		public function __construct(BranchService $branchservice){
			$this->modelService = $branchservice;
			$this->modelAlias = " Branch ";
		}

		public function exportBranches(Request $request){
			$exportFileTypes = array(
				'csv' => \Maatwebsite\Excel\Excel::CSV,
				'excel' => \Maatwebsite\Excel\Excel::XLSX,
				'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
			);

			$exportFileName = array(
				'csv' => 'branches '.Carbon::now()->format('Y-m-d').'.csv',
				'excel' => 'branches '.Carbon::now()->format('Y-m-d').'.xlsx',
				'pdf' => 'branches '.Carbon::now()->format('Y-m-d').'.pdf',
			);

			return (new BranchExport($request->selectedIds ?? []))
				->download(
					$exportFileName[$request->exportType] ?? $exportFileName['excel'],
					$exportFileTypes[$request->exportType ?? 'excel'],
				);
		}
}
