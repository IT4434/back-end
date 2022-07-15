<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $orders = $this->orderService->getAllListOrders();

        return OrderResource::collection($orders);
    }

    /**
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order): OrderResource
    {
        $order->load([
            'user',
            'orderDetails.productDetail.images',
            'orderDetails.productDetail.product.images'
        ]);

        return new OrderResource($order);
    }

    /**
     * @param OrderRequest $request
     * @param Order $order
     * @return OrderResource
     */
    public function updateOrderStatus(OrderRequest $request, Order $order): OrderResource
    {
        $status = $request->input('order_status');
        $order = $this->orderService->updateOrderStatus($order, $status);

        return new OrderResource($order);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order): \Illuminate\Http\JsonResponse
    {
        if ($order->order_status != config('const.ORDER_STATUS.COMPLETED')) {
            return response()->json(['error' => 'Can not delete this order'], config('response.HTTP_BAD_REQUEST'));
        }

        $result = $this->orderService->delete($order);
        if ($result) {
            return response()->json(['error' => 'Successfully deleted'], config('response.HTTP_OK'));
        } else {
            return response()->json(['error' => 'Can not delete this order'], config('response.HTTP_BAD_REQUEST'));
        }
    }
}
