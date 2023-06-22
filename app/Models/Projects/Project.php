<?php

namespace App\Models\Projects;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'logo',
        'slug',
        'phone_number',
        'whatsapp_number',
        'email',
        'address',
        'map_url',
        'status',
        'description',
        'started_price',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(ProjectDetail::class, 'project_id');
    }

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_project', 'project_id', 'product_id');
    }
}
