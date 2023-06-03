<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Services\User\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function getUserProfile(Request $request): JsonResponse
    {
        $data = $this->userService->getUserProfile($request);
        return response()->json($data, $data['code']);
    }

    public function updateUserProfile(Request $request): JsonResponse
    {
        $data = $this->userService->updateUserProfile($request);
        return response()->json($data, $data['code']);
    }
}
