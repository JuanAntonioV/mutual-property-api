<?php

namespace App\Services\Admin\Auth;

use Illuminate\Http\Request;

interface AdminAuthServiceInterface
{
    public function login(Request $request): array;

    public function logout(Request $request): array;

    public function me(Request $request): array;

    public function forgotPassword(Request $request): array;

    public function resetPassword(Request $request): array;

    public function changePassword(Request $request): array;

    public function getAdminProfile(Request $request): array;

    public function updateAdminProfile(Request $request): array;
}
