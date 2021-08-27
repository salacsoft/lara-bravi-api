<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStaff extends Model
{
    use HasFactory;

    /**
     * get the client staff user account
     */
    public function user()
    {
        return $this->morpheOne(User::class, "userable");
    }
}
