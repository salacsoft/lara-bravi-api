<?php

namespace App\Services;

use DB;
use Illuminate\Support\Str;
use App\Models\Branch;


class BranchService extends BaseService {

	protected $branch;

	public function __construct(Branch $model) {

		parent::__construct($model);
		$this->branch = $model;
		$this->searchableColumns = ["uuid","branch_code","branch_name","branch_address"];
		$this->defaultSortKey = "branch_name";

	}
}