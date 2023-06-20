<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        $this->adminService = $adminService;
    }

    public function getAllAdmins(): JsonResponse
    {
        $data = $this->adminService->getAllAdmins();
        return response()->json($data, $data['code']);
    }

    public function getAdminDetails(int $id): JsonResponse
    {
        $data = $this->adminService->getAdminDetails($id);
        return response()->json($data, $data['code']);
    }

    public function createAdmin(Request $request): JsonResponse
    {
        $data = $this->adminService->createAdmin($request);
        return response()->json($data, $data['code']);
    }

    public function updateAdmin(Request $request, int $id): JsonResponse
    {
        $data = $this->adminService->updateAdmin($id, $request);
        return response()->json($data, $data['code']);
    }

    public function nonActiveAdmin(Request $request, int $id): JsonResponse
    {
        $data = $this->adminService->toggleAdminStatus($id, $request);
        return response()->json($data, $data['code']);
    }

    public function changeAdminPassword(Request $request, int $id): JsonResponse
    {
        $data = $this->adminService->changeAdminPassword($id, $request);
        return response()->json($data, $data['code']);
    }
}
