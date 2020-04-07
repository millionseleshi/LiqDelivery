<?php

use App\Address;
use App\Category;
use App\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        factory(Category::class, 1)->create();
        factory(Product::class, 1)->create();
        factory(Address::class, 1)->create();
    }
}
