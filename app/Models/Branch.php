<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

		protected $fillable = [
			'uuid',
			'client_uuid',
			'branch_code',
			'branch_name',
			'branch_address',
			'area_uuid',
			'region_uuid',
		];


        public function client()
        {
            return $this->belongsTo(Client::class, "client_uuid", "uuid");
        }
}
