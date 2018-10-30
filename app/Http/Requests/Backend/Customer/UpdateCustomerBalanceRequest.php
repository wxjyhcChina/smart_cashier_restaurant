<?php

namespace App\Http\Requests\Backend\Customer;

use App\Http\Requests\Request;

/**
 * Class UpdateCustomerBalanceRequest
 * @package App\Http\Requests\Backend\Access\User
 */
class UpdateCustomerBalanceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('manage-customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'balance' => 'required|numeric|min:0',
            'source' => 'required'
        ];
    }
}
