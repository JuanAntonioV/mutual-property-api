<?php

namespace App\Models\Category;

use App\Models\SubCategory\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, 'categories_sub_categories', 'category_id', 'sub_category_id');
    }
}
