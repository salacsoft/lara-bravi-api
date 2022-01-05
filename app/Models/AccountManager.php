<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountManager extends Model
{
    use HasFactory;

    protected $appends = ["full_name"];

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
