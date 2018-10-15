<?php

namespace App\Http\Requests\Api\ConsumeRule;

use App\Http\Requests\ApiBaseRequest;

class StoreConsumeRuleRequest extends ApiBaseRequest
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
            'name' => 'required',
            'discount' => 'required|numeric',
            'weekday' => 'required|array',
            'dinning_time' => 'required|array',
            'consume_categories' => 'required|array',
        ];
    }
}

