<?php

namespace Tests\Feature;

use App\CustomerOrder;
use App\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreatePayment()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/payments', $this->getPayment());
        $this->assertCount(1, Payment::all());
        $this->assertJson(Payment::first()->toArray());
        $response->assertStatus(201);
    }

    private function getPayment()
    {
        factory(CustomerOrder::class, 1)->create();
        return [
            'total_amount' => random_int(10, 1000),
            'amount_paid' => random_int(10, 100),
            'customer_order_id' => CustomerOrder::first()->id,
            'payment_type' => $this->faker->randomElement(array(['on_bank', 'ondelivery', 'deposite']))
        ];
    }
}
