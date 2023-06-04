<?php

namespace App\Services\Product;

use Illuminate\Http\Request;

interface ProductServiceInterface
{
    public function getNewestProductPosts(): array;

    public function getAllProducts(Request $request): array;
}
