<?php

namespace App\Models\Subscriptions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'is_subscribed',
        'subscribed_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
