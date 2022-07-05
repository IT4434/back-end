<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $carts = $this->cartService->getUserCarts(auth()->user()->id);

        return CartResource::collection($carts);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'user_id' => auth()->user()->id,
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
        ];

        $cart = $this->cartService->addToCart($data);

        if ($cart) {
            return response()->json(['success' => 'Successfully added to cart'], 200);
        } else {
            return response()->json(['error' => 'Failed to add'], 400);
        }
    }

    /**
     * @param Cart $cart
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeCart(Cart $cart): \Illuminate\Http\JsonResponse
    {
        $result = $this->cartService->removeCart($cart->id);

        if ($result) {
            return response()->json(['success' => 'Successfully removed cart'], 200);
        } else {
            return response()->json(['error' => 'Failed to remove'], 400);
        }
    }
}
