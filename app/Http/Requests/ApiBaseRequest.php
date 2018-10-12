<?php

namespace App\Http\Requests;

use App\Modules\Enums\ErrorCode;
use App\Exceptions\Api\ApiException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class Request
 * @package App\Http\Requests
 */
class ApiBaseRequest extends Request
{

    /**
     * @param Validator $validator
     * @throws ApiException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new ApiException(ErrorCode::INPUT_INCOMPLETE, $errors->first());
    }
}
