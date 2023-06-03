<?php

namespace App\Repository\AccessLog;

interface AccessLogRepoInterface
{
    public static function insertLoginLog(int $userId, string $ipAddress, string $token): bool;

    public static function updateLogoutLog(int $userId, string $token): bool;
}
