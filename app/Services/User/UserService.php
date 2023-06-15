<?php

namespace App\Services\User;

use App\Helpers\ResponseHelper;
use App\Repository\Auth\AuthRepoInterface;
use App\Repository\User\UserRepoInterface;
use App\Services\Auth\AuthService;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\DB;
use libphonenumber\NumberParseException;

class UserService implements UserServiceInterface
{
    protected UserRepoInterface $userRepo;
    protected UserValidator $userValidator;
    protected AuthRepoInterface $authRepo;

    public function __construct(UserRepoInterface $userRepo, UserValidator $userValidator,
                                AuthRepoInterface $authRepo)
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

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);

                if ($phoneNumber != $prevUserData->phone_number) {
                    $isPhoneNumberRegistered = $this->authRepo->isPhoneNumberRegistered($phoneNumber);

                    if ($isPhoneNumberRegistered) return ResponseHelper::error('Nomor telepon sudah terdaftar', null, 400);
                }
            }

            $data = [
                'email' => $email ?? $prevUserData->email,
                'full_name' => $fullName ?? $prevUserData->full_name,
                'phone_number' => $phoneNumber ?? $prevUserData->phone_number,
            ];

            $this->userRepo->updateUserProfile($userId, $data);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah user profile');
        } catch (NumberParseException $e) {
            return ResponseHelper::error("Nomor handphone tidak valid");
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
