<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountManager extends Model
{
    use HasFactory;

    protected $fillable = ["uuid", "account_pin",  "account_code", "first_name", "last_name", "middle_name", "photo", "mobile_no"];

    public $fileColumns = ["photo"];

    protected $appends = ["full_name"];

    public $defaultSortKey = [ "first_name", "last_name"];

    /**
     * get the account manager user account
     */
    public function user()
    {
        return $this->morphOne(User::class, "userable", null, "userable_uuid", "uuid");
    }


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
