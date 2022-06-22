<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

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
        $orderList = $this->orderService->getUserOrders(auth()->user());

        return OrderResource::collection($orderList);
    }

    /**
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order): OrderResource
    {
        $order->load('orderDetails.productDetail.images');

        return new OrderResource($order);
    }

    /**
     * @param OrderRequest $request
     * @return OrderResource
     */
    public function store(OrderRequest $request): OrderResource
    {
        $order = $this->orderService->createNewOrder($request);

        return new OrderResource($order);
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order): \Illuminate\Http\JsonResponse
    {
        if ($order->order_status != 'Pending') {
            return response()->json(['error' => 'Can not delete this order'], config('response.HTTP_BAD_REQUEST'));
        }

        $result = $this->orderService->deleteOrder($order);
        if ($result) {
            return response()->json(['success' => 'Successfully deleted order'], config('response.HTTP_OK'));
        } else{
            return response()->json(['error' => 'Failed to delete order'], config('response.HTTP_BAD_REQUEST'));
        }
    }
}
