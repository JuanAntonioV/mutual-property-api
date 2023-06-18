<?php

namespace App\Models\Contacts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'message',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
