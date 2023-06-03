<?php

namespace App\Services\Auth;

use App\Entities\UserEntities;
use App\Helpers\ResponseHelper;
use App\Jobs\ProcessForgotPassword;
use App\Models\Users\User;
use App\Repository\AccessLog\AccessLogRepoInterface;
use App\Repository\Auth\AuthRepoInterface;
use App\Repository\PasswordReset\PasswordResetRepoInterface;
use App\Repository\User\UserRepoInterface;
use App\Validators\AuthValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    protected AuthRepoInterface $authRepo;

    protected AuthValidator $authValidator;
    protected AccessLogRepoInterface $accessLogRepo;
    protected UserRepoInterface $userRepo;
    protected PasswordResetRepoInterface $passwordResetRepo;

    public function __construct(AuthRepoInterface          $authRepo, AuthValidator $authValidator,
                                AccessLogRepoInterface     $accessLogRepo, UserRepoInterface $userRepo,
                                PasswordResetRepoInterface $passwordResetRepo)
    {
        $this->authRepo = $authRepo;
        $this->authValidator = $authValidator;
        $this->accessLogRepo = $accessLogRepo;
        $this->userRepo = $userRepo;
        $this->passwordResetRepo = $passwordResetRepo;
    }

    public function register($request): array
    {
        $validator = $this->authValidator->validateRegister($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $fullName = $request->input('full_name');
            $phoneNumber = $request->input('phone_number');
            $email = $request->input('email');
            $password = $request->input('password');

            $user = $this->authRepo->register($fullName, $phoneNumber, $email, $password);

            if (!$user) return ResponseHelper::error('Gagal mendaftarkan akun', null, 500);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil mendaftarkan akun');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function login($request): array
    {
        $validator = $this->authValidator->validateLogin($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $password = $request->input('password');

            $userCredential = $this->authRepo->getUserCredentialByEmail($email);

            if (!$userCredential) return ResponseHelper::error('Email atau password salah', null, 401);

            // check status
            if ($userCredential->status != UserEntities::STATUS_ACTIVE)
                return ResponseHelper::error('Akun anda telah di nonaktifkan sementara', null, 401);

            // check password match
            if (!Hash::check($password, $userCredential->password)) return ResponseHelper::error('Email atau password salah', null, 401);

            // generate token
            $token = self::generateAuthToken($userCredential->id);

            // save access log
            $this->accessLogRepo->insertLoginLog($userCredential->id, $request->ip(), $token);

            // get user data
            $user = $this->userRepo->getUserProfileById($userCredential->id);

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            DB::commit();
            return ResponseHelper::success($data, 'Berhasil login');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    private static function generateAuthToken(int $userId): string
    {
        return User::where('id', $userId)->first()->createToken('authToken')->accessToken;
    }

    public function logout($request): array
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $token = $request->bearerToken();

            $user->token()->revoke();

            // update access log
            $this->accessLogRepo->updateLogoutLog($user->id, $token);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil logout');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function me($request): array
    {
        try {
            $userId = $request->user()->id;

            $user = $this->userRepo->getUserProfileById($userId);

            if (!$user && !$userId) return ResponseHelper::error('User tidak ditemukan', null, 404);

            return ResponseHelper::success($user, 'Berhasil mengambil data user');
        } catch (\Exception $e) {
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    public function forgotPassword($request): array
    {
        $validator = $this->authValidator->validateForgotPassword($request);

        if ($validator) return $validator;

        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $userCredential = $this->authRepo->getUserCredentialByEmail($email);

            if (!$userCredential) return ResponseHelper::error('Email atau password salah', null, 401);

            // check status
            if ($userCredential->status != UserEntities::STATUS_ACTIVE)
                return ResponseHelper::error('Akun anda telah di nonaktifkan sementara', null, 401);

            $token = self::createNewResetPasswordToken();
            $fullName = $userCredential->full_name;

            $this->passwordResetRepo->insertOrUpdateToken($email, $token);

            dispatch(new ProcessForgotPassword($token, $email, $fullName));

            $data = [
                'email' => $email,
                'token' => $token
            ];

            DB::commit();
            return ResponseHelper::success($data, 'Berhasil mengirim email');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }

    private function createNewResetPasswordToken(): string
    {
        $token = Str::random(60);
        return hash('sha256', $token);
    }

    public function resetPassword($request): array
    {
        DB::beginTransaction();
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $token = $request->input('token');

            $userId = $this->userRepo->getUserIdByEmail($email);

            if (!$userId) return ResponseHelper::error('Email tidak terdaftar', null, 401);

            $tokenStatus = $this->passwordResetRepo->checkToken($email, $token);

            if (!$tokenStatus) {
                return ResponseHelper::error('Token tidak valid', null, 401);
            }

            $rememberToken = $this->passwordResetRepo->getCurrentRememberToken($userId);

            if ($rememberToken && $rememberToken === $token) {
                return ResponseHelper::error('Token tidak valid', null, 401);
            }

            $passwordEncrypted = md5($password);

            $this->passwordResetRepo->changePasswordByUserId($userId, $passwordEncrypted);

            $this->passwordResetRepo->updateRememberToken($userId, $token);

            DB::commit();
            return ResponseHelper::success(null, 'Berhasil merubah password');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::serverError($e->getMessage());
        }
    }
}
