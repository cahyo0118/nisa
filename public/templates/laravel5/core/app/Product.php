<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }


}
