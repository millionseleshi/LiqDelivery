<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude,
        'city' => $faker->city,
        'subcity' => $faker->city,
        'postal_code' => $faker->postcode,
        'woreda' => $faker->streetAddress,
        'kebela' => $faker->streetAddress,
        'houseno' => $faker->streetAddress,
        'special_name' => $faker->address
    ];
});
