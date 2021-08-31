<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CquoinUser extends Model
{
    use HasFactory;

    protected $fillable = ["uuid", "last_name", "first_name", "middle_name", "mobile_no"];

    /**
     * get the cquoin users user account
     */
    public function user()
    {
        return $this->morphOne(User::class, "userable", null, "userable_uuid", "uuid");
    }
}
