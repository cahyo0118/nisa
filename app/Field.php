<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public function table()
    {
        return $this->belongsTo('App\Table', 'table_id');
    }

    public function relation()
    {
        return $this->hasOne('App\Relation');
    }

    public function menu_criterias()
    {
        return $this->belongsToMany('App\Menu', 'menu_criteria', 'field_id', 'menu_id')->withPivot(['operator', 'value']);
    }
}
