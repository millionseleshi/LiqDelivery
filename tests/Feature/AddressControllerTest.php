<?php

namespace Tests\Feature;

use App\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateAddress()
    {
        $response = $this->post('/api/addresses', $this->getAddress(), ['Accept' => 'application/json']);
        $response->assertStatus(201);
        $this->assertCount(1, Address::all());
    }

    public function getAddress()
    {
        return [
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
            'city' => $this->faker->city,
            'subcity' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'woreda' => $this->faker->streetAddress,
            'kebela' => $this->faker->streetAddress,
            'houseno' => $this->faker->name,
            'special_name' => $this->faker->address
        ];
    }

    public function testLongitudeIsRequired()
    {

        $response = $this->post('/api/addresses', array_merge($this->getAddress(), ['longitude' => '']));
        $response->assertSessionHasErrors(['longitude']);
    }

    public function testLatitudeIsRequired()
    {
        $response = $this->post('/api/addresses', array_merge($this->getAddress(), ['latitude' => '']));
        $response->assertSessionHasErrors(['latitude']);
    }

    public function testAddressUpdate()
    {
        $this->post('/api/addresses', $this->getAddress(), ['Accept' => 'application/json']);
        $address = Address::first();
        $response = $this->put('/api/addresses/' . $address->id, ['city' => 'nabula34S']);
        $this->assertEquals('nabula34S', Address::first()->city);
        $response->assertStatus(200);
    }

    public function testGetAddressById()
    {
        $this->post('/api/addresses', $this->getAddress(), ['Accept' => 'application/json']);
        $address = Address::first();
        $response = $this->get('/api/addresses/' . $address->id);
        $response->assertExactJson($address->toArray());
        $response->assertStatus(302);
    }

    public function testAddressNotFound()
    {
        $response = $this->get('/api/addresses/' . $this->faker->randomDigit);
        $response->assertExactJson(['address not found']);
        $response->assertStatus(404);
    }

    public function testGetAllAddress()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/addresses', $this->getAddress(), ['Accept' => 'application/json']);
        $this->post('/api/addresses', $this->getAddress(), ['Accept' => 'application/json']);
        $response=$this->get("/api/addresses");
        $this->assertCount(2,Address::all());
        $response->assertJson(Address::all()->toArray());

    }
}
