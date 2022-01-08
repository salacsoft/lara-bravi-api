<?php

namespace App\Services;

use DB;
use Illuminate\Support\Str;
use App\Models\Branch;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;


class BranchService extends BaseService {

	protected $branch;

	public function __construct(Branch $model) {

		parent::__construct($model);
		$this->branch = $model;
		//		variable to hold array ->  this columns is declared on model
		$this->searchableColumns = $this->model->searchableColumns;
		// if nothing declare on model it will get error
		$this->defaultSortKey = $this->model->defaultSortKey ?? "branch_name" ;

		//instantiate a variable to call in base controller $this->modelService->requestValidator then call the methods of request class
		$this->requestValidator = new BranchRequest;

        $this->modelResource = "App\Http\Resources\BranchResource";

	}

	// this methods will generate new format
	public function getAll($request) {
		$data = Parent::getAll($request);
		return BranchResource::collection($data);
	}
}
