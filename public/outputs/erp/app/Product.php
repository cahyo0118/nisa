<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";


    public function product_details()
    {
        return $this->hasMany('App\ProductDetails', '28');
    }
}
