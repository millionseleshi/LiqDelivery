<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $guarded = [];
    public function items()
    {
        return $this->hasMany(ShoppingCartItem::class, 'shopping_cart_id');
    }

}
