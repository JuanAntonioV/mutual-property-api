<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateLogin($request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return ResponseHelper::error(
                $validator->errors()->first(),
                $validator->errors(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return false;
    }

    public function validateRegister($request)
    {
        $rules = [
            'full_name' => 'required|string|min:3|max:50',
            'phone_number' => 'required|string|min:10|max:16',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return ResponseHelper::error(
                $validator->errors()->first(),
                $validator->errors(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return false;
    }

    public function validateForgotPassword($request)
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()) {
            return ResponseHelper::error(
                $validator->errors()->first(),
                $validator->errors(),
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return false;
    }
}
