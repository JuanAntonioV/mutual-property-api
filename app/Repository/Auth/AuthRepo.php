<?php

namespace App\Repository\Auth;

use App\Traits\RepoTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepo implements AuthRepoInterface
{
    use RepoTrait;

    public static function register($fullName, $phoneNumber, $email, $password): bool
    {
        $userId = self::getDbTable()->insertGetId([
            'email' => $email,
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('user_details')->insert([
            'user_id' => $userId,
            'full_name' => $fullName,
            'phone_number' => $phoneNumber,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private static function getDbTable(): object
    {
        return DB::table('users');
    }

    public static function getUserCredentialByEmail($email): object
    {
        return self::getDbTable()
            ->join('user_details', 'user_details.user_id', 'users.id')
            ->where('email', $email)
            ->select(
                'users.id',
                'user_details.full_name',
                'users.email',
                'users.password',
                'users.status'
            )->first();
    }

    public static function isPhoneNumberRegistered($phoneNumber): bool
    {
        return DB::table('user_details')
            ->where('phone_number', $phoneNumber)
            ->exists();
    }
}
