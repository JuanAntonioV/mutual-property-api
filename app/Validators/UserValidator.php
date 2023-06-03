<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateUpdateUserProfile($request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|min:3|max:255',
            'phone_number' => 'required|string|max:16',
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
