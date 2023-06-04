<?php

namespace App\Models\Products;

use App\Models\Developers\Developer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDeveloperDetail extends Model
{
    use HasFactory;

    protected $table = 'product_developer_details';

    protected $fillable = [
        'product_id',
        'developer_id',
        'total_unit',
        'certificate',
        'area',
        'price_list_image',
        'side_plan_image',
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
