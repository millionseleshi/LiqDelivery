<?php

namespace App\Http\Controllers\Api;

use App\CustomerOrder;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new JsonResponse(CustomerOrder::all(), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order = CustomerOrder::findorFail($id);
            return new JsonResponse($order, Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse("order not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerOrder $order)
    {
        $validator = $this->getUpdateValidator();
        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            if (\request()->has('ordered_date')) {
                return new JsonResponse("Ordered date can't up updated", Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $order->update(["note"=>\request('note')]);
                return new JsonResponse($order, Response::HTTP_OK);
            }
        }
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getUpdateValidator(): \Illuminate\Contracts\Validation\Validator
    {
        $validator = Validator::make(\request()->all(), [
            'note' => ['sometimes','required']
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
        try {
            CustomerOrder::findorFail($id);
            CustomerOrder::destroy($id);
            return new JsonResponse("order deleted", Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse("order not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
