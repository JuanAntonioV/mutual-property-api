<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SubscriptionValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateCreateSubscription($request)
    {
        $rules = [
            'email' => 'required|email|max:255',
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
