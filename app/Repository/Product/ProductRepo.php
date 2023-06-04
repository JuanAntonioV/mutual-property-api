<?php

namespace App\Repository\Product;

use App\Entities\CategoryEntities;
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
            ->orderBy('created_at')
            ->get();
    }

    private static function getDbTable(): object
    {
        return DB::table('products');
    }
}
