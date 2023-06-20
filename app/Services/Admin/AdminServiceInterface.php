<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;

interface AdminServiceInterface
{
    public function getAllAdmins(): array;

    public function getAdminDetails(int $id): array;

    public function createAdmin(Request $request): array;

    public function updateAdmin(int $id, Request $request): array;

    public function changeAdminPassword(int $id, Request $request): array;

    public function toggleAdminStatus(int $id, Request $request): array;
}
