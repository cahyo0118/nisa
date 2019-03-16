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
}
