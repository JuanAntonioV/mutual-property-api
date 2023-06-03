<?php

namespace App\Repository\PasswordReset;

use App\Traits\RepoTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PasswordResetRepo implements PasswordResetRepoInterface
{
    use RepoTrait;

    public static function insertOrUpdateToken($email, $token)
    {
        $now = Carbon::now()->toDateTimeString();

        return self::getDbTable()->updateOrInsert([
            'email' => $email
        ], [
            'token' => $token,
            'created_at' => $now,
        ]);
    }

    private static function getDbTable(): object
    {
        return DB::table('password_reset_tokens');
    }

    public static function checkToken($email, $token): bool
    {
        return (bool)self::getDbTable()
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>=', Carbon::now()->subMinutes()->toDateTimeString())
            ->select('token')
            ->first();
    }

    public static function changePasswordByUserId($userId, $password)
    {
        return DB::table('users')
            ->where('id', $userId)
            ->update([
                'password' => $password,
            ]);
    }

    public static function updateRememberToken($userId, $token)
    {
        return DB::table('users')
            ->where('id', $userId)
            ->update([
                'remember_token' => $token,
            ]);
    }

    public static function getCurrentRememberToken($userId): string|null
    {
        return DB::table('users')
            ->where('id', $userId)
            ->select('remember_token')
            ->first()
            ->remember_token;
    }
}
