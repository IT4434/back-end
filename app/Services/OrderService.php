<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderService
{
    protected $orderRepository;
    protected $orderDetailRepository;
    protected $productDetailService;
    const ORDER_STATUS_PENDING = 'Pending';

    public function __construct(OrderRepository $orderRepository,
        OrderDetailRepository $orderDetailRepository,
        ProductDetailService $productDetailService)
    {
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->productDetailService = $productDetailService;
    }

    public function getUserOrders(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $this->orderRepository->getUserOrders($user);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createNewOrder(Request $request)
    {
        // Create new order
        $data = $request->except('order_details');
        $data['user_id'] = auth()->user()->id;
        $data['order_status'] = self::ORDER_STATUS_PENDING;

        $orderDetails = $request->input('order_details');
        $totalPrice = 0;
        $details = [];
        foreach ($orderDetails as $orderDetail) {
            $product = $this->productDetailService->show($orderDetail['product_id']);

            $productGeneral = $product->product;
            $productGeneral->sold_quantity = $productGeneral->sold_quantity + $orderDetail['quantity'];
            $productGeneral->save();

            $totalPrice += $product->price;
            $details[] = [
                'price' => $product->price,
                'product_id' => $product->id,
                'quantity' => $orderDetail['quantity'],
            ];
        }
        $data['total_price'] = $totalPrice;
        $newOrder = $this->orderRepository->store($data);

        // Create new order details
        foreach ($details as $key => $val) {
            $details[$key]['order_id'] = $newOrder->id;
        }
        $newOrderDetails = $this->orderDetailRepository->createNewOrderDetails($details);

        $newOrder->load('orderDetails.productDetail.images');

        return $newOrder;
    }

    public function deleteOrder(Order $order)
    {
        $orderDetails = $order->orderDetails;

        // Delete order details
        foreach ($orderDetails as $orderDetail) {
            $this->orderDetailRepository->delete($orderDetail->id);
        }

        return $this->orderRepository->delete($order->id);
    }

    /**
     * Admin get list of orders
     *
     * @return mixed
     */
    public function getAllListOrders()
    {
        return $this->orderRepository->getAllListOrders();
    }

    /**
     * @param Order $order
     * @param string $status
     * @return Order
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        return $this->orderRepository->updateOrderStatus($order, $status);
    }

    /**
     * @param Order $order
     * @return false|mixed
     */
    public function delete(Order $order)
    {
        return $this->orderRepository->delete($order->id);
    }

    public function confirmComplete(Order $order)
    {
        return $this->orderRepository->update($order->id, ['order_status' => 'Completed']);
    }
}
