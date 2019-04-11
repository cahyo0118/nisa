<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public function field()
    {
        return $this->belongsTo('App\Field');
    }

    public function table()
    {
        return $this->belongsTo('App\Table', 'table_id');
    }

    public function foreign_key_field()
    {
        return $this->belongsTo('App\Field', 'relation_foreign_key');
    }
}
