<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded=[];

    //Category has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
