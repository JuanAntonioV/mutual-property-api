<?php

namespace App\Services\Admin\Product;

use Illuminate\Http\Request;

interface AdminProductServiceInterface
{
    public function getAllProducts(): array;

    public function deleteProduct(int $id): array;

    public function getProductDetails(int $id): array;

    public function deleteProductImage(int $id, int $imageId): array;

    public function deleteProductFacility(int $id, int $facilityId): array;

    public function createProduct(Request $request): array;

    public function getAllNewPosts(Request $request): array;

    public function updateProduct(int $id, Request $request): array;

}
