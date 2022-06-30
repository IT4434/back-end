<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\OrderDetailRepository;
use App\Repositories\ProductDetailRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    protected $productRepository;
    protected $productDetailRepository;
    protected $orderDetailRepository;

    public function __construct(ProductRepository $productRepository,
        ProductDetailRepository $productDetailRepository,
        OrderDetailRepository $orderDetailRepository)
    {
        $this->productRepository = $productRepository;
        $this->productDetailRepository = $productDetailRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    /**
     * Get list of products
     *
     * @return mixed
     */
    public function index()
    {
        $products = $this->productRepository->index();
        $products->load('images', 'productDetails.images');

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

    public function store($data)
    {
        return $this->productRepository->store($data);
    }

    public function update($id, $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->productRepository->delete($id);
    }

    public function searchProduct($data)
    {
        return $this->productRepository->searchProduct($data);
    }

    public function sortProductByRating($field, $type)
    {
        return $this->productRepository->sortProductByRating($field, $type);
    }

    public function makeRating(Request $request, Product $product)
    {
        $user = auth()->user();
        $userOrders = $user->orders;
        $canRate = false;
        $orderRateId = '';
        $productId = $product->id;
        foreach ($userOrders as $userOrder) {
            $orderDetails = $userOrder->orderDetails;

            foreach ($orderDetails as $orderDetail) {
                if (!$orderDetail->is_rated && $orderDetail->productDetail->product->id == $productId) {
                    $canRate = true;
                    $orderRateId = $orderDetail->id;
                    break;
                }
            }

            if ($canRate) break;
        }

        $data = [
            'rating' => $request->rating,
        ];

        if ($canRate) {
            $ratingData = $this->calculateRating($data, $product);
            $this->orderDetailRepository->setIsRated($orderRateId);

            return $this->productRepository->update($product->id, $ratingData);
        } else {
            return false;
        }
    }

    public function calculateRating(array $data, Product $product): array
    {
        $newRatingQuantity = $product->rating_quantity + 1;
        $newRating = ($product->rating * $product->rating_quantity + $data['rating']) / $newRatingQuantity;

        return [
            'rating_quantity' => $newRatingQuantity,
            'rating' => $newRating,
        ];
    }
}
