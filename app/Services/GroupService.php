<?php

namespace App\Services;

use DB;
use Illuminate\Support\Str;
use App\Models\Group;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;


class GroupService extends BaseService {

	protected $group;

	public function __construct(Group $model) {

		parent::__construct($model);
		$this->branch = $model;
		//		variable to hold array ->  this columns is declared on model
		$this->searchableColumns = $this->model->searchableColumns;
		// if nothing declare on model it will get error
		$this->defaultSortKey = $this->model->defaultSortKey ?? "group_name" ;

		//instantiate a variable to call in base controller $this->modelService->requestValidator then call the methods of request class
		$this->requestValidator = new GroupRequest;

	}

	// this methods will generate new format
	public function getAll($request) {
		$data = Parent::getAll($request);
		return GroupResource::collection($data);
	}
}
