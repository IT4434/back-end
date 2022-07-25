<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get list of categories
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categories = $this->categoryService->index();

        return CategoryResource::collection($categories);
    }

    /**
     * Show specified category
     *
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        $category->load('products');

        return new CategoryResource($category);
    }

    /**
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function store(CategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->store($request->all());

        return new CategoryResource($category);
    }

    /**
     * Update specified category
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, int $id): CategoryResource
    {
        $category = $this->categoryService->update($id, $request->all());

        return new CategoryResource($category);
    }

    /**
     * Delete specified category
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        $result = $this->categoryService->destroy($category->id);

        if ($result) {
            return response()->json(['success' => __('Successfully deleted')], config('response.HTTP_OK'));
        } else {
            return response()->json(['error' => __('Failed to delete')], config('response.HTTP_BAD_REQUEST'));
        }
    }
}
