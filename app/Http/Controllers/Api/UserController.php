<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(User::all(), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return new JsonResponse($user, Response::HTTP_FOUND);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse("user not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(User $user)
    {
       if($this->getUpdateValidate($user)->fails())
       {
          $errors= $this->getUpdateValidate($user)->errors();
           return new JsonResponse($errors,Response::HTTP_UNPROCESSABLE_ENTITY);
       }
       else{
           $user->update(\request()->all());
           return new JsonResponse($user, Response::HTTP_OK);
       }

    }

    /**
     * @param User $user
     */
    public function getUpdateValidate(User $user)
    {
         $validator=Validator::make(\request()->all(),[
            'first_name' => ['sometimes', 'required', 'min:2'],
            'last_name' => ['sometimes', 'required', 'min:2'],
            'phone_number' => ['sometimes', 'required'],
            'status' => ['sometimes', 'required', Rule::in(['active', 'inactive'])],
            'user_name' => ['sometimes', 'required', 'unique:users,user_name',
                Rule::unique('users')->ignore($user->id), 'min:3'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email',
                Rule::unique('users')->ignore($user->id)],
            'alternative_phone_number' => ['sometimes', 'required'],
        ]);

         return $validator;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try
        {  User::findOrFail($id);
            User::destroy($id);
            return new  JsonResponse("user deleted",Response::HTTP_OK);
        }
        catch (ModelNotFoundException $exception)
        {
            return new JsonResponse("user not found",Response::HTTP_OK);
        }
    }
}
