<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function getNewProductPosts(): JsonResponse
    {
        $data = $this->productService->getNewestProductPosts();
        return response()->json($data, $data['code']);
    }

    public function getAllProducts(Request $request): JsonResponse
    {
        $data = $this->productService->getAllProducts($request);
        return response()->json($data, $data['code']);
    }
}
