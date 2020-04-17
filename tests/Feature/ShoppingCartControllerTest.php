<?php

namespace Tests\Feature;

use App\CustomerOrder;
use App\Product;
use App\ShoppingCart;
use App\ShoppingCartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShoppingCartControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateShoppingCart()
    {
        $response = $this->post('/api/carts');
        $this->assertCount(1, ShoppingCart::all());
        $response->assertJsonPath("message", "cart created");
    }

    public function testShowShoppingCart()
    {
        $this->withoutExceptionHandling();
        factory(ShoppingCart::class, 1)->create();
        $response = $this->get('/api/carts/' . ShoppingCart::first()->id);
        $response->assertJsonPath("cart_id", ShoppingCart::first()->id);
        $response->assertJsonPath("cart_key", ShoppingCart::first()->key);
        $response->assertStatus(200);
    }

    public function testShoppingCartNotFound()
    {
        $response = $this->get('/api/carts/' . $this->faker->uuid);
        $response->assertExactJson(["shopping cart not found"]);
        $response->assertStatus(422);
    }

    public function testDeleteShoppingCart()
    {

        factory(ShoppingCart::class, 1)->create();
        $response = $this->delete('/api/carts/' . ShoppingCart::first()->id);
        $response->assertExactJson(['shopping cart deleted']);
        $this->assertCount(0, ShoppingCart::all());
        $response->assertStatus(200);
    }

    public function testAddProductToCart()
    {
        factory(ShoppingCart::class, 1)->create();
        factory(Product::class, 1)->create();
        $response = $this->post('/api/carts/add/' . ShoppingCart::first()->id, [
            'cart_key' => ShoppingCart::first()->key,
            'product_id' => Product::first()->id,
            'quantity' => '46',
        ]);
        $this->assertCount(1, ShoppingCartItem::all());
        $response->assertExactJson(["product added to cart"]);
    }

    public function testRemoveProductFromCart()
    {
        factory(ShoppingCartItem::class, 1)->create();
        factory(Product::class, 1)->create();
        $response = $this->post('/api/carts/remove/' . ShoppingCart::first()->id, ["product_id" => Product::first()->id]);
        $response->assertExactJson(["product deleted from cart"]);
    }

    public function testCheckOut()
    {
        factory(ShoppingCart::class, 1)->create();
        factory(Product::class, 1)->create();
        factory(ShoppingCartItem::class, 1)->create([
            "shopping_cart_id" => ShoppingCart::first()->id,
            "product_id" => Product::first()->id,
        ]);
        $response=$this->get('/api/carts/checkout/' . ShoppingCart::first()->id);
        $response->assertExactJson(["order created"]);
        $this->assertCount(1,CustomerOrder::all());

    }


}
