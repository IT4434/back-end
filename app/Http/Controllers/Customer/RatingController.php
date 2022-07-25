<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;


class RatingController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeRating(Request $request, Product $product): \Illuminate\Http\JsonResponse
    {
        $result = $this->productService->makeRating($request, $product);
        if ($result) {
            return response()->json(['success' => 'Successfully'], config('response.HTTP_OK'));
        }

        return response()->json(['error' => 'Failed'], config('response.HTTP_BAD_REQUEST'));
    }
}
