<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use App\Models\SubCategory\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() > 0) {
            return;
        }

        $category = [
            [
                'name' => 'Dijual',
                'slug' => 'dijual',
            ],
            [
                'name' => 'Disewa',
                'slug' => 'disewa',
            ],
            [
                'name' => 'Baru',
                'slug' => 'baru',
            ],
        ];

        foreach ($category as $key => $value) {
            Category::create($value);
        }

        if (SubCategory::count() > 0) {
            return;
        }

        $subCategory = [
            [
                'name' => 'Rumah',
                'slug' => 'rumah',
            ],
            [
                'name' => 'Ruko',
                'slug' => 'ruko',
            ],
            [
                'name' => 'Gudang / Pabrik',
                'slug' => 'gudang-pabrik',
            ],
            [
                'name' => 'Apartemen',
                'slug' => 'apartemen',
            ],
            [
                'name' => 'Komersial',
                'slug' => 'komersial',
            ],
        ];

        foreach ($subCategory as $key => $value) {
            SubCategory::create($value);
        }

        foreach ($category as $catKey => $catValue) {
            foreach ($subCategory as $key => $value) {
                DB::table('categories_sub_categories')->insert([
                    'category_id' => $catKey + 1,
                    'sub_category_id' => $key + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
