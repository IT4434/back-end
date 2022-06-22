<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;

class OrderRepository extends BaseRepository
{

    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return Order::class;
    }

    public function getUserOrders(User $user): \Illuminate\Database\Eloquent\Collection
    {
        $orders = $user->orders()->latest()->get();
        $orders->load('orderDetails.productDetail.images');

        return $orders;
    }
}
