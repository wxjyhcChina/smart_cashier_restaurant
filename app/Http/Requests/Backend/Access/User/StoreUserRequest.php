<?php

namespace App\Http\Requests\Backend\Access\User;

use App\Common\RegExpPattern;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->hasRole(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'     => 'required|max:191',
            'last_name'  => 'required|max:191',
            'username'    => ['required', 'max:191', Rule::unique(config('access.users_table')), 'regex:'.RegExpPattern::REGEX_USERNAME],
            'password' => 'required|min:6|confirmed',
        ];
    }
}
