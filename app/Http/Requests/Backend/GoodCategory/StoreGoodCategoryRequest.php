<?php

namespace App\Http\Requests\Backend\GoodCategory;

use App\Http\Requests\Request;

class StoreGoodCategoryRequest extends Request
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
            'name' => 'required',
            'shop_id' => 'required'
        ];
    }
}

