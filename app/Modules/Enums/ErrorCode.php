<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 5:20 PM
 */

namespace App\Modules\Enums;

class ErrorCode
{
    const DATABASE_ERROR = 0001;
    const PARAM_ERROR = 0002;

    //user_related
    const TOKEN_NOT_PROVIDED = 1001;
    const TOKEN_EXPIRE = 1002;
    const TOKEN_INVALID = 1003;
    const SMS_ERROR = 1004;
    const SMS_INVALID_TYPE = 1005;
    const USER_NOT_EXIST = 1006;
    const USER_ALREADY_EXIST = 1007;
    const LOGIN_FAILED = 1008;
    const CREATE_TOKEN = 1009;
    const INPUT_INCOMPLETE = 1010;
    const USER_HAVE_NOT_MONEY = 1011;
    const CREATE_USER_FAILED = 1012;
    const OPEN_ID_ACCESS_TOKEN_ERROR = 1013;

    //sn
    const SN_NOT_EXIST = 6001;
}