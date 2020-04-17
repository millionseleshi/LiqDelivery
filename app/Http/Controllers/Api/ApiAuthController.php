<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        $user_request = $this->getUserCreateValidator();
        if ($user_request->fails()) {
            return new JsonResponse($user_request->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user = User::create(array_merge(request()->all(),
                ['password' => bcrypt(request('password'))]
            ));
            $success['access_token'] = $user->createToken('user token')->accessToken;
            $success['token_type'] = "Bearer ";
            $success['message'] = "user created";

            return new JsonResponse($success, Response::HTTP_CREATED);

        }


    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getUserCreateValidator()
    {
        $user_request = Validator::make(request()->all(), [
            'first_name' => ['required', 'min:2'],
            'last_name' => ['required', 'min:2'],
            'password' => ['required', 'min:8', 'confirmed'],
            'phone_number' => ['required'],
            'status' => ['sometimes', 'required', Rule::in(['active', 'inactive'])],
            'user_name' => ['sometimes', 'required', 'unique:users,user_name', 'min:3'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email'],
            'alternative_phone_number' => ['sometimes', 'required'],
            'role' => 'required',
            'address_id' => ['sometimes', 'required'],
        ]);
        return $user_request;
    }

    public function signout()
    {
        request()->user()->token()->revoke();
        return new JsonResponse('successfully logged out', Response::HTTP_OK);
    }

    public function user()
    {
        return new JsonResponse(request()->user(), Response::HTTP_OK);
    }
}
