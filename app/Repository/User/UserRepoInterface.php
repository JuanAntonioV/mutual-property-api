<?php

namespace App\Repository\User;

interface UserRepoInterface
{
    public static function getUserProfileById(int $userId): object;

    public static function getUserIdByEmail(string $email): int;
}
