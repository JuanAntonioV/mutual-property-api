<?php

namespace App\Repository\User;

interface UserRepoInterface
{
    public static function getUserProfileById(int $userId): object;

    public static function getUserIdByEmail(string $email): int;

    public static function updateUserProfile(int $userId, array $data): bool;

    public static function updateUserPassword(int $userId, string $password): bool;
}
