<?php

namespace App\Repository\Product;

interface ProductRepoInterface
{
    public static function getNewestProductPosts(): object;

    public static function getAllProducts(string $category, string $subCategory, string $order, string $search):
    object;
}
