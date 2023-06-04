<?php

namespace App\Services\User;

use App\Helpers\ResponseHelper;
use App\Repository\Auth\AuthRepoInterface;
use App\Repository\User\UserRepoInterface;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\DB;

class UserService implements UserServiceInterface
{
    protected UserRepoInterface $userRepo;
    protected UserValidator $userValidator;
    protected AuthRepoInterface $authRepo;

    public function __construct(UserRepoInterface $userRepo, UserValidator $userValidator, AuthRepoInterface $authRepo)
    {
        $this->userRepo = $userRepo;
        $this->userValidator = $userValidator;
        $this->authRepo = $authRepo;
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

            $prevUserData = $this->userRepo->getUserProfileById($userId);

            $phoneBeginString = substr($phoneNumber, 0, 2);
            if ($phoneBeginString != '08' && $phoneBeginString != '62') return
                ResponseHelper::error('Nomor telepon tidak valid',
                    null, 400);

            if ($phoneBeginString == '62') {
                $phoneNumber = '08' . substr($phoneNumber, 2);
            }
            
            if ($phoneNumber != $prevUserData->phone_number) {
                $isPhoneNumberRegistered = $this->authRepo->isPhoneNumberRegistered($phoneNumber);

                if ($isPhoneNumberRegistered) return ResponseHelper::error('Nomor telepon sudah terdaftar', null, 400);
            }

            $data = [
                'email' => $email ?? $prevUserData->email,
                'full_name' => $fullName ?? $prevUserData->full_name,
                'phone_number' => $phoneNumber ?? $prevUserData->phone_number,
            ];

            $user = $this->userRepo->updateUserProfile($userId, $data);

            if (!$user) return ResponseHelper::error('Gagal merubah user profile', null, 500);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah user profile');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
