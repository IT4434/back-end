<?php

namespace App\Services;

use App\Repositories\ProductDetailRepository;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;
    protected $productDetailRepository;

    public function __construct(ProductRepository $productRepository, ProductDetailRepository $productDetailRepository)
    {
        $this->productRepository = $productRepository;
        $this->productDetailRepository = $productDetailRepository;
    }

    public function index()
    {
        $products = $this->productRepository->index();
        $products->load('images');

        return $products;
    }

    /**
     * Get all details of a specified product
     *
     * @param $pro_id
     * @return mixed
     */
    public function getProductDetails($pro_id)
    {
        return $this->productDetailRepository->getAllProductDetails($pro_id);
    }

    public function getProductsByCategory($cat_id)
    {
        return $this->productRepository->getProductsByCategory($cat_id);
    }
}
