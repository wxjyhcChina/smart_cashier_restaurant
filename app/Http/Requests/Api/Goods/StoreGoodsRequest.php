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
        $rules = ['price' => 'required|numeric'];

        if (!$this->has('is_temp') || $this->get('is_temp') == 0 )
        {
            $rules = array_merge($rules, [
                'name' => 'required',
                'dinning_time' => 'required',
                'shop_id' => 'required'
            ]);
        }

        return $rules;
    }
}

