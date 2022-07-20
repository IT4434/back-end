<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class StaticsController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function showTopProductInMonth(Request $request)
    {
        return $this->productService->getTopProductInMonth($request->month);
    }

    public function showTopProductInWeek(Request $request)
    {
        return $this->productService->getTopProductInWeek($request->week);
    }
}
