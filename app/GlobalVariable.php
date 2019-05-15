<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalVariable extends Model
{
    public function generate_option()
    {
        return $this->belongsTo('App\GenerateOption', 'generate_option_id');
    }
}
