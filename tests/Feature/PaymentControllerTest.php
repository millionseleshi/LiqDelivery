<?php

namespace Tests\Feature;

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
//        $response = $this->post('/api/payments');
//
//        $response->assertStatus(200);
    }

    private function getPayment()
    {
        return [
            'total_amount'=>random_int(10,1000),
            'amount_paid'=>random_int(10,100),
    ];
    }
}
