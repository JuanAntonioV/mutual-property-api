<?php

namespace App\Services\Admin\Product;

use App\Entities\FolderEntities;
use App\Entities\ProductEntities;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Products\Product;
use App\Models\Projects\Project;
use App\Validators\ProductValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductService implements AdminProductServiceInterface
{

    protected ProductValidator $productValidator;

    public function __construct(ProductValidator $productValidator)
    {
        $this->productValidator = $productValidator;
    }

    public function getAllProducts(): array
    {
        try {
            $products = Product::all();

            if (!$products) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
            }

            $products->load('category', 'subCategory');

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function deleteProduct(int $id): array
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
            }

            $productImage = $product->images()->get();

            foreach ($productImage as $image) {
                FileHelper::deleteFile($image->path);
            }

            $product->delete();

            DB::commit();
            return ResponseHelper::success('Produk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getProductDetails(int $id): array
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
            }

            $product->load('detail', 'images', 'facility', 'category', 'subCategory', 'staff.detail');

            foreach ($product->images as $image) {
                $image->path = FileHelper::getFileUrl($image->path);
            }

            $product->staff->photo = FileHelper::getFileUrl($product->staff->photo);

            return ResponseHelper::success($product);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function createProduct($request): array
    {
        $validator = $this->productValidator->validateCreateProduct($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $staffId = $request->user()->id;
            $type = $request->input('type');
            $title = $request->input('title');
            $categoryId = $request->input('category_id');
            $subCategoryId = $request->input('sub_category_id');
            $address = $request->input('address');
            $isSold = $request->input('is_sold');
            $price = $request->input('price');
            $mapUrl = $request->input('map_url');

            $bedroom = $request->input('bedroom');
            $bathroom = $request->input('bathroom');
            $garage = $request->input('garage');
            $floor = $request->input('floor');
            $certificate = $request->input('certificate');
            $soilArea = $request->input('soil_area');
            $landArea = $request->input('land_area');
            $buildingArea = $request->input('building_area');
            $buildingSize = $request->input('building_size');
            $buildingCondition = $request->input('building_condition');
            $buildingDirection = $request->input('building_direction');
            $electricityCapacity = $request->input('electricity_capacity');
            $waterSource = $request->input('water_source');

            $images = $request->file('images');
            $facilities = $request->input('facilities');

            $productData = [
                'staff_id' => $staffId,
                'categories_id' => $categoryId,
                'sub_categories_id' => $subCategoryId,
                'title' => $title,
                'slug' => Str::slug($title),
                'address' => $address,
                'is_sold' => $isSold,
                'price' => $price,
                'map_url' => $mapUrl,
            ];

            $productDetailData = [
                'bedroom' => $bedroom,
                'bathroom' => $bathroom,
                'floor' => $floor,
                'garage' => $garage,
                'certificate' => $certificate,
                'soil_area' => $soilArea,
                'land_area' => $landArea,
                'building_area' => $buildingArea,
                'building_size' => $buildingSize,
                'building_condition' => $buildingCondition,
                'building_direction' => $buildingDirection,
                'electricity_capacity' => $electricityCapacity,
                'water_source' => $waterSource,
            ];

            $product = Product::create($productData);
            $product->detail()->create($productDetailData);

            foreach ($images as $key => $image) {
                $file = $image[0];
                $productImageFolder = FolderEntities::PRODUCT_FOLDER . $product->id . '/' .
                    FolderEntities::PRODUCT_GALLERY_FOLDER;
                $filePrefix = 'image-' . $key;
                $productImagePath = FileHelper::uploadFile($file, $productImageFolder, $filePrefix);
                $product->images()->create([
                    'path' => $productImagePath,
                    'alt' => 'Gambar ' . $key . ' ' . $title
                ]);
            }

            foreach ($facilities as $facility) {
                $product->facility()->create([
                    'facility' => $facility[0]
                ]);
            }

            if ($type === ProductEntities::PRODUCT_PROJECT_TYPE) {
                $projectId = $request->input('project_id');

                if ($request->hasFile('floor_plan_image')) {
                    $floorPlanImage = $request->file('floor_plan_image');
                    $floorPlanImagePath = FileHelper::uploadFile($floorPlanImage, 'floor_plan_image', 'floor_plan');

                    if (!$floorPlanImagePath) {
                        DB::rollBack();
                        return ResponseHelper::error('Gagal mengupload gambar denah lantai');
                    }
                }

                $project = Project::find($projectId);

                if (!$project) {
                    return ResponseHelper::notFound('Proyek tidak ditemukan');
                }

                $project->product()->attach($product->id);

                $product->detail()->update([
                    'floor_plan_image' => $floorPlanImagePath ?? null
                ]);
            }

            DB::commit();
            return ResponseHelper::success(null, 'Produk berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function updateProduct(int $id, Request $request): array
    {
        $validator = $this->productValidator->validateUpdateProduct($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $staffId = $request->user()->id;
            $type = $request->input('type');
            $title = $request->input('title');
            $categoryId = $request->input('category_id');
            $subCategoryId = $request->input('sub_category_id');
            $address = $request->input('address');
            $isSold = $request->input('is_sold');
            $price = $request->input('price');
            $mapUrl = $request->input('map_url');
            $status = $request->input('status');

            $bedroom = $request->input('bedroom');
            $bathroom = $request->input('bathroom');
            $garage = $request->input('garage');
            $floor = $request->input('floor');
            $certificate = $request->input('certificate');
            $soilArea = $request->input('soil_area');
            $landArea = $request->input('land_area');
            $buildingArea = $request->input('building_area');
            $buildingSize = $request->input('building_size');
            $buildingCondition = $request->input('building_condition');
            $buildingDirection = $request->input('building_direction');
            $electricityCapacity = $request->input('electricity_capacity');
            $waterSource = $request->input('water_source');

            $images = $request->file('images');
            $facilities = $request->input('facilities');

            $product = Product::find($id);

            if (!$product) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
            }

            if ($title == $product->title) {
                $title = $product->title;
            }

            $productData = [
                'staff_id' => $staffId,
                'categories_id' => $categoryId ?? $product->categories_id,
                'sub_categories_id' => $subCategoryId ?? $product->sub_categories_id,
                'title' => $title ?? $product->title,
                'slug' => Str::slug($title) ?? $product->slug,
                'address' => $address ?? $product->address,
                'is_sold' => $isSold ?? $product->is_sold,
                'price' => $price ?? $product->price,
                'map_url' => $mapUrl ?? $product->map_url,
                'status' => $status ?? $product->status,
            ];

            $productDetailData = [
                'bedroom' => $bedroom ?? $product->detail->bedroom,
                'bathroom' => $bathroom ?? $product->detail->bathroom,
                'floor' => $floor ?? $product->detail->floor,
                'garage' => $garage ?? $product->detail->garage,
                'certificate' => $certificate ?? $product->detail->certificate,
                'soil_area' => $soilArea ?? $product->detail->soil_area,
                'land_area' => $landArea ?? $product->detail->land_area,
                'building_area' => $buildingArea ?? $product->detail->building_area,
                'building_size' => $buildingSize ?? $product->detail->building_size,
                'building_condition' => $buildingCondition ?? $product->detail->building_condition,
                'building_direction' => $buildingDirection ?? $product->detail->building_direction,
                'electricity_capacity' => $electricityCapacity ?? $product->detail->electricity_capacity,
                'water_source' => $waterSource ?? $product->detail->water_source,
            ];

            $product->update($productData);
            $product->detail()->update($productDetailData);

            if ($images) {
                // Delete old images
                $oldImages = $product->images;
                foreach ($oldImages as $oldImage) {
                    FileHelper::deleteFile($oldImage->path);
                    $oldImage->delete();
                }

                foreach ($images as $key => $image) {
                    $file = $image[0];
                    $productImageFolder = FolderEntities::PRODUCT_FOLDER . $product->id . '/' .
                        FolderEntities::PRODUCT_GALLERY_FOLDER;
                    $filePrefix = 'image-' . $key;
                    $productImagePath = FileHelper::uploadFile($file, $productImageFolder, $filePrefix);
                    $product->images()->create([
                        'path' => $productImagePath,
                        'alt' => 'Gambar ' . $key . ' ' . $title
                    ]);
                }
            }

            if ($facilities) {
                // Delete old facilities
                $oldFacilities = $product->facility;
                foreach ($oldFacilities as $oldFacility) {
                    $oldFacility->delete();
                }

                foreach ($facilities as $facility) {
                    $product->facility()->create([
                        'facility' => $facility[0]
                    ]);
                }
            }

            $product->save();

            DB::commit();
            return ResponseHelper::success(null, 'Produk berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
