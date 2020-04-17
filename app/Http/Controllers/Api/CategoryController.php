<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return new JsonResponse(Category::all(), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $validation = Validator::make(\request()->all(), [
            'category_name' => 'required|string|unique:categories,category_name',
            'category_description' => 'sometimes|string',
        ]);

        if ($validation->fails()) {
            return new JsonResponse($validation->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $category = Category::create(\request()->all());
            return new JsonResponse($category, \Illuminate\Http\Response::HTTP_CREATED);
        }
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
            $category = Category::findOrFail($id);
            return new JsonResponse($category, Response::HTTP_FOUND);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse('category not found', Response::HTTP_NOT_FOUND);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Category $category)
    {
        $validator = Validator::make(\request()->all(),[
            'category_name' => ['sometimes','required', 'string', Rule::unique('categories')->ignore($category->id)],
            'category_description' => 'sometimes|string'
        ]);
        if($validator->fails())
        {
           return new JsonResponse($validator->errors(),Response::HTTP_UNPROCESSABLE_ENTITY);
        }
         $category->update(\request()->all());
        return new JsonResponse($category, Response::HTTP_OK);
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
           Category::findOrFail($id);
            Category::destroy($id);
            return new JsonResponse('category deleted', Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse('category not found', Response::HTTP_NOT_FOUND);
        }
    }

}
