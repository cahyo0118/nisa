<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuLoadReference extends Model
{
    protected $table = 'menu_load_references';

    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }

    public function field()
    {
        return $this->belongsTo('App\Field');
    }

    public function field_reference()
    {
        return $this->belongsTo('App\Field', 'field_reference_id');
    }
}
