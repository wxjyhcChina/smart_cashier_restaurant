<?php

namespace App\Http\Requests\Api\ConsumeOrder;

use App\Http\Requests\ApiBaseRequest;

class ConsumeOrderQueryRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'start_time' => 'date_format:Y-m-d',
            'end_time' => 'date_format:Y-m-d|after_or_equal:start_time',
            
        ];
    }
}

