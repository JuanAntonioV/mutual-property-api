<?php

namespace App\Repository\Product;

use App\Entities\CategoryEntities;
use App\Entities\ProductEntities;
use App\Traits\RepoTrait;
use Illuminate\Support\Facades\DB;

class ProductRepo implements ProductRepoInterface
{
    use RepoTrait;

    public static function getNewestProductPosts(): object
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('product_details', 'product_details.product_id', 'products.id')
            ->where('categories_id', CategoryEntities::CATEGORY_DIJUAL)
            ->orWhere('categories_id', CategoryEntities::CATEGORY_DIJUAL)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.address',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'product_details.bathroom',
                'product_details.bedroom',
                'product_details.floor',
                'product_details.certificate',
                'products.created_at as posted_at',
            )
            ->orderBy('products.created_at')
            ->limit(8)
            ->get();
    }

    private static function getDbTable(): object
    {
        return DB::table('products');
    }

    public static function getAllProducts($category, $subCategory, $order, $search): object
    {
        $query = self::getDbTable()
            ->join('product_details', 'product_details.product_id', 'products.id')
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.address',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'product_details.bathroom',
                'product_details.bedroom',
                'product_details.floor',
                'product_details.certificate',
                'products.created_at as posted_at',
            );

        if ($search) {
            $query->where('products.title', 'like', '%' . $search . '%')
                ->orWhere('products.address', 'like', '%' . $search . '%')
                ->orWhere('categories.name', 'like', '%' . $search . '%')
                ->orWhere('sub_categories.name', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->join('categories', 'categories.id', 'products.id')
                ->where('categories.slug', $category);
        }

        if ($subCategory) {
            $query->join('sub_categories', 'sub_categories.id', 'products.id')
                ->where('sub_categories.slug', $subCategory);
        }

        if ($order === 'termurah') {
            $query->orderBy('products.price');
        } else if ($order === 'termahal') {
            $query->orderBy('products.price', 'desc');
        }

        return $query->get();
    }
}
