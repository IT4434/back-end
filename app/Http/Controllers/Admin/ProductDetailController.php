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
        $s3_path = $this->imageService->s3UploadImages($path, 'product_details');
        $image = $this->imageService->storeImagePaths($s3_path, $productDetail->id, 'App\Models\ProductDetail');
        $productDetail->load('images');

        return new ProductDetailResource($productDetail);
    }

    /**
     * @param ProductDetail $productDetail
     * @return ProductDetailResource
     */
    public function show(ProductDetail $productDetail): ProductDetailResource
    {
        $productDetail->load('images');

        return new ProductDetailResource($productDetail);
    }

    /**
     * @param ProductDetail $productDetail
     * @param ProductDetailRequest $request
     * @return ProductDetailResource
     */
    public function update(ProductDetail $productDetail, ProductDetailRequest $request): ProductDetailResource
    {
        $data = $request->except('images');
        $productDetail = $this->productDetailService->update($productDetail->id, $data);

        if ($request->file('images')) {
            // Delete old images
            $images = $productDetail->images()->get();
            $this->imageService->s3DeleteImages($images);

            // Update image
            $file = $request->file('images');
            $path = $this->imageService->resizeImage($file);
            $s3_path = $this->imageService->s3UploadImages($path, 'product_details');
            $image = $this->imageService->updateImagePath($s3_path, $productDetail->id, 'App\Models\ProductDetail');
        }

        $productDetail->load('images');

        return new ProductDetailResource($productDetail);
    }

    /**
     * @param ProductDetail $productDetail
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductDetail $productDetail): \Illuminate\Http\JsonResponse
    {
        $images = $productDetail->images()->get();
        $result = $this->productDetailService->destroy($productDetail->id);

        if ($result) {
            $productDetail->images()->delete();
            $this->imageService->s3DeleteImages($images);

            return response()->json(['success' => __('Successfully deleted')], config('response.HTTP_OK'));
        } else {
            return response()->json(['error' => __('Failed to delete')], config('response.HTTP_BAD_REQUEST'));
        }
    }
}
