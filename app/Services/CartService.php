<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\CartRepository;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getUserCarts($userId)
    {
        return $this->cartRepository->getUserCarts($userId);
    }

    public function addToCart($data)
    {
        return $this->cartRepository->store($data);
    }

    public function removeCart($cartId)
    {
        return $this->cartRepository->delete($cartId);
    }

    public function updateCart(Cart $cart, array $data)
    {
        $cart = $this->cartRepository->update($cart->id, $data);
        $cart->load(['productDetail.images',
            'productDetail.product.productDetails',
            'productDetail.product.images',
        ]);

        return $cart;
    }

    public function clearCart()
    {
        $userId = auth()->user()->id;

        return $this->cartRepository->deleteAllCustomerCarts($userId);
    }
}
