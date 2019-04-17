<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = "users";

    use HasApiTokens, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var  array
    */
    protected $fillable = [
        'name',
        'email',
        'address',
        'password',
        'photo',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var  array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }

}
