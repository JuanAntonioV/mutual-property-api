<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
