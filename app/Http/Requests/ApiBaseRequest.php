<?php

namespace App\Http\Requests;

use App\Modules\Enums\ErrorCode;
use App\Exceptions\Api\ApiException;

/**
 * Class Request
 * @package App\Http\Requests
 */
class ApiBaseRequest extends Request
{
    //
    /**
     * override response method
     * @param array $errors
     * @throws ApiException
     * @return null
     */
    public function response(array $errors)
    {
        $error_values = array_values($errors);
        throw new ApiException(ErrorCode::INPUT_INCOMPLETE, $error_values[0][0]);
    }
}
