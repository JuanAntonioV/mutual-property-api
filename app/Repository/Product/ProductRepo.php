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
                'products.cover_image',
                'products.is_sold',
                'products.address',
                'products.status',
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

    public static function getAllProducts($categoryId, $subCategoryId, $order, $search): object
    {
        $query = self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_details', 'product_details.product_id', 'products.id')
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.cover_image',
                'products.is_sold',
                'products.address',
                'products.status',
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

        if ($categoryId) {
            $query->where('categories.id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_categories.id', $subCategoryId);
        }

        if ($order === 'termurah') {
            $query->orderBy('products.price');
        } else if ($order === 'termahal') {
            $query->orderBy('products.price', 'desc');
        }

        return $query->get();
    }

    public static function getProductById($productId)
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_details', 'product_details.product_id', 'products.id')
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.cover_image',
                'products.is_sold',
                'products.address',
                'products.status',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'product_details.bathroom',
                'product_details.bedroom',
                'product_details.floor',
                'product_details.certificate',
                'products.created_at as posted_at',
            )->first();
    }

    public static function searchProduct($search): object
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->leftJoin('product_details', 'product_details.product_id', 'products.id')
            ->leftJoin('product_developers', 'product_developers.product_id', 'products.id')
            ->leftJoin('developers', 'developers.id', 'product_developers.developer_id')
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->where('products.title', 'like', '%' . $search . '%')
            ->orWhere('products.address', 'like', '%' . $search . '%')
            ->orWhere('categories.name', 'like', '%' . $search . '%')
            ->orWhere('sub_categories.name', 'like', '%' . $search . '%')
            ->orWhere('developers.name', 'like', '%' . $search . '%')
            ->get();
    }

    public static function getAllDeveloperProductById($productId)
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_developers', 'product_developers.product_id', 'products.id')
            ->join('developers', 'developers.id', 'product_developers.developer_id')
            ->where('categories.id', CategoryEntities::CATEGORY_BARU)
            ->where('products.id', $productId)
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.cover_image',
                'products.address',
                'products.status',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'developers.id as developer_id',
                'developers.name as developer_name',
                'developers.status as developer_status',
                'products.created_at as posted_at',
            )->first();
    }

    public static function getAllDeveloperProducts($subCategoryId, $order, $search): object
    {
        $query = self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_developers', 'product_developers.product_id', 'products.id')
            ->join('developers', 'developers.id', 'product_developers.developer_id')
            ->where('categories.id', CategoryEntities::CATEGORY_BARU)
            ->where('sub_categories.id', $subCategoryId)
            ->where('products.status', ProductEntities::IS_ACTIVE)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.cover_image',
                'products.address',
                'products.status',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'developers.id as developer_id',
                'developers.name as developer_name',
                'developers.status as developer_status',
                'products.created_at as posted_at',
            );

        if ($search) {
            $query->where('products.title', 'like', '%' . $search . '%')
                ->orWhere('products.address', 'like', '%' . $search . '%')
                ->orWhere('categories.name', 'like', '%' . $search . '%')
                ->orWhere('sub_categories.name', 'like', '%' . $search . '%');
        }

        if ($subCategoryId) {
            $query->where('sub_categories.id', $subCategoryId);
        }

        if ($order === 'termurah') {
            $query->orderBy('products.price');
        } else if ($order === 'termahal') {
            $query->orderBy('products.price', 'desc');
        }

        return $query->get();
    }

    public static function getProductDetails($slug)
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_details', 'product_details.product_id', 'products.id')
            ->where('products.slug', $slug)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.address',
                'products.cover_image',
                'products.is_sold',
                'products.status',
                'products.map_link',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.id as sub_category_id',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'product_details.bathroom',
                'product_details.bedroom',
                'product_details.floor',
                'product_details.garage',
                'product_details.certificate',
                'product_details.soil_area',
                'product_details.land_area',
                'product_details.building_area',
                'product_details.building_size',
                'product_details.building_condition',
                'product_details.building_direction',
                'product_details.electricity_capacity',
                'product_details.water_source',
                'products.created_at as posted_at',
            )->first();
    }

    public static function getProductDeveloperDetails($slug)
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->join('sub_categories', 'sub_categories.id', 'products.id')
            ->join('product_developer_details', 'product_developer_details.product_id', 'products.id')
            ->join('developers', 'developers.id', 'product_developer_details.developer_id')
            ->where('products.slug', $slug)
            ->select(
                'products.id',
                'products.title',
                'products.slug',
                'products.address',
                'products.cover_image',
                'products.is_sold',
                'products.status',
                'products.map_link',
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'sub_categories.id as sub_category_id',
                'sub_categories.name as sub_category_name',
                'sub_categories.slug as sub_category_slug',
                'product_developer_details.total_unit as detail_total_unit',
                'product_developer_details.certificate as detail_certificate',
                'product_developer_details.area as detail_area',
                'product_developer_details.brochure as brochure_file',
                'product_developer_details.price_list_image as price_list_image',
                'product_developer_details.side_plan_image as side_plan_image',
                'developers.name as developer_name',
                'developers.email as developer_email',
                'developers.logo as developer_logo',
                'developers.phone_number as developer_phone_number',
                'developers.whatsapp_number as developer_whatsapp_number',
            )->first();
    }

    public static function getProductDeveloperUnits($productId, $developerId): object
    {
        return DB::table('product_developer_units')
            ->where('product_id', $productId)
            ->where('developer_id', $developerId)
            ->select(
                'product_developer_units.name as unit_name',
                'product_developer_units.price as unit_price',
                'product_developer_units.total_unit as unit_total',
                'product_developer_units.floor_plan_image as unit_floor_plan_image',
                'product_developer_units.status as unit_status',
            )->get();
    }

    public static function getProductDeveloperUnitDetails($productDeveloperUnitId)
    {
        return DB::table('product_developer_units')
            ->join('product_developer_unit_details', 'product_developer_unit_details.product_developer_unit_id', 'product_developer_units.id')
            ->where('id', $productDeveloperUnitId)
            ->select(
                'product_developer_units.name as unit_name',
                'product_developer_units.price as unit_price',
                'product_developer_units.total_unit as unit_total',
                'product_developer_units.floor_plan_image as unit_floor_plan_image',
                'product_developer_units.status as unit_status',
                'product_developer_unit_details.bedroom as total_bedroom',
                'product_developer_unit_details.bathroom as total_bathroom',
                'product_developer_unit_details.floor as total_floor',
                'product_developer_unit_details.garage as total_garage',
                'product_developer_unit_details.certificate as unit_certificate',
                'product_developer_unit_details.soil_area as unit_soil_area',
                'product_developer_unit_details.land_area as unit_land_area',
                'product_developer_unit_details.building_area as unit_building_area',
                'product_developer_unit_details.building_size as unit_building_size',
                'product_developer_unit_details.building_condition as unit_building_condition',
                'product_developer_unit_details.building_direction as unit_building_direction',
                'product_developer_unit_details.electricity_capacity as unit_electricity_capacity',
                'product_developer_unit_details.water_source as unit_water_source',
            )->first();
    }

    public static function getProductCategory($slug)
    {
        return self::getDbTable()
            ->join('categories', 'categories.id', 'products.id')
            ->where('products.slug', $slug)
            ->select(
                'categories.id',
                'categories.name',
                'categories.slug',
            )->first();
    }

    public static function getProductMarketing($productId): object
    {
        return self::getDbTable()
            ->join('staffs', 'staffs.id', 'products.staff_id')
            ->join('staff_details', 'staff_details.staff_id', 'staffs.id')
            ->where('products.id', $productId)
            ->select(
                'staffs.id',
                'staffs.photo',
                'staff_details.full_name',
                'staff_details.phone_number',
                'staffs.email',
                'staffs.status',
            )->first();
    }

    public static function getProductGallery($productId): object
    {
        return DB::table('product_images')
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->select(
                'id',
                'path',
                'alt',
                'is_active',
            )
            ->get();
    }

    public static function getProductFacilities($productId): object
    {
        return DB::table('product_facilities')
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->select(
                'id',
                'facility',
                'icon',
                'is_active',
            )
            ->get();
    }
}
