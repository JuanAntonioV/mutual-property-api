<?php

namespace App\Repository\User;

use App\Traits\RepoTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepo implements UserRepoInterface
{
    use RepoTrait;

    public static function getUserProfileById($userId): object
    {
        return self::getDbTable()
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->select(
                'users.email',
                'users.email_verified_at',
                'users.status',
                'user_details.full_name',
                'user_details.phone_number'
            )->first();
    }

    private static function getDbTable(): object
    {
        return DB::table('users');
    }

    public static function getUserIdByEmail($email): int
    {
        return self::getDbTable()
            ->where('email', $email)
            ->value('id');
    }

    public static function updateUserPassword($userId, $password): bool
    {
        return self::getDbTable()
            ->where('id', $userId)
            ->update(['password' => Hash::make($password)]);
    }

    public static function updateUserProfile(int $userId, string $email, string $fullName, string $phoneNumber): bool
    {
        return self::getDbTable()
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->where('users.id', $userId)
            ->update([
                'users.email' => $email,
                'user_details.full_name' => $fullName,
                'user_details.phone_number' => $phoneNumber
            ]);
    }
}
