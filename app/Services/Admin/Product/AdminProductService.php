<?php

namespace App\Services\Admin\Product;

use App\Helpers\ResponseHelper;
use App\Models\Products\Product;
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

            return ResponseHelper::success($products);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function deleteProduct(int $id): array
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            if (!$product) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
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
            $product = Product::findOrFail($id);

            if (!$product) {
                return ResponseHelper::notFound('Produk tidak ditemukan');
            }

            $product->load('detail', 'images', 'facility', 'category', 'subCategory');

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
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $data['staff_id'] = $staffId;
            Product::with('detail')->create($data);

            DB::commit();
            return ResponseHelper::success(null, 'Produk berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function createProductFacility(Request $request): array
    {
        $validator = $this->productValidator->validateCreateProductFacility($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);


            DB::commit();
            return ResponseHelper::success(null, 'Produk berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
