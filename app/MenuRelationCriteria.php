<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuRelationCriteria extends Model
{
    protected $table = "menu_relation_criteria";

    public function relation_field()
    {
        return $this->belongsTo('App\Field');
    }

    public function relation()
    {
        return $this->belongsTo('App\Relation');
    }

    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
