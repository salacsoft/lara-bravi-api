<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // // if your key name is not 'id'
    // // you can also set this to null if you don't have a primary key
    // protected $primaryKey = 'uuid';
    // public $incrementing = false;
    // // In Laravel 6.0+ make sure to also set $keyType
    // protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "uuid",
        'username',
        'email',
        'password',
        'user_type',
        'userable_type',
        'userable_uuid',
        'banned_until',
        'attempt',
        'device_info',
        'remember_token'
    ];

    public $searchableColumns = [
        "uuid",
        'username',
        'email',
        'password',
        'user_type',
        'userable_type',
        'userable_uuid',
        'banned_until',
        'attempt',
        'device_info',
        'remember_token'
    ];

    public $defaultSortKey ="username";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * get the parent userable model of user
     */
    public function userable()
    {
        //                    functionname morph type       morph id       ownerkey
        return $this->morphTo("userable", "userable_type", "userable_uuid", "uuid");
    }


        /**
     * set the users password
     * @param string $password
     * @return string $hash password
     */
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }


}
