<?php

namespace App\Repositories;

use App\Models\ProductDetail;

class ProductDetailRepository extends BaseRepository
{
    public function setModel(): string
    {
        // TODO: Implement setModel() method.
        return ProductDetail::class;
    }

    /**
     * Get all details of a specified product
     *
     * @param $productId
     * @return mixed
     */
    public function getAllProductDetails($productId)
    {
        return $this->model->where('product_id', $productId)->with('images')->get();
    }
}
