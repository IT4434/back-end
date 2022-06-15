<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductDetailRequest;
use App\Http\Resources\ProductDetailResource;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Services\ImageService;
use App\Services\ProductDetailService;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    protected $productDetailService;
    protected $imageService;

    public function __construct(ProductDetailService $productDetailService, ImageService $imageService)
    {
        $this->productDetailService = $productDetailService;
        $this->imageService = $imageService;
    }
    /**
     * Get details of product
     *
     * @param Product $product
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Product $product): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $productDetails = $product->productDetails;
        $productDetails->load('images');

        return ProductDetailResource::collection($productDetails);
    }

    /**
     * Store new product detail
     *
     * @param ProductDetailRequest $request
     * @return ProductDetailResource
     */
    public function store(ProductDetailRequest $request): ProductDetailResource
    {
        // Store product detail
        $data = $request->except('images');
        $productDetail = $this->productDetailService->store($data);

        // Store image
        $file = $request->file('images');
        $path = $this->imageService->resizeImage($file);
        $s3_path = $this->imageService->s3UploadImages($path, $request->imageable_type);
        $image = $this->imageService->storeImagePaths($s3_path, $request->imageable_id, $request->imageable_type);
        $productDetail->load('images');

        return new ProductDetailResource($productDetail);
    }
}
