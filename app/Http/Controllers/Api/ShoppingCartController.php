<?php

namespace App\Http\Controllers\Api;


use App\CustomerOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShoppingCartItemCollection;
use App\Product;
use App\ShoppingCart;
use App\ShoppingCartItem;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShoppingCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(ShoppingCart::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        if (Auth::guard('api')->check()) {
            $user_id = auth('api')->user()->getKey();
        }

        $cart = ShoppingCart::create([
            'key' => (string)Str::uuid(),
            'user_id' => isset($user_id) ? $user_id : null,

        ]);
        return new JsonResponse([
            'message' => 'cart created',
            'cart_id' => $cart->id,
            'cart_key' => $cart->key,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param ShoppingCart $cart
     * @return JsonResponse
     */
    public function show($id)
    {
        $cart = ShoppingCart::all()->find($id);
        if ($cart != null) {
            return new JsonResponse([
                'cart_id' => $cart->id,
                'cart_key' => $cart->key,
                'items_in_Cart' => new ShoppingCartItemCollection($cart->items),
            ], Response::HTTP_OK);
        } else {
            return new JsonResponse("shopping cart not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $cart = ShoppingCart::all()->find($id);
        if ($cart != null) {
            $cart->delete();
            return new JsonResponse("shopping cart deleted", Response::HTTP_OK);
        } else {
            return new JsonResponse("shopping cart not found", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    public function addProducts(ShoppingCart $cart)
    {
        $validator = $this->getAddProductValidator();

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $product_id = \request('product_id');
        $quantity = \request('quantity');


        //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
        $cartItem = ShoppingCartItem::where(['shopping_cart_id' => $cart->getKey(), 'product_id' => $product_id])->first();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            ShoppingCartItem::where(['shopping_cart_id' => $cart->getKey(), 'product_id' => $product_id])->update(['quantity' => $quantity]);
        } else {
            ShoppingCartItem::create(['shopping_cart_id' => $cart->getKey(), 'product_id' => $product_id, 'quantity' => $quantity]);
        }

        return new JsonResponse("product added to cart", Response::HTTP_OK);

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getAddProductValidator(): \Illuminate\Contracts\Validation\Validator
    {
        $validator = Validator::make(\request()->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ]);
        return $validator;
    }


    public function removeProduct(ShoppingCart $cart)
    {
        $validator = Validator::make(\request()->all(), [
            'product_id' => ['required', 'exists:shopping_cart_items,product_id']
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $cart->items()->where('product_id', \request('product_id'))->delete();
            return new JsonResponse("product deleted from cart", Response::HTTP_OK);
        }
    }

    public function checkOut(ShoppingCart $cart)
    {
        if (Auth::guard('api')->check()) {
            $user_id = auth('api')->user()->getKey();
        }
        $total_price = (float)0.0;

        //calcualte total price for items in the cart
        foreach ($cart->items() as $item) {
            $product = Product::find($item->produt_id);
            $unit_price = $product->price_per_unit;
            $in_stock = $product->units_in_stock;
            if ($in_stock >= $item->quantity) {
                $total_price = $total_price + ($unit_price * $item->quantity);
                $product->units_in_stock = $product->units_in_stock - $item->quantity;
                $product->save();
            } else {
                return new JsonResponse("out of stock", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

         // proccess code paymnet

        //add order
        $this->createOrder($total_price, $user_id);

        return new JsonResponse("order created", Response::HTTP_OK);

    }

    /**
     * @param $total_price
     * @param $userID
     */
    public function createOrder($total_price, $userID): void
    {
        $order = CustomerOrder::create(
            [
                "ordered_date" => Carbon::today()->format(''),
                "total_price" => $total_price,
                "user_id" => isset($user_id) ? $user_id : null]
        );
    }


}
