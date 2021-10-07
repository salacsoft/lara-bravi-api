<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\BranchService;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use Illuminate\Support\Str;

class BranchController extends Controller
{
		use ResponseTrait;

    protected $branchService;

		public function __construct(BranchService $branchservice){
			$this->modelService = $branchservice;
			$this->modelAlias = " Branch ";
		}
}
