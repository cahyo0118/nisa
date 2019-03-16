<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public static $validation = [
        'store' => [
            'name' => 'required|max:50',
            'display_name' => 'required',
        ],
        'update' => [
            'name' => 'required|max:50',
            'display_name' => 'required',
        ]
    ];

    public function menus()
    {
        return $this->hasMany('App\Menu', 'project_id');
    }
}
