<?php

namespace App\Http\Requests\Api\Goods;

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
            'price' => 'required|numeric',
            'dinning_time_id' => 'required',
            'shop_id' => 'required'
        ];
    }
}

