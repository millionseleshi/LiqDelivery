<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new JsonResponse(Category::all(), ResponseAlias::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store()
    {
        $valid_data = \request()->validate([
            'category_name' => 'required|string|unique:categories,category_name',
            'category_description' => 'sometimes|string'
        ]);

        $category = Category::create($valid_data);
        return new JsonResponse($category, ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return new JsonResponse($category, ResponseAlias::HTTP_FOUND);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse('category not found', ResponseAlias::HTTP_NOT_FOUND);

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Category $category)
    {
        $valid_data = \request()->validate([
            'category_name' => ['required', 'string', Rule::unique('categories')->ignore($category->id)],
            'category_description' => 'sometimes|string'
        ]);
        $updated_category = $category->update($valid_data);
        return new JsonResponse($updated_category, ResponseAlias::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            Category::destroy($id);
            return new JsonResponse('category deleted', ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse('category not found', ResponseAlias::HTTP_NOT_FOUND);
        }
    }

}
