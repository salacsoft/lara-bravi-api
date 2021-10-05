<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        "uuid",
        "company_uuid",
        "client_code"  ,
        "client_name",
        "client_address",
        "is_active"
    ];

    public $searchableColumns = ["uui", "company_uuid", "client_code", "client_name", "client_address", "is_active", "created_at", "updated_at"];

    public $defaultSortKey = "client_name";
}
