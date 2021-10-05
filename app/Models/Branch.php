<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

		protected $fillable = [
			'uuid',
			'client_uuid',
			'branch_code',
			'branch_name',
			'branch_address',
			'area_uuid',
			'region_uuid',
		];
}
