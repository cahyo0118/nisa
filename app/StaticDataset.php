<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaticDataset extends Model
{
    public function field()
    {
        return $this->hasOne('App\Field');
    }
}
