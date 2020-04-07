<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use function request;

class ApiAuthController extends Controller
{
    public function signin()
    {
        $credentials = $this->getCredentials();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $access_token = $user->createToken('user Token');
            $token = $access_token->token;

            if (request('remember_me')) {

                $token->expires_at = Carbon::now()->addYears(1);
                $token->save();
            }
            $success['user'] = Auth::user();
            $success['message'] = "successfully logged in";
            $success['token_type'] = "Bearer ";
            $success['access_token'] = $access_token->accessToken;
            $success['expiresAt'] = Carbon::parse($access_token->token->expires_at)->toDateTimeString();
            return new JsonResponse($success, Response::HTTP_OK);
        } else {
            return new JsonResponse('unauthenticated', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        $credentials = [];
        request()->validate(
            [
                'remember_me' => 'boolean',
                'phone_number' => 'required_without_all:email,user_name'
            ]);
        $credentials['password'] = request('password');
        if (request()->has('user_name')) {
            $credentials['user_name'] = request('user_name');
        }
        if (request()->has('email')) {
            $credentials['email'] = request('email');
        }
        if (request('phone_number')) {
            $credentials['phone_number'] = request('phone_number');
        }
        return $credentials;
    }

    public function signup()
    {
        $valid_request = $this->getUserCreateValidator();

        $user = User::create(array_merge($valid_request,
            ['password' => bcrypt(request('password'))]
        ));
        $success['access_token'] = $user->createToken('user token')->accessToken;
        $success['token_type'] = "Bearer ";
        $success['message'] = "user created";

        return new JsonResponse($success, Response::HTTP_CREATED);
    }

    /**
     * @return array
     */
    public function getUserCreateValidator(): array
    {
        $valid_request = request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
            'phone_number' => ['required'],
            'user_name' => ['sometimes', 'required', 'unique:users,user_name'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email'],
            'alternative_phone_number' => ['sometimes', 'required'],
            'role' => 'required',
            'address_id' => ['sometimes', 'required'],
        ]);
        return $valid_request;
    }

    public function signout()
    {
        request()->user()->token()->revoke();
       return new JsonResponse('successfully logged out', Response::HTTP_OK);
    }

    public function user()
    {
        return new JsonResponse(request()->user(),Response::HTTP_OK);
    }
}
