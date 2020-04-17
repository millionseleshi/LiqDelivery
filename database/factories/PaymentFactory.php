<?php

/** @var Factory $factory */

use App\CustomerOrder;
use App\Payment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Payment::class, function (Faker $faker) {
    \factory(CustomerOrder::class, 1)->create();
    return [
        'total_cost' => $faker->randomFloat(),
        'amount_paid' => $faker->randomFloat(),
        'payment_type' => $faker->randomElement(array('on_bank', 'on_delivery', 'deposit')),
        'customer_order_id' => CustomerOrder::first()->id
    ];
});
