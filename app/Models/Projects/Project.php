<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(ProjectDetail::class);
    }
}
