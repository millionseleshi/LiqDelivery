<?php

namespace Tests\Feature;

use App\Address;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserSignUp()
    {
        $response = $this->post('/api/signup', $this->getUser());
        $this->assertCount(1, User::all());
        $response->assertJson(["token_type" => "Bearer ", "message" => "user created"]);
        $this->assertNotNull($response->json('access_token'));
        $response->assertStatus(201);
    }

    private function getUser(): array
    {
        $address = factory(Address::class, 1)->create();
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'user_name' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => $password = $this->faker->password,
            'password_confirmation' => $password,
            'phone_number' => Str::slug('09' . $this->faker->phoneNumber),
            'alternative_phone_number' => $this->faker->phoneNumber,
            'role' => $this->faker->randomElement(array('customer', 'deliverer', 'officeworker')),
            'address_id' => Address::first()->id,
        ];
    }

    public function testFirstNameIsRequired()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['first_name' => '']));
        $response->assertSessionHasErrors(['first_name']);
    }

    public function testLastNameIsRequired()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['last_name' => '']));
        $response->assertSessionHasErrors(['last_name']);
    }

    public function testPhoneNumberIsRequired()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['phone_number' => '']));
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function testPasswordIsRequired()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['password' => '']));
        $response->assertSessionHasErrors(['password']);
    }

    public function testPasswordLength()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['password' => '214w']));
        $response->assertSessionHasErrors(['password']);
    }

    public function testPasswordConfirmation()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['password_confirmation' => '']));
        $response->assertSessionHasErrors(['password']);
    }

    public function testEmailUniqueness()
    {
        $this->post('/api/signup', array_merge($this->getUser(), ['email' => 'test@gmail.com']));
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['email' => 'test@gmail.com']));
        $response->assertSessionHasErrors(['email']);
    }

    public function testValidEmailIsEntered()
    {
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['email' => 'test.com']));
        $response->assertSessionHasErrors(['email']);
    }

    public function testUsernameUniqueness()
    {
        $this->post('/api/signup', array_merge($this->getUser(), ['user_name' => 'dagu86']));
        $response = $this->post('/api/signup', array_merge($this->getUser(), ['user_name' => 'dagu86']));
        $response->assertSessionHasErrors(['user_name']);
    }

    public function testUserSignInWithUserNameAndPassword()
    {
        $this->withoutExceptionHandling();
        $user_detail = ['user_name' => 'pappy', 'password' => '1234@pass!', 'password_confirmation' => '1234@pass!'];

        $this->post('/api/signup', array_merge($this->getUser(), $user_detail));
        $credentials = [
            'user_name' => 'pappy',
            'password' => '1234@pass!',
            'remember_me' => true,
        ];
        $response = $this->post('api/signin', $credentials);
        $this->assertNotNull($response->json('access_token'));
        $response->assertJson(["message" => "successfully logged in", "token_type" => "Bearer "]);
    }

    public function testUserSignInWithEmailAndPassword()
    {
        $user_detail = ['email' => 'pappy@liq.com', 'password' => '1234@pass!', 'password_confirmation' => '1234@pass!'];

        $this->post('/api/signup', array_merge($this->getUser(), $user_detail));
        $credentials = [
            'email' => 'pappy@liq.com',
            'password' => '1234@pass!',
            'remember_me' => true,
        ];
        $response = $this->post('api/signin', $credentials);
        $this->assertNotNull($response->json('access_token'));
        $response->assertJson(["message" => "successfully logged in", "token_type" => "Bearer "]);
    }

    public function testUserSignInWithPhoneNumberAndPassword()
    {
        $user_detail = ['phone_number' => '0911213', 'password' => '1234@pass!', 'password_confirmation' => '1234@pass!'];

        $this->post('/api/signup', array_merge($this->getUser(), $user_detail));
        $credentials = [
            'phone_number' => '0911213',
            'password' => '1234@pass!',
            'remember_me' => true,
        ];
        $response = $this->post('api/signin', $credentials);
        $this->assertNotNull($response->json('access_token'));
        $response->assertJson(["message" => "successfully logged in", "token_type" => "Bearer "]);
        return $response;
    }

    public function testUserSignInRequiresAtLeastPhoneNumberAndPassword()
    {
        $user_detail = [
            'phone_number' => '0911213', 'password' => '1234@pass!',
            'password_confirmation' => '1234@pass!',
            'email' => '', 'user_name' => ''];

        $this->post('/api/signup', array_merge($this->getUser(), $user_detail));
        $credentials = [
            'password' => '1234@pass!',
            'remember_me' => true,
        ];
        $response = $this->post('api/signin', $credentials);
        //$this->assertNull($response->json('access_token'));
        $response->assertSessionHasErrors(['phone_number']);
    }

    public function testUnAuthenticatedUser()
    {
        $this->post('/api/signup', array_merge($this->getUser()));
        $credentials = [
            'phone_number' => '0911213',
            'password' => '1234@pass!',
            'remember_me' => true,
        ];
        $response = $this->post('api/signin', $credentials);
        $this->assertNull($response->json('access_token'));
        $response->assertExactJson(['unauthenticated']);
    }

    public function testUserSignOut()
    {

        Passport::actingAs(factory(User::class)->create());
        $user=new User();
        $response=$this->get('/api/signout',['headers'=>[
            'Authorization'=>Str::slug('Bearer '. $user->token())]
        ]);
        $response->assertExactJson(["successfully logged out"]);
    }

    public function testGetAuthUser()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(factory(User::class)->create());
        $response=$this->get('/api/user');
        $response->assertStatus(200);
    }


    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call(' passport:install');
    }

}
