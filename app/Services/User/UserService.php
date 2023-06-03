<?php

namespace App\Services\User;

use App\Helpers\ResponseHelper;
use App\Repository\User\UserRepoInterface;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{
    protected UserRepoInterface $userRepo;
    protected UserValidator $userValidator;

    public function __construct(UserRepoInterface $userRepo, UserValidator $userValidator)
    {
        $this->userRepo = $userRepo;
        $this->userValidator = $userValidator;
    }

    public function getUserProfile($request): array
    {
        try {
            $userId = $request->user()->id;
            $user = $this->userRepo->getUserProfileById($userId);

            if (!$user && !$userId) return ResponseHelper::error('User tidak ditemukan', null, 404);

            return ResponseHelper::success($user, 'Berhasil mendapatkan data profile');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function updateUserProfile($request): array
    {
        $validator = $this->userValidator->validateUpdateUserProfile($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $userId = $request->user()->id;
            $email = $request->input('email');
            $fullName = $request->input('full_name');
            $phoneNumber = $request->input('phone_number');

            $user = $this->userRepo->updateUserProfile($userId, $email, $fullName, $phoneNumber);

            if (!$user) return ResponseHelper::error('Gagal merubah user profile', null, 500);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah user profile');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
