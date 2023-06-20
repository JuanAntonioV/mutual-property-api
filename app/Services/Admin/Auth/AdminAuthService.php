<?php

namespace App\Services\Admin\Auth;

use App\Entities\FolderEntities;
use App\Entities\StaffEntitites;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Jobs\ProcessForgotPassword;
use App\Models\Staffs\Staff;
use App\Repository\Admin\Auth\AdminAuthRepoInterface;
use App\Repository\PasswordReset\PasswordResetRepoInterface;
use App\Services\Auth\AuthService;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAuthService implements AdminAuthServiceInterface
{
    protected AdminAuthRepoInterface $adminAuthRepo;
    protected AuthValidator $authValidator;
    protected PasswordResetRepoInterface $passwordResetRepo;

    public function __construct(AdminAuthRepoInterface $adminAuthRepo, AuthValidator $authValidator, PasswordResetRepoInterface $passwordResetRepo)
    {
        $this->adminAuthRepo = $adminAuthRepo;
        $this->authValidator = $authValidator;
        $this->passwordResetRepo = $passwordResetRepo;
    }

    public function login(Request $request): array
    {
        $validator = $this->authValidator->validateAdminLogin($request);

        if ($validator) return $validator;

        try {
            $username = $request->input('username');
            $password = $request->input('password');

            $staff = $this->adminAuthRepo->getStaffCredentialByUsername($username);

            if (!$staff) return ResponseHelper::error('Username tidak ditemukan', null, 404);

            if ($staff->status == StaffEntitites::STATUS_NOT_ACTIVE) return ResponseHelper::error('Akun anda dinonaktifkan', null,
                400);

            if (!Hash::check($password, $staff->password)) return ResponseHelper::error('Username atau password salah', null, 400);

            $token = $staff->createToken('adminToken')->plainTextToken;

            $data = [
                'token' => $token,
                'staff' => $staff
            ];

            return ResponseHelper::success($data, 'Login berhasil');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function logout(Request $request): array
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return ResponseHelper::success(null, 'Logout berhasil');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function me(Request $request): array
    {
        try {
            $staffId = $request->user()->id;
            $staff = $this->adminAuthRepo->getStaffProfileById($staffId);

            if (!$staff) return ResponseHelper::error('Staff tidak ditemukan', null, 404);

            $staff->photo = FileHelper::getFileUrl($staff->photo);

            return ResponseHelper::success($staff, 'Berhasil mengambil data staff');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function forgotPassword(Request $request): array
    {
        $validator = $this->authValidator->validateForgotPassword($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $email = $request->input('email');

            $staff = $this->adminAuthRepo->getStaffCredentialByEmail($email);

            if (!$staff) return ResponseHelper::error('Email tidak ditemukan', null, 404);

            $staffEmail = $staff->email;
            $token = self::createNewResetPasswordToken();
            $fullName = $staff->detail->full_name;

            $this->passwordResetRepo->insertOrUpdateToken($staffEmail, $token);

            dispatch(new ProcessForgotPassword($token, $staffEmail, $fullName));

            $data = [
                'email' => $staffEmail,
//                'token' => $token
            ];

            DB::commit();
            return ResponseHelper::success($data, 'Berhasil mengirim email reset password');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    private function createNewResetPasswordToken(): string
    {
        $token = Str::random(60);
        return hash('sha256', $token);
    }

    public function resetPassword(Request $request): array
    {
        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $token = $request->input('token');

            $staff = $this->adminAuthRepo->getStaffCredentialByUsername($email);
            $staffId = $staff->id;

            if (!$staffId) return ResponseHelper::error('Email tidak terdaftar', null, 401);

            $tokenStatus = $this->passwordResetRepo->checkToken($email, $token);

            if (!$tokenStatus) return ResponseHelper::error('Token tidak valid', null, 401);

            $rememberToken = Staff::where('id', $staffId)->first()->remember_token;

            if ($rememberToken && $rememberToken === $token) {
                return ResponseHelper::error('Token tidak valid', null, 401);
            }

            $passwordEncrypted = Hash::make($password);

            Staff::where('id', $staffId)->update([
                'password' => $passwordEncrypted,
                'remember_token' => $token
            ]);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah password');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function changePassword(Request $request): array
    {
        $validator = $this->authValidator->validateChangePassword($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $oldPassword = $request->input('old_password');
            $newPassword = $request->input('new_password');

            $userId = $request->user()->id;
            $username = $request->user()->username;

            $staff = $this->adminAuthRepo->getStaffCredentialByUsername($username);

            if (!$staff) return ResponseHelper::notFound('User tidak ditemukan');

            if (!Hash::check($oldPassword, $staff->password)) return ResponseHelper::error('Password lama tidak sesuai', null, 401);

            if ($oldPassword === $newPassword) return ResponseHelper::error('Password baru tidak boleh sama dengan password lama', null, 401);

            Staff::where('id', $userId)->update([
                'password' => Hash::make($newPassword)
            ]);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah password');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAdminProfile(Request $request): array
    {
        try {
            $staffId = $request->user()->id;
            $staff = $this->adminAuthRepo->getStaffProfileById($staffId);

            if (!$staff) return ResponseHelper::error('Staff tidak ditemukan', null, 404);

            $staff->photo = FileHelper::getFileUrl($staff->photo);

            return ResponseHelper::success($staff, 'Berhasil mengambil data staff');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function updateAdminProfile(Request $request): array
    {
        $validator = $this->authValidator->validateUpdateAdminProfile($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $staffId = $request->user()->id;
            $staff = $this->adminAuthRepo->getStaffProfileById($staffId);

            if (!$staff) return ResponseHelper::error('Staff tidak ditemukan', null, 404);

            $fullName = $request->input('full_name');
            $recruitmentDate = $request->input('recruitment_date');
            $position = $request->input('position');
            $phoneNumber = $request->input('phone_number');
            $email = $request->input('email');
            $status = $request->input('status');
            $isSuper = $request->input('is_super');

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);
            }

            $photoName = null;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $folderPath = FolderEntities::STAFF_FOLDER . $staffId;
                $photoName = FileHelper::uploadFile($photo, $folderPath, 'photo');
            }

            $staffData = [
                'email' => $email ?? $staff->email,
                'photo' => $photoName ?? $staff->photo,
                'status' => $status ?? $staff->status,
                'is_super' => $isSuper ?? $staff->is_super,
            ];

            $staffDetailData = [
                'full_name' => $fullName ?? $staff->detail->full_name,
                'recruitment_date' => $recruitmentDate ?? $staff->detail->recruitment_date,
                'position' => $position ?? $staff->detail->position,
                'phone_number' => $phoneNumber ?? $staff->detail->phone_number,
            ];

            $staff->email = $staffData['email'];
            $staff->photo = $staffData['photo'];
            $staff->status = $staffData['status'];
            $staff->is_super = $staffData['is_super'];
            $staff->save();

            $staff->detail->full_name = $staffDetailData['full_name'];
            $staff->detail->recruitment_date = $staffDetailData['recruitment_date'];
            $staff->detail->position = $staffDetailData['position'];
            $staff->detail->phone_number = $staffDetailData['phone_number'];
            $staff->detail->save();

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil mengubah data profil');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
