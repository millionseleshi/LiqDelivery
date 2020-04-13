<?php

namespace Tests\Feature;

use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllUser()
    {
        factory(User::class, 2)->create();
        $response = $this->get('/api/users');
        $response->assertJson(User::all()->toArray());
        $response->assertStatus(200);
    }

    public function testGetUserById()
    {
        factory(User::class, 1)->create();
        $user = User::first();
        $response = $this->get('/api/users/' . $user->id);
        $response->assertJson($user->toArray());
        $response->assertStatus(302);
    }

    public function testUserNotFound()
    {
        $response = $this->get('/api/users/' . $this->faker->randomDigit);
        $response->assertExactJson(["user not found"]);
        $response->assertStatus(404);
    }

    public function testUpdateUser()
    {
        factory(User::class,1)->create();
        $update_user=[
            'first_name'=>'new_first_name',
            'user_name'=>$this->faker->userName
        ];
        $user=User::first();
        $response = $this->put('/api/users/'.$user->id,$update_user);
        $this->assertEquals('new_first_name',User::first()->first_name);
        $response->assertJson(User::first()->toArray());
    }

    public function testDeleteUser()
    {
        $this->withoutExceptionHandling();
       factory(User::class,1)->create();
        $user=User::first();
        $response = $this->delete('/api/users/'.$user->id);
        $this->assertCount(0,User::all());
        $response->assertExactJson(["user deleted"]);
    }


    private function getUser(): array
    {
        $address = factory(Address::class, 1)->create();
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'user_name' => $this->faker->userName,
            'email' => $this->faker->email,
            'status' => $this->faker->randomElement(array(['customer', 'deliverer', 'officeworker', 'admin'])),
            'password' => $password = $this->faker->password,
            'password_confirmation' => $password,
            'phone_number' => Str::slug('09' . $this->faker->phoneNumber),
            'alternative_phone_number' => $this->faker->phoneNumber,
            'role' => 'admin',
            'address_id' => Address::first()->id,
        ];
    }

}
