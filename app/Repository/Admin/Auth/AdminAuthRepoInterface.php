<?php

namespace App\Repository\Admin\Auth;

interface AdminAuthRepoInterface
{
    public function getStaffCredentialByUsername(string $username);

    public function getStaffProfileById(int $staffId);
}
