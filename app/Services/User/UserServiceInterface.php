<?php

namespace App\Services\User;

use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function updateUserProfile(Request $request): array;

    public function getUserProfile(Request $request): array;
}
