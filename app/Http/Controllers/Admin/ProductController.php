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
        $product = $this->productService->store($request->except('files'));

        return new ProductResource($product);
    }

    public function updateImage(ImageRequest $request)
    {
        $file = $request->file('images');
        $image_size_paths = $this->imageService->resizeImage($file);
        $s3_paths = $this->imageService->s3UploadImages($image_size_paths);
        File::delete(array_column($image_size_paths, 'local_path'));

    }
}