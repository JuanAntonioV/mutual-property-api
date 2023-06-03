<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function login(Request $request): array;

    public function register(Request $request): array;

    public function logout(Request $request): array;

    public function me(Request $request): array;

    public function forgotPassword(Request $request): array;

    public function resetPassword(Request $request): array;
}
