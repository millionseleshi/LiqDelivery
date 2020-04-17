<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCartItem extends Model
{
    protected $guarded = [];

    public function cart()
    {
        return $this->belongsTo(ShoppingCart::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
