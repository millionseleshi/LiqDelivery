<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $valid_request = $this->getAddressValidator();
        $address = Address::create($valid_request);
        return new JsonResponse($address, Response::HTTP_CREATED);
    }

    /**
     * @return array
     */
    public function getAddressValidator(): array
    {
        $valid_request = \request()->validate([
            'longitude' => 'required',
            'latitude' => 'required',
            'city' => 'sometimes|required',
            'postal_code' => 'sometimes|required',
            'subcity' => 'sometimes|required',
            'woreda' => 'sometimes|required',
            'kebela' => 'sometimes|required',
            'houseno' => 'sometimes|required',
            'special_name' => 'sometimes|required'
        ]);
        return $valid_request;
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
        $valid_request = $this->getUpdateAddressValidator();
        $updated_product = $address->update($valid_request);
        return new JsonResponse($updated_product, Response::HTTP_OK);
    }

    /**
     * @return array
     */
    public function getUpdateAddressValidator(): array
    {
        $valid_request = \request()->validate([
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
        return $valid_request;
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
