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
        $orders->load(['orderDetails.productDetail.images', 'orderDetails.productDetail.product.images']);

        return $orders;
    }

    /**
     * Admin get list of orders
     *
     * @return mixed
     */
    public function getAllListOrders()
    {
        return $this->model->with([
            'user',
            'orderDetails.productDetail.images',
        ])->latest()->get();
    }

    /**
     * @param Order $order
     * @param string $status
     * @return Order
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update([
            'order_status' => $status,
        ]);
        $order->load(['user', 'orderDetails.productDetail.images']);

        return $order;
    }
}
