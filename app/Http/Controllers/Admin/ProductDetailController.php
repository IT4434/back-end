<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductDetailRequest;
use App\Http\Resources\ProductDetailResource;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Services\ProductDetailService;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    protected $productDetailService;

    public function __construct(ProductDetailService $productDetailService)
    {
        $this->productDetailService = $productDetailService;
    }
    /**
     * Get details of product
     *
     * @param Product $product
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Product $product): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $productDetails = $product->load('productDetails');
        $productDetails->load('images');

        return ProductDetailResource::collection($productDetails);
    }

//    public function show(Product $product, ProductDetail $productDetail)
//    {
//
//    }

    /**
     * Store new product detail
     *
     * @param ProductDetailRequest $request
     * @return ProductDetailResource
     */
    public function store(ProductDetailRequest $request): ProductDetailResource
    {
        $data = $request->all();

        return new ProductDetailResource($this->productDetailService->store($data));
    }
}
