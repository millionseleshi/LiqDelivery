<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $validator = $this->getCreateValidator();
        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $payment = new Payment();
            $payment['amount_paid'] = \request('amount_paid');
            $payment['payment_type'] = \request('payment_type');

            $payment->order()->where('id', '=', \request('customer_order_id'))->pluck('');

            return new JsonResponse($payment, Response::HTTP_CREATED);
        }

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getCreateValidator(): \Illuminate\Contracts\Validation\Validator
    {
        $validator = Validator::make(\request()->all(), [
            'amount_paid' => ['numeric'],
            'customer_order_id' => ['required', 'exists:customer_orders,id'],
            'payment_type' => ['required', 'in:on_bank,deposit,on_delivery'],
        ]);
        return $validator;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
