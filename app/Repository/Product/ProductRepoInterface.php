<?php

namespace App\Repository\Product;

interface ProductRepoInterface
{
    public static function getNewestProductPosts(): object;

    public static function getProductDetails(string $slug);

    public static function getProductDeveloperDetails(string $slug);

    public static function getAllProducts(int $categoryId, int $subCategoryId, string $order, string $search):
    object;

    public static function getAllDeveloperProducts(int $subCategoryId, string $order, string $search): object;

    public static function getAllDeveloperProductById(int $productId);

    public static function getProductById(int $productId);

    public static function searchProduct(string $search): object;

    public static function getProductGallery(int $productId): object;

    public static function getProductFacilities(int $productId): object;

    public static function getProductCategory(string $slug);

    public static function getProductMarketing(int $productId): object;

    public static function getProductDeveloperUnits(int $productId, int $developerId): object;

    public static function getProductDeveloperUnitDetails(int $productDeveloperUnitId);
}
