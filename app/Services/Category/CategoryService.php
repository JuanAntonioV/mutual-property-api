<?php

namespace App\Services\Category;

use App\Helpers\ResponseHelper;
use App\Models\Category\Category;

class CategoryService implements CategoryServiceInterface
{
    public function getAllCategory(): array
    {
        try {
            $categories = Category::where('is_active', 1)
                ->with('subCategories:id,name,slug')
                ->whereHas('subCategories', function ($q) {
                    $q->where('is_active', 1);
                })
                ->orderBy('id', 'asc')
                ->get(['id', 'name', 'slug']);

            if ($categories->isEmpty()) {
                return ResponseHelper::notFound('Kategori tidak ditemukan');
            }

            return ResponseHelper::success($categories);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
