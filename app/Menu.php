<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
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

    public function sub_menus()
    {
        return $this->hasMany('App\Menu', 'parent_menu_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function table()
    {
        return $this->belongsTo('App\Table', 'table_id');
    }

    public function field_criterias()
    {
        return $this->belongsToMany('App\Field', 'menu_criteria', 'menu_id', 'field_id')->withPivot(['operator', 'value']);
    }

}
