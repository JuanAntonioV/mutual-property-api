<?php

namespace App\Models\Products;

use App\Models\Developers\Developer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDeveloperUnit extends Model
{
    use HasFactory;

    protected $table = 'product_developer_units';

    protected $fillable = [
        'product_id',
        'developer_id',
        'name',
        'price',
        'total_unit',
        'floor_plan_image',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }
}
