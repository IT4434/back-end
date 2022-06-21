<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $productService;
    protected $userService;

    public function __construct(ProductService $productService, UserService $userService)
    {
        $this->productService = $productService;
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getFavoriteList(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $favoriteList = $this->userService->getFavoriteProduct(auth()->user());

        return ProductResource::collection($favoriteList);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToFavorite(Request $request): \Illuminate\Http\JsonResponse
    {
        $productId = $request->input('product_id');
        $user = auth()->user();

        $result = $this->userService->addFavoriteProduct($user, $productId);

        return response()->json(['success' => 'Successfully added to favorite'], config('response.HTTP_OK'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFavorite(Request $request): \Illuminate\Http\JsonResponse
    {
        $productId = $request->input('product_id');
        $user = auth()->user();

        $result = $this->userService->removeFavoriteProduct($user, $productId);

        return response()->json(['success' => 'Successfully removed product'], config('response.HTTP_OK'));
    }
}
