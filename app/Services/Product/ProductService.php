<?php

namespace App\Services\Product;

use App\Entities\CategoryEntities;
use App\Helpers\ResponseHelper;
use App\Repository\Product\ProductRepoInterface;

class ProductService implements ProductServiceInterface
{
    protected ProductRepoInterface $productRepo;

    public function __construct(ProductRepoInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function getNewestProductPosts($request): array
    {
        try {
            $products = $this->productRepo->getNewestProductPosts();

            if ($products->isEmpty()) return ResponseHelper::notFound('Tidak ada produk baru');

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAllProducts($request): array
    {
        try {
            $categoryId = $request->input('category');
            $subCategoryId = $request->input('type');
            $order = $request->input('order');
            $search = $request->input('search');

            $products = $this->productRepo->getAllProducts($categoryId, $subCategoryId, $order, $search);

            if ($products->isEmpty()) return ResponseHelper::notFound('Tidak ada produk');

            foreach ($products as $item) {
                $item->gallery = $this->productRepo->getProductGallery($item->id);
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getDeveloperProducts($request): array
    {
        try {
            $subCategoryId = $request->input('type');
            $order = $request->input('order');
            $search = $request->input('search');

            $products = $this->productRepo->getAllDeveloperProducts($subCategoryId, $order,
                $search);

            if ($products->isEmpty()) return ResponseHelper::notFound('Tidak ada produk');

            foreach ($products as $item) {
                $item->gallery = $this->productRepo->getProductGallery($item->id);
                $item->total_unit_type = $this->productRepo->getProductDeveloperUnits($item->id,
                    $item->developer_id)->count();
                $item->total_units = $this->productRepo->getProductDeveloperUnits($item->id,
                    $item->developer_id)->sum('total_unit');
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function searchProduct($request): array
    {
        try {
            $search = $request->input('search');

            $searchedProduct = $this->productRepo->searchProduct($search);

            if ($searchedProduct->isEmpty()) return ResponseHelper::notFound('Tidak ada produk');

            $products = [];

            foreach ($searchedProduct as $item) {
                if ($item->category_id == CategoryEntities::CATEGORY_DIJUAL || $item->category_id == CategoryEntities::CATEGORY_DISEWA) {
                    $oneProducts = $this->productRepo->getProductById($item->id);

                    $oneProducts->gallery = $this->productRepo->getProductGallery($oneProducts->id);

                    $products[] = $oneProducts;
                } else {
                    $secProducts = $this->productRepo->getAllDeveloperProductById($item->id);

                    $secProducts->gallery = $this->productRepo->getProductGallery($secProducts->id);
                    $secProducts->total_unit_type = $this->productRepo->getProductDeveloperUnits($secProducts->id,
                        $secProducts->developer_id)->count();
                    $secProducts->total_units = $this->productRepo->getProductDeveloperUnits($secProducts->id,
                        $secProducts->developer_id)->sum('total_unit');

                    $products[] = $secProducts;
                }
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getProductDetails($request, $slug): array
    {
        try {
            $typeId = $request->input('type');
            $productCategory = $this->productRepo->getProductCategory($slug);
            $productCategoryId = $productCategory->id;

            if (!$productCategory) return ResponseHelper::notFound('Produk tidak ditemukan');

            if ($productCategoryId == CategoryEntities::CATEGORY_DIJUAL || $productCategoryId ==
                CategoryEntities::CATEGORY_DISEWA) {
                $product = $this->productRepo->getProductDetails($slug);

                $product->gallery = $this->productRepo->getProductGallery($product->id);
                $product->facilities = $this->productRepo->getProductFacilities($product->id);
                $product->marketing = $this->productRepo->getProductMarketing($product->id);
            } else {
                $product = $this->productRepo->getProductDeveloperDetails($slug);

                $product->gallery = $this->productRepo->getProductGallery($product->id);

                if ($typeId) {
                    $product->units = $this->productRepo->getProductDeveloperUnitDetails($typeId);
                } else {
                    $product->units = $this->productRepo->getProductDeveloperUnits($product->id, $product->developer_id);
                }
            }

            return ResponseHelper::success($product);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
