<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\Admin\Auth\AdminAuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    protected AdminAuthServiceInterface $adminAuthService;

    public function __construct(AdminAuthServiceInterface $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function login(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->login($request);
        return response()->json($data, $data['code']);
    }

    public function logout(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->logout($request);
        return response()->json($data, $data['code']);
    }

    public function me(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->me($request);
        return response()->json($data, $data['code']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->forgotPassword($request);
        return response()->json($data, $data['code']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->resetPassword($request);
        return response()->json($data, $data['code']);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->changePassword($request);
        return response()->json($data, $data['code']);
    }

    public function getAdminProfile(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->getAdminProfile($request);
        return response()->json($data, $data['code']);
    }

    public function updateAdminProfile(Request $request): JsonResponse
    {
        $data = $this->adminAuthService->updateAdminProfile($request);
        return response()->json($data, $data['code']);
    }

}
