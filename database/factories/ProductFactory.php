<?php

/** @var Factory $factory */

use App\Category;
use App\Product;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;

$factory->define(Product::class, function (Faker $faker) {
    $category_id = Category::all()->pluck('id');
    return [
        'product_name' => $faker->name(),
        'product_description' => $faker->paragraph(),
        'product_image' => UploadedFile::fake()->image('tests/stubs/images.jpg', 256, 197),
        'price_per_unit' => random_int(1, 1000),
        'category_id' => $faker->randomElement($category_id)
    ];
});
