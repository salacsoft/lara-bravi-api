<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountManager extends Model
{
    use HasFactory;

    /**
     * get the account manager user account
     */
    public function user()
    {
        return $this->morpheOne(User::class, "userable");
    }
}
