<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ["uuid", "group_name"];

    public $searchableColumns = ["uuid", "group_name"];

	public $defaultSortKey = "group_name";
}
