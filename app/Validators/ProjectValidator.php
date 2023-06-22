<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProjectValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateCreateProject($request)
    {
        $rules = [
            'name' => 'required|string',
            'logo_image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'phone_number' => 'required|string|max:18',
            'whatsapp_number' => 'required|string|max:18',
            'email' => 'required|email|max:255',
            'started_price' => 'required|integer',
            'address' => 'required|string',
            'certificate' => 'required|string',
            'total_unit' => 'required|integer',
            'area' => 'required|integer',
            'map_url' => 'required|string',
            'facilities' => 'required|string',
            'brochure_file' => 'required|file|mimes:pdf|max:2048',
            'price_list_image' => 'required|file|mimes:jgp,jpeg,png|max:2048',
            'side_plan_image' => 'required|file|mimes:jgp,jpeg,png|max:2048',
            'description' => 'required|string',
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

    public function validateUpdateProject($request)
    {
        $rules = [
            'name' => 'required|string',
            'logo_image' => 'max:2048',
            'phone_number' => 'required|string|max:18',
            'whatsapp_number' => 'required|string|max:18',
            'email' => 'required|email|max:255',
            'started_price' => 'required|integer',
            'address' => 'required|string',
            'certificate' => 'required|string',
            'total_unit' => 'required|integer',
            'area' => 'required|integer',
            'map_url' => 'required|string',
            'facilities' => 'required|string',
            'brochure_file' => 'max:2048',
            'price_list_image' => 'max:2048',
            'side_plan_image' => 'max:2048',
            'description' => 'required|string',
            'status' => 'required|boolean',
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
