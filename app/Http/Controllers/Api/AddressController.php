<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(Address::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @return JsonResponse
     */
    public function store()
    {
        if($this->getAddressValidator()->fails())
        {
            $errors=$this->getAddressValidator()->errors();
            return new JsonResponse($errors,Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        else{
            $address = Address::create(\request()->all());
            return new JsonResponse($address, Response::HTTP_CREATED);
        }

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getAddressValidator()
    {
        $address_request = Validator::make(request()->all(),[
            'longitude' => 'required',
            'latitude' => 'required',
            'city' => ['sometimes','required'],
            'postal_code' => ['sometimes','required'],
            'subcity' =>['sometimes','required'],
            'woreda' => ['sometimes','required'],
            'kebela' =>['sometimes','required'],
            'houseno' => ['sometimes','required'],
            'special_name' =>['sometimes','required'],
        ]);
        return $address_request;
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
            $address = Address::findOrFail($id);
            return new JsonResponse($address, Response::HTTP_FOUND);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse('address not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Address $address)
    {

         if($this->getUpdateAddressValidator()->fails())
         {
             $errors=$this->getUpdateAddressValidator()->errors();
             return new JsonResponse($errors,Response::HTTP_UNPROCESSABLE_ENTITY);
         }
         else
         {   $address->update(\request()->all());
             return new JsonResponse($address, Response::HTTP_OK);
         }

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getUpdateAddressValidator()
    {
        $validator = Validator::make(\request()->all(),[
            'longitude' => 'sometimes|required',
            'latitude' => 'sometimes|required',
            'city' => 'sometimes|required',
            'postal_code' => 'sometimes|required',
            'subcity' => 'sometimes|required',
            'woreda' => 'sometimes|required',
            'kebela' => 'sometimes|required',
            'houseno' => 'sometimes|required',
            'special_name' => 'sometimes|required'
        ]);
        return $validator;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
