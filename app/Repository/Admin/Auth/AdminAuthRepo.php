<?php

namespace App\Repository\Admin\Auth;

use App\Models\Staffs\Staff;

class AdminAuthRepo implements AdminAuthRepoInterface
{
    public function getStaffCredentialByUsername(string $username)
    {
        return Staff::where('username', $username)->first();
    }

    public function getStaffCredentialByEmail(string $email)
    {
        return Staff::where('email', $email)->first();
    }

    public function getStaffProfileById(int $staffId)
    {
        return Staff::where('id', $staffId)
            ->with('detail')
            ->firstOrFail();
    }
}
