<?php

namespace App\Repository\PasswordReset;

interface PasswordResetRepoInterface
{
    public static function insertOrUpdateToken(string $email, string $token);

    public static function checkToken(string $email, string $token): bool;

    public static function changePasswordByUserId(string $userId, string $password);

    public static function updateRememberToken(string $userId, string $token);

    public static function getCurrentRememberToken(string $userId): string|null;
}
