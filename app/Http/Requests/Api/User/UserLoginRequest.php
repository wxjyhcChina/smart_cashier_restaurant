<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 22/02/2017
 * Time: 8:45 PM
 */

namespace App\Http\Requests\Api\User;

use App\Http\Requests\ApiBaseRequest;

class UserLoginRequest extends ApiBaseRequest
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
            'username' => 'required',
            'password' => 'required'
        ];
    }
}
