<?php

namespace App\Http\Requests\Api\ConsumeOrder;

use App\Http\Requests\ApiBaseRequest;

class CreateOrderRequest extends ApiBaseRequest
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
            'discount' => 'numeric|min:0|max:10',
        ];
    }
}

