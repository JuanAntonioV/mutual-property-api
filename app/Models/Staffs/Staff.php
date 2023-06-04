<?php

namespace App\Models\Staffs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'username',
        'password',
        'email',
        'status',
        'is_super',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(StaffDetail::class);
    }
}
