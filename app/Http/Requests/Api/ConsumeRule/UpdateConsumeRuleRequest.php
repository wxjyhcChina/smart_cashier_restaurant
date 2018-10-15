<?php

namespace App\Http\Requests\Api\ConsumeRule;

use App\Http\Requests\ApiBaseRequest;

class UpdateConsumeRuleRequest extends ApiBaseRequest
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
            'discount' => 'numeric',
            'weekday' => 'array',
            'dinning_time' => 'array',
            'consume_categories' => 'array',
        ];
    }
}

