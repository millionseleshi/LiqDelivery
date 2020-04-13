<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $address = factory(Address::class, 1)->create();
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'user_name' => $faker->userName,
        'email' => $faker->email,
        'password' =>  $faker->password,
        'status'=>$faker->randomElement(array('active','inactive')),
        'phone_number' => Str::slug('09' . $faker->phoneNumber),
        'alternative_phone_number' => $faker->phoneNumber,
        'role' => $faker->randomElement(array('customer', 'deliverer', 'officeworker','admin')),
        'address_id' => Address::first()->id,
    ];
});
