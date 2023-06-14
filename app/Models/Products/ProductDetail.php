<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    use HasFactory;

    protected $table = 'product_details';

    protected $fillable = [
        'product_id',
        'bedroom',
        'bathroom',
        'floor',
        'garage',
        'certificate',
        'soil_area',
        'land_area',
        'building_area',
        'building_size',
        'building_condition',
        'building_direction',
        'electricity_capacity',
        'water_source',
        'floor_plan_image',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
