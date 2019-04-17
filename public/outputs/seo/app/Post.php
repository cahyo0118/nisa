<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'role_permission', 'role_id', 'permission_id');
    }
}
