<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ["uuid", "group_name"];

    public $searchableColumns = ["uuid", "group_name"];

	public $defaultSortKey = "group_name";
}
