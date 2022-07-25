<?php

namespace App\Repositories;

use App\Models\Product;
use Carbon\Carbon;

class ProductRepository extends BaseRepository
{

    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return Product::class;
    }

    /**
     * Get products of a specified category
     *
     * @param $cat_id
     * @return mixed
     */
    public function getProductsByCategory($cat_id)
    {
        return $this->model->where('category_id', $cat_id)->get();
    }

    public function searchProduct($data)
    {
        return $this->model->where('product_name', 'LIKE', '%' . $data . '%')
            ->with('images')->get();
    }

    public function sortProductByRating($field, $type = 'ASC')
    {
        return $this->model->orderBy($field, $type)
            ->with('images')->get();
    }

    public function getTopProductInMonth($month)
    {
        $startOfMonth = Carbon::createFromFormat('m/Y', $month)
            ->firstOfMonth()
            ->format('Y-m-d h:i:s');
        $finishOfMonth = Carbon::createFromFormat('m/Y', $month)
            ->endOfMonth()
            ->format('Y-m-d h:i:s');

        $listProduct = $this->model->selectRaw('products.*, sum(order_details.quantity) as total')
            ->join('product_details', 'product_details.product_id', '=', 'products.id')
            ->join('order_details', 'order_details.product_id', '=', 'product_details.id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->whereDate('orders.created_at', '>=', $startOfMonth)
            ->whereDate('orders.created_at', '<=', $finishOfMonth)
            ->groupBy('products.id')
            ->orderBy('total', 'DESC')
            ->get();

        return $listProduct->load([
            'images',
//            'productDetails.images'
        ]);
    }

    public function getTopProductInWeek($week)
    {
        $startOfWeek = Carbon::createFromFormat('m/d/Y', $week)
            ->firstOfMonth()
            ->format('Y-m-d h:i:s');
        $finishOfWeek = Carbon::createFromFormat('m/d/Y', $week)
            ->endOfMonth()
            ->format('Y-m-d h:i:s');

        $listProduct = $this->model->selectRaw('products.*, sum(order_details.quantity) as total')
            ->join('product_details', 'product_details.product_id', '=', 'products.id')
            ->join('order_details', 'order_details.product_id', '=', 'product_details.id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->whereDate('orders.created_at', '>=', $startOfWeek)
            ->whereDate('orders.created_at', '<=', $finishOfWeek)
            ->groupBy('products.id')
            ->orderBy('total', 'DESC')
            ->get();

        return $listProduct->load([
            'images',
//            'productDetails.images'
        ]);
    }
}
