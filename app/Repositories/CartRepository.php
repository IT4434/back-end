<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository extends BaseRepository
{

    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return Cart::class;
    }

    public function getUserCarts($userId)
    {
        return $this->model->where('user_id', $userId)
            ->with(['productDetail.images',
                'productDetail.product.images',
                'productDetail.product.productDetails',
            ])->latest()->get();
    }

    public function deleteAllCustomerCarts($userId)
    {
        return $this->model->where('user_id', $userId)
            ->delete();
    }
}
