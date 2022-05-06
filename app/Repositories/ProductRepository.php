<?php

namespace App\Repositories;

use App\Models\Product;

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
}
