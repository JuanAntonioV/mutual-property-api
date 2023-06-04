<?php

namespace App\Services\Product;

use App\Helpers\ResponseHelper;
use App\Repository\Product\ProductRepoInterface;

class ProductService implements ProductServiceInterface
{
    protected ProductRepoInterface $productRepo;

    public function __construct(ProductRepoInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getNewestProductPosts(): array
    {
        try {
            $products = $this->productRepo->getNewestProductPosts();

            if (!$products) {
                return ResponseHelper::notFound('Tidak ada produk baru');
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAllProducts($request): array
    {
        try {
            $category = $request->input('category');
            $subCategory = $request->input('type');
            $order = $request->input('order');
            $search = $request->input('search');

            $products = $this->productRepo->getAllProducts($category, $subCategory, $order, $search);

            if (!$products) {
                return ResponseHelper::notFound('Tidak ada produk');
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
