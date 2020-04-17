<?php

/** @var Factory $factory */

use App\ShoppingCart;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ShoppingCart::class, function (Faker $faker) {
    \factory(User::class, 1)->create();
    return [
        'key' => $faker->uuid,
        'user_id' => User::first()->id
    ];
});
