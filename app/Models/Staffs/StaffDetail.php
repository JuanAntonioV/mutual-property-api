<?php

namespace App\Models\Staffs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffDetail extends Model
{
    use HasFactory;

    protected $table = 'staff_details';

    protected $primaryKey = 'staff_id';

    protected $fillable = [
        'user_id',
        'full_name',
        'recruitment_date',
        'position',
        'phone_number',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
