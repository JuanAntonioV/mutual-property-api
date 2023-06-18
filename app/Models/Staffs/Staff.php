<?php

namespace App\Models\Staffs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'staffs';

    protected $fillable = [
        'photo',
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
        'created_at',
        'updated_at',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(StaffDetail::class, 'staff_id');
    }
}
