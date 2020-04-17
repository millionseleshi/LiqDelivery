<?php

namespace App\Http\Resources;

use App\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingCartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product = Product::find($this->product_id);
        return [
            'product_id'=>$product->id,
            'product_name'=>$product->product_name,
            'product_unit_price'=>$product->price_per_unit,
            'quantity'=>$this->quantity
        ];
    }
}
