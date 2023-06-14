<?php

namespace App\Validators;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductValidator
{
    private array $messages = ValidationHelper::VALIDATION_MESSAGES;

    public function validateCreateProduct($request)
    {
        $rules = [
            'title' => 'required|string|min:3|unique:products,title',
            'address' => 'required|string|min:3',
            'categories_id' => 'required|integer|exists:categories,id',
            'sub_categories_id' => 'required|integer|exists:sub_categories,id',
            'is_sold' => 'required|boolean',
            'price' => 'required|integer',
            'map_link' => 'required|string|min:3',
            'bedroom' => 'required|integer',
            'bathroom' => 'required|integer',
            'floor' => 'required|integer',
            'garage' => 'required|integer',
            'certificate' => 'required|string',
            'soil_area' => 'required|string',
            'land_area' => 'required|integer',
            'building_area' => 'required|integer',
            'building_size' => 'required|string',
            'building_condition' => 'required|string',
            'building_direction' => 'required|string',
            'electricity_capacity' => 'required|integer',
            'water_source' => 'required|string',
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

    public function validateCreateProductFacility($request)
    {
        $rules = [
            'product_id' => 'required|integer|exists:products,id',
            'facility' => 'required|string|min:3',
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
