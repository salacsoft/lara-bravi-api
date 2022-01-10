<?php

namespace App\Services;

use DB;
use Illuminate\Support\Str;
use App\Models\AccountManager;
use App\Http\Requests\AccountManagerRequest;
use App\Http\Resources\AccountManagerResource;


class AccountManagerService extends BaseService
{

	public function __construct(AccountManager $model) {

		parent::__construct($model);
		$this->branch = $model;
		//		variable to hold array ->  this columns is declared on model
		$this->searchableColumns = $this->model->getFillable();
		// if nothing declare on model it will get error
		$this->defaultSortKey = ["first_name"] ;

		//instantiate a variable to call in base controller $this->modelService->requestValidator then call the methods of request class
		$this->requestValidator = new AccountManagerRequest;

        $this->modelResource = "App\Http\Resources\AccountManagerResource";

        //location to upload
        $this->fileStoragePath = "public/account-manager";

	}

}
