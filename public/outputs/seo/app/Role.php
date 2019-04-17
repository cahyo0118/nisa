<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permission', 'role_id', 'permission_id');
    }
}
