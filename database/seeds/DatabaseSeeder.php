<?php

use App\Address;
use App\Category;
use App\CustomerOrder;
use App\Payment;
use App\Product;
use App\ShoppingCart;
use App\User;
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
        factory(User::class, 1)->create();
        factory(ShoppingCart::class, 1)->create();
        factory(CustomerOrder::class, 1)->create();
        factory(Payment::class, 1)->create();
    }
}
