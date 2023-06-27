<?php

namespace App\Services\Product;

use App\Entities\CategoryEntities;
use App\Entities\ProductEntities;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Products\Product;
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
            $products = Product::with('category', 'subCategory', 'detail')
                ->with(['images' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->where('status', ProductEntities::STATUS_PUBLISH)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            if ($products->isEmpty()) return ResponseHelper::notFound('Tidak ada produk baru');

            foreach ($products as $item) {
                foreach ($item->images as $image) {
                    $image->path = FileHelper::getFileUrl($image->path);
                }
            }

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAllProducts($request): array
    {
        try {
            $categoryId = $request->input('category');
            $subCategoryId = $request->input('sub_category');
            $order = $request->input('order');
            $search = $request->input('search');

            $products = Product::with('category', 'subCategory', 'detail')
                ->with(['images' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->where('status', ProductEntities::STATUS_PUBLISH)
                ->when($categoryId, function ($query, $categoryId) {
                    return $query->where('categories_id', $categoryId);
                })
                ->when($subCategoryId, function ($query, $subCategoryId) {
                    return $query->where('sub_categories_id', $subCategoryId);
                })
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%');
                })
                ->when($order, function ($query, $order) {
                    if ($order == 'terbaru') {
                        return $query->orderBy('created_at', 'desc');
                    } elseif ($order == 'terlama') {
                        return $query->orderBy('created_at', 'asc');
                    } elseif ($order == 'termurah') {
                        return $query->orderBy('price', 'asc');
                    } elseif ($order == 'termahal') {
                        return $query->orderBy('price', 'desc');
                    } else {
                        return $query->orderBy('created_at', 'desc');
                    }
                })
                ->paginate(8);

            if ($products->isEmpty()) return ResponseHelper::notFound('Tidak ada produk');

            foreach ($products as $item) {
                foreach ($item->images as $image) {
                    $image->path = FileHelper::getFileUrl($image->path);
                }
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
            $product = Product::with('category', 'subCategory', 'detail', 'facility')
                ->with(['images' => function ($query) {
                    $query->where('is_active', 1);
                }])
                ->where('slug', $slug)
                ->first();

            if (!$product) return ResponseHelper::notFound('Produk tidak ditemukan');

            $productCategoryId = $product->categories_id;


            if ($productCategoryId === CategoryEntities::CATEGORY_DIJUAL || $productCategoryId ===
                CategoryEntities::CATEGORY_DISEWA) {
                $product->load('staff.detail');

                $product->is_project_unit = false;

                $product->staff->photo = FileHelper::getFileUrl($product->staff->photo);
            } else {
                $product->load('project');

                $product->is_project_unit = true;
            }

            foreach ($product->images as $image) {
                $image->path = FileHelper::getFileUrl($image->path);
            }

            return ResponseHelper::success($product);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
