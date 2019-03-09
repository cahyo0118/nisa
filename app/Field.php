<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public function table()
    {
        return $this->belongsTo('App\Table');
    }

    public function relation()
    {
        return $this->hasOne('App\Relation');
    }
}
