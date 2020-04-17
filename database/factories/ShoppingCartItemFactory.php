<?php

/** @var Factory $factory */

use App\Product;
use App\ShoppingCart;
use App\ShoppingCartItem;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ShoppingCartItem::class, function (Faker $faker) {
    \factory(Product::class, 1)->create();
    \factory(ShoppingCart::class, 1)->create();
    return [
        'shopping_cart_id'=>ShoppingCart::first()->id,
        'product_id'=>Product::first()->id,
        'quantity'=>$faker->randomDigit
    ];
});
