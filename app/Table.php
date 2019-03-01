<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    public static $validation = [
        'store' => [
            'table_project_id' => 'required',
            'table_name' => 'required|max:50',
            'table_display_name' => 'required',
        ],
        'update' => [
            'table_project_id' => 'required',
            'table_name' => 'required|max:50',
            'table_display_name' => 'required',
        ]
    ];

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }

    public function fields()
    {
        return $this->hasMany('App\Field');
    }
}
