<?php

namespace App\Services\Admin;

use App\Helpers\ResponseHelper;
use App\Models\Staffs\Staff;
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
            $admins = Staff::all();

            if (!$admins) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            return ResponseHelper::success($admins);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function getAdminDetails($id): array
    {
        try {
            $admin = Staff::findOrFail($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            $admin->load('detail');

            return ResponseHelper::success($admin);
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function createAdmin($request): array
    {
        $validator = $this->authValidator->validateCreateAdmin($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['password'] = Hash::make($request->input('password'));

            $photoName = null;

            if ($request->hasFile('photo')) {
                $photoName = $request->file('photo')->getClientOriginalName();
                $request->file('photo')->storeAs('public/admins', $photoName);
            }

            $data['photo'] = $photoName;
            $admin = Staff::with('detail')->create($data);

            DB::commit();
            return ResponseHelper::success($admin, 'Admin berhasil dibuat');
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
            $admin = Staff::with('detail')->findOrFail($id);

            if (!$admin) {
                return ResponseHelper::notFound('Admin tidak ditemukan');
            }

            $data = $request->all();

            $photoName = null;

            if ($request->hasFile('photo')) {
                $photoName = $request->file('photo')->getClientOriginalName();
                $request->file('photo')->storeAs('public/admins', $photoName);
            }

            $data['photo'] = $photoName;
            $admin->update($data);

            DB::commit();
            return ResponseHelper::success($admin, 'Admin berhasil dirubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
