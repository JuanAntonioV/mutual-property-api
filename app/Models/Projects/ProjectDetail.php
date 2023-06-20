<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $table = 'project_details';
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'project_id',
        'total_unit',
        'certificate',
        'area',
        'price_list_image',
        'side_plan_image',
        'status',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
