<?php

namespace App\Services\Admin\Product;

use Illuminate\Http\Request;

interface AdminProductServiceInterface
{
    public function getAllProducts(): array;

    public function deleteProduct(int $id): array;

    public function getProductDetails(int $id): array;

    public function createProduct(Request $request): array;

    public function createProductFacility(Request $request): array;
}
