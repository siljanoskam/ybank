<?php

namespace App\Http\Requests;

use App\Http\Responses\Std400Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest
{
    /**
     * Set a validation error response here
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $httpStatusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
        $response = new Std400Response();
        $response->setStatusCode($httpStatusCode);
        $response->setErrors([$validator->errors()]);

        throw new HttpResponseException(
            response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
