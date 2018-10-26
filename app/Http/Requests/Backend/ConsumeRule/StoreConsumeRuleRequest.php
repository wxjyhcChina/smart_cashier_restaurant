<?php

namespace App\Http\Requests\Backend\ConsumeRule;

use App\Http\Requests\Request;

class StoreConsumeRuleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('manage-consume-rule');
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
