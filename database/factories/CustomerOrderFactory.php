<?php

/** @var Factory $factory */

use App\CustomerOrder;
use App\ShoppingCart;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(CustomerOrder::class, function (Faker $faker) {
    factory(User::class, 1)->create();
    return [
        'ordered_date' => \Carbon\Carbon::today(),
        'total_price'=>$faker->randomDigit,
        'note' => $faker->sentence,
        'user_id' => User::first()->id
    ];
});
