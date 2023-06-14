<?php

namespace App\Models\Products;

use App\Models\Category\Category;
use App\Models\Developers\Developer;
use App\Models\SubCategory\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'staff_id',
        'categories_id',
        'sub_categories_id',
        'cover_image',
        'title',
        'slug',
        'address',
        'status',
        'price',
        'map_link',
        'is_sold',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function facility(): HasMany
    {
        return $this->hasMany(ProductFacility::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function subCategory(): HasOne
    {
        return $this->hasOne(SubCategory::class);
    }

    public function developer(): BelongsToMany
    {
        return $this->belongsToMany(Developer::class, 'product_developers', 'product_id', 'developer_id');
    }
}
