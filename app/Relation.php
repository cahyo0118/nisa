<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public function field()
    {
        return $this->belongsTo('App\Field');
    }

    public function local_table()
    {
        return $this->belongsTo('App\Table', 'local_table_id');
    }

    public function table()
    {
        return $this->belongsTo('App\Table', 'table_id');
    }

    public function local_key_field()
    {
        return $this->belongsTo('App\Field', 'relation_local_key');
    }

    public function foreign_key_field()
    {
        return $this->belongsTo('App\Field', 'relation_foreign_key');
    }

    public function foreign_key_display_field()
    {
        return $this->belongsTo('App\Field', 'relation_display');
    }
}
