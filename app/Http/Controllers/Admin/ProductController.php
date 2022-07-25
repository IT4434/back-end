<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    protected $imageService;

    public function __construct(ProductService $productService, ImageService $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
    }

    /**
     * Get list of products
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = $this->productService->index();

        return ProductResource::collection($products);
    }

    /**
     * Get a specified product
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        $product->load(['images', 'productDetails']);
        $product->productDetails->load('images');

        return new ProductResource($product);
    }

    /**
     * Create new product
     *
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function store(ProductRequest $request): ProductResource
    {
        // Store product
        $product = $this->productService->store($request->except('images'));

        // Store image
        $file = $request->file('images');
        $path = $this->imageService->resizeImage($file);
        $s3_path = $this->imageService->s3UploadImages($path, 'products');
        $image = $this->imageService->storeImagePaths($s3_path, $product->id, 'App\Models\Product');
        $product->load('images');

        return new ProductResource($product);
    }

    public function updateImage(ImageRequest $request)
    {
        $file = $request->file('images');
        $path = $this->imageService->resizeImage($file);
        $s3_path = $this->imageService->s3UploadImages($path, $request->imageable_type);

        return $this->imageService->storeImagePaths($s3_path, $request->imageable_id, $request->imageable_type);
    }

    /**
     * Update product
     *
     * @param Product $product
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function update(Product $product, ProductRequest $request): ProductResource
    {
        $data = $request->except('images');
        $product = $this->productService->update($product->id, $data);

        if ($request->file('images')) {
            // Delete old images
            $images = $product->images()->get();
            $this->imageService->s3DeleteImages($images);

            // Update image
            $file = $request->file('images');
            $path = $this->imageService->resizeImage($file);
            $s3_path = $this->imageService->s3UploadImages($path, 'products');
            $image = $this->imageService->updateImagePath($s3_path, $product->id, 'App\Models\Product');
        }

        $product->load('images');

        return new ProductResource($product);
    }

    /**
     * Delete product
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): \Illuminate\Http\JsonResponse
    {
        $images = $product->images()->get();
        $result = $this->productService->destroy($product->id);

        if ($result) {
            $product->images()->delete();
            $this->imageService->s3DeleteImages($images);

            return response()->json(['success' => __('Successfully deleted')], config('response.HTTP_OK'));
        } else {
            return response()->json(['error' => __('Failed to delete')], config('response.HTTP_BAD_REQUEST'));
        }
    }
}
