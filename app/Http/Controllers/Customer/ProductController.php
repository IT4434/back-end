<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->index();

        return ProductResource::collection($products);
    }

    /**
     * Get all details of the product
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        $product->load('productDetails');

        return new ProductResource($product);
    }

    /**
     *Get products of a specified category
     *
     * @param Category $category
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductsByCategory(Category $category): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->getProductsByCategory($category->id);
        $products->load('images');

        return ProductResource::collection($products);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function searchProduct(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->searchProduct($request->input('search'));

        return ProductResource::collection($products);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function sortProduct(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $field = $request->input('field');
        $type = $request->input('type');
        $products = $this->productService->sortProductByRating($field, $type);

        return ProductResource::collection($products);
    }
}
