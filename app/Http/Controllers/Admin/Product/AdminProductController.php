<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Services\Admin\Product\AdminProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    protected AdminProductServiceInterface $adminProductService;

    public function __construct(AdminProductServiceInterface $adminProductService)
    {
        $this->adminProductService = $adminProductService;
    }

    public function getAllProducts(): JsonResponse
    {
        $data = $this->adminProductService->getAllProducts();
        return response()->json($data, $data['code']);
    }

    public function deleteProduct(int $id): JsonResponse
    {
        $data = $this->adminProductService->deleteProduct($id);
        return response()->json($data, $data['code']);
    }

    public function getProductDetails(int $id): JsonResponse
    {
        $data = $this->adminProductService->getProductDetails($id);
        return response()->json($data, $data['code']);
    }

    public function createProduct(Request $request): JsonResponse
    {
        $data = $this->adminProductService->createProduct($request);
        return response()->json($data, $data['code']);
    }

    public function updateProduct(int $id, Request $request): JsonResponse
    {
        $data = $this->adminProductService->updateProduct($id, $request);
        return response()->json($data, $data['code']);
    }
}
