<?php

namespace App\Repository\Auth;

interface AuthRepoInterface
{
    public static function register(string $fullName, string $phoneNumber, string $email, string $password): bool;

    public static function getUserCredentialByEmail(string $email): object|null;

    public static function isPhoneNumberRegistered(string $phoneNumber): bool;
}
