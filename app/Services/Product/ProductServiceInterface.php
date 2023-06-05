<?php

namespace App\Services\Product;

use Illuminate\Http\Request;

interface ProductServiceInterface
{
    public function getNewestProductPosts(Request $request): array;

    public function getAllProducts(Request $request): array;

    public function getProductDetails(Request $request, $slug): array;

    public function getDeveloperProducts(Request $request): array;

    public function searchProduct(Request $request): array;
}
