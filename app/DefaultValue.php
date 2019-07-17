<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultValue extends Model
{
    public function generate_option()
    {
        return $this->belongsTo('App\GenerateOption', 'generate_option_id');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'variable_project', 'variable_id', 'project_id')->withPivot(['value']);
    }
}
