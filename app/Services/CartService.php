<?php

namespace App\Services;

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
}
