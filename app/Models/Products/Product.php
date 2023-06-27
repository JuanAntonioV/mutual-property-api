<?php

namespace App\Models\Products;

use App\Models\Category\Category;
use App\Models\Projects\Project;
use App\Models\Staffs\Staff;
use App\Models\Staffs\StaffDetail;
use App\Models\SubCategory\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'title',
        'slug',
        'address',
        'status',
        'price',
        'map_url',
        'is_sold',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(ProductDetail::class, 'product_id', 'id');
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
        return $this->hasOne(Category::class, 'id', 'categories_id');
    }

    public function subCategory(): HasOne
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_categories_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    public function project(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'product_project', 'product_id', 'project_id');
    }

    public function staffDetails(): BelongsTo
    {
        return $this->belongsTo(StaffDetail::class, 'staff_id', 'staff_id');
    }
}
