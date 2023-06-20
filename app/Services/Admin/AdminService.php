<?php

namespace App\Services\Admin;

use App\Entities\FolderEntities;
use App\Helpers\FileHelper;
use App\Helpers\ResponseHelper;
use App\Models\Staffs\Staff;
use App\Services\Auth\AuthService;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminService implements AdminServiceInterface
{

    protected AuthValidator $authValidator;

    public function __construct(AuthValidator $authValidator)
    {
        $this->authValidator = $authValidator;
    }

    public function getAllAdmins(): array
    {
        try {
            $admins = Staff::all()->load('detail');

            if (!$admins) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            foreach ($admins as $admin) {
                $admin->photo = FileHelper::getFileUrl($admin->photo);
            }

            return ResponseHelper::success($admins);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAdminDetails($id): array
    {
        try {
            $admin = Staff::find($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            $admin->load('detail');

            return ResponseHelper::success($admin);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function changeAdminPassword(int $id, Request $request): array
    {
        $validator = $this->authValidator->validateAdminChangePassword($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $admin = Staff::find($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            $password = $request->input('password');

            $admin->password = Hash::make($password);
            $admin->save();

            DB::commit();
            return ResponseHelper::success(null, 'Password berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function createAdmin($request): array
    {
        $validator = $this->authValidator->validateCreateAdmin($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $fullName = $request->input('full_name');
            $username = $request->input('username');
            $recruitmentDate = $request->input('recruitment_date');
            $position = $request->input('position');
            $phoneNumber = $request->input('phone_number');
            $email = $request->input('email');
            $password = $request->input('password');

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);
            }

            $staffData = [
                'email' => $email,
                'username' => $username,
                'photo' => FolderEntities::DEFAULT_PROFILE_PICTURE,
                'password' => Hash::make($password),
            ];

            $staffDetailData = [
                'full_name' => $fullName,
                'recruitment_date' => $recruitmentDate,
                'position' => $position,
                'phone_number' => $phoneNumber,
            ];

            $admin = Staff::create($staffData);

            $admin->detail()->create($staffDetailData);

            DB::commit();
            return ResponseHelper::success(null, 'Admin berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function updateAdmin(int $id, Request $request): array
    {
        $validator = $this->authValidator->validateUpdateAdminProfile($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $fullName = $request->input('full_name');
            $username = $request->input('username');
            $recruitmentDate = $request->input('recruitment_date');
            $position = $request->input('position');
            $phoneNumber = $request->input('phone_number');
            $email = $request->input('email');
            $status = $request->input('status');

            $admin = Staff::find($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            if ($phoneNumber) {
                $phoneNumber = AuthService::getFormattedPhone($phoneNumber);
            }

            $staffData = [
                'email' => $email,
                'username' => $username,
                'status' => $status,
            ];

            $staffDetailData = [
                'full_name' => $fullName,
                'recruitment_date' => $recruitmentDate,
                'position' => $position,
                'phone_number' => $phoneNumber,
            ];

            $admin->update($staffData);

            $admin->detail()->update($staffDetailData);

            DB::commit();
            return ResponseHelper::success(null, 'Admin berhasil dirubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function toggleAdminStatus(int $id, Request $request): array
    {
        $validator = $this->authValidator->validateToggleAdminStatus($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $admin = Staff::find($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            $oldAdminStatus = $admin->status;

            $admin->status = !$oldAdminStatus;
            $admin->save();

            DB::commit();

            $message = $oldAdminStatus ? 'Admin berhasil di nonaktifkan' : 'Admin berhasil diaktifkan';

            return ResponseHelper::success(null, $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
