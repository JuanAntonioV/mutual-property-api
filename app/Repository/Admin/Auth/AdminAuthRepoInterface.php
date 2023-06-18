<?php

namespace App\Repository\Admin\Auth;

interface AdminAuthRepoInterface
{
    public function getStaffCredentialByUsername(string $username);

    public function getStaffCredentialByEmail(string $email);

    public function getStaffProfileById(int $staffId);
}
