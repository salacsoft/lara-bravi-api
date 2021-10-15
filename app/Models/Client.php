<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "uuid",
        "company_uuid",
        "client_code"  ,
        "client_name",
        "client_address",
        "is_active"
    ];

    public $searchableColumns = ["uuid", "company_uuid", "client_code", "client_name", "client_address", "is_active", "created_at", "updated_at"];

    public $defaultSortKey = "client_name";
}
