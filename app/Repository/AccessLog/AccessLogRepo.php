<?php

namespace App\Repository\AccessLog;

use App\Traits\RepoTrait;
use Illuminate\Support\Facades\DB;

class AccessLogRepo implements AccessLogRepoInterface
{
    use RepoTrait;

    public static function insertLoginLog($userId, $ipAddress, $token): bool
    {
        return self::getDbTable()->insert([
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'token' => $token,
            'login_at' => now(),
            'logout_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private static function getDbTable(): object
    {
        return DB::table('access_logs');
    }

    public static function updateLogoutLog($userId, $token): bool
    {
        return self::getDbTable()
            ->where('user_id', $userId)
            ->where('token', $token)
            ->update([
                'logout_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
