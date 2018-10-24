<?php

namespace App\Http\Requests\Backend\Goods;

use App\Http\Requests\ApiBaseRequest;

class StoreGoodsRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('manage-goods');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price' => 'required|numeric',
            'name' => 'required',
            'dinning_time_id' => 'required',
            'shop_id' => 'required'
        ];
    }
}

