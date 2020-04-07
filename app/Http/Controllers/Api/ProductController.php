<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Product;
use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductController extends Controller
{
    use UploadTrait;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(Product::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $this->getProductValidator()->validate();

        $product = $this->getProductRequest();
        $created_product = Product::create($product);
        return new JsonResponse($created_product, ResponseAlias::HTTP_CREATED);

    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getProductValidator(): \Illuminate\Contracts\Validation\Validator
    {
        $validator = Validator::make(\request()->all(), [
            'product_name' => ['required', 'unique:products,product_name'],
            'product_description' => ['sometimes', 'required', 'string'],
            'product_image' => ['sometimes', 'required', 'image', 'mimes:jpeg,png,jpg,gif,svg,', 'max:2048'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id']
        ]);
        return $validator;
    }

    /**
     * @return array
     */
    public function getProductRequest(): array
    {
        $product = \request()->all();
        if (request()->has('product_image')) {
            $image = \request()->file('product_image');
            $fileName = Str::slug(\request('product_name') . '__' . time());
            $folder = '/uploads/images/';
            $filePath = $folder . $fileName . '.' . $image->getClientOriginalExtension();
            $this->storeImage(\request(), 'product_image', $folder);
            $product['product_image'] = $filePath;
        }
        return $product;
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
            $product = Product::findOrFail($id);
            return new JsonResponse($product, ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse("product not found", ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Product $product)
    {
        $validator = $this->getUpdateValidator($product);

        $validator->validate();

        if ($product->getAttribute('product_image') != null) {
            $this->getProductRequest();
        }
        $product->update(\request()->all());
        return new JsonResponse($product, ResponseAlias::HTTP_OK);
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getUpdateValidator(Product $product): \Illuminate\Contracts\Validation\Validator
    {
        $validator = Validator::make(\request()->all(), [
            'product_name' => ['sometimes', 'required', Rule::unique('products')->ignore($product->id)],
            'product_description' => ['sometimes', 'required', 'string'],
            'product_image' => ['sometimes', 'required', 'image', 'mimes:jpeg,png,jpg,gif,svg,', 'max:2048'],
            'price_per_unit' => ['sometimes', 'required', 'numeric', 'min:0'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id']
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
        try {
            Product::destroy($id);
            return new JsonResponse('product deleted', ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse("product not found", ResponseAlias::HTTP_NOT_FOUND);
        }
    }
}
