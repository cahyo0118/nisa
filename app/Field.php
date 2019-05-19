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
}
