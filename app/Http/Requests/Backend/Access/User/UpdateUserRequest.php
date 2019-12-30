<?php

namespace App\Http\Requests\Backend\Access\User;

use App\Common\RegExpPattern;
use App\Http\Requests\Request;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends Request
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
            'username' => ['required', 'max:191', 'regex:'.RegExpPattern::REGEX_USERNAME],
            'first_name'  => 'required|max:191',
            'last_name'  => 'required|max:191',
        ];
    }
}
