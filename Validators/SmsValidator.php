<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 11/02/2017
 * Time: 4:19 PM
 */

namespace App\Modules\Validators;

use App\Common\Sms;

class SmsValidator
{
    public function validate($attribute, $value, $params, $validator){
        //return true if field value is foo
        $telephone = $validator->getData()['telephone'];

        return Sms::verifyCode($telephone, $value);
    }
}