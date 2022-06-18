<?php

namespace App\Services;

use App\Repositories\ProductDetailRepository;

class ProductDetailService
{
    protected $productDetailRepository;

    public function __construct(ProductDetailRepository $productDetailRepository)
    {
        $this->productDetailRepository = $productDetailRepository;
    }

    public function store(array $data)
    {
        return $this->productDetailRepository->store($data);
    }

    public function update(int $id, array $data)
    {
        return $this->productDetailRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->productDetailRepository->delete($id);
    }
}
