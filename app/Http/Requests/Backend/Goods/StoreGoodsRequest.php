<?php

namespace App\Http\Requests\Backend\Goods;

use App\Http\Requests\Request;

class StoreGoodsRequest extends Request
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
            'dinning_time' => 'required',
            'shop_id' => 'required',
            'good_category_id' => 'required'
        ];
    }
}

