<?php

namespace Tests\Feature;

use App\CustomerOrder;
use App\ShoppingCart;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerOrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function testUpdateOrder()
    {
        factory(CustomerOrder::class, 1)->create();
        $order = CustomerOrder::first();
        $this->put('/api/orders/' . $order->id, ['note' => "some special request"]);
        $this->assertEquals("some special request", CustomerOrder::first()->note);
    }

    public function testShowOrder()
    {
        factory(CustomerOrder::class, 1)->create();
        $order = CustomerOrder::first();
        $response = $this->get('/api/orders/' . $order->id);
        $response->assertExactJson($order->toArray());
    }

    public function testOrderNotFound()
    {
        $response = $this->get('/api/orders/' . $this->faker->randomDigit);
        $response->assertExactJson(['order not found']);
    }

    public function testDeleteOrder()
    {

        factory(CustomerOrder::class, 1)->create();
        $order = CustomerOrder::first();
        $response = $this->delete('/api/orders/' . $order->id);
        $this->assertCount(0, CustomerOrder::all());
        $response->assertExactJson(['order deleted']);
    }

    public function testGetAllOrder()
    {
        factory(CustomerOrder::class, 2)->create();
        $response = $this->get('/api/orders');
        $response->assertExactJson(CustomerOrder::all()->toArray());
    }


    public function getOrderData()
    {
        factory(User::class, 1)->create();
        factory(ShoppingCart::class,1)->create();
        return [
            'ordered_date' => Carbon::now()->addDay(1),
            'pickup_date' => Carbon::now()->addDay(2),
            'shopping_cart_id'=>ShoppingCart::first()->id,
            'note' => $this->faker->sentence,
            'user_id' => User::first()->id
        ];
    }
}
