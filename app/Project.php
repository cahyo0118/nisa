<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public static $validation = [
        'store' => [
            'name' => 'required|max:50',
            'display_name' => 'required',
            'item_per_page' => 'required',
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

    public function tables()
    {
        return $this->hasMany('App\Table', 'project_id');
    }

    public function variables()
    {
        return $this->belongsToMany('App\GlobalVariable', 'variable_project', 'project_id', 'variable_id')->withPivot(['value']);
    }

}
