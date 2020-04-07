<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    //Product belongsTo Category
    public function testProductBelongsToCategory()
    {
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create(['category_id' => $category->id]);
        $this->assertInstanceOf(Category::class, $product->category);
    }

    //Only attributes required for product
    public function testCreateProduct()
    {
        $category = factory(Category::class)->create();
        Product::create([
            'product_name' => $this->faker->name,
            'price_per_unit' => random_int(1, 1000),
            'category_id' => $category->id
        ]);

        $this->assertCount(1, Product::all());
    }
}
