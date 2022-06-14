<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
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
     * @param $cat_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getProductsByCategory($cat_id): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->getProductsByCategory($cat_id);
        $products->load('images');

        return ProductResource::collection($products);
    }
}
