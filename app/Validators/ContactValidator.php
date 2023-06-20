<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContactValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateSendNewContact($request)
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
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
