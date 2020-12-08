<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldCriteria extends Model
{
    protected $table = 'menu_criteria';

    public function field()
    {
        return $this->hasOne('App\Field');
    }
}
