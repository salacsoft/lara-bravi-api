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
			$this->branchService = $branchservice;
		}

		// list all branch
		public function all(Request $request){
			// $request->paginate;
			// 1000 = limit
			$branchesList = $this->branchService->getAll($request->paginate ?? 10000);

			return BranchResource::collection($branchesList);
		}

		// save new branch
		public function store(BranchRequest $request) {
			$data = $request->only($this->branchService->getFillable());
			$data['uuid'] = Str::random(30);
			$branch = $this->branchService->create($data);

			return $this->success(
				"New Branch successfully created",
				$branch,
				201
			);
		}
}
