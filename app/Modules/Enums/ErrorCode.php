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
    const DATABASE_ERROR = "DATABASE_ERROR";
    const PARAM_ERROR = "PARAM_ERROR";

    const RESOURCE_NOT_FOUND = "RESOURCE_NOT_FOUND";

    //user_related
    const TOKEN_NOT_PROVIDED = "TOKEN_NOT_PROVIDED";
    const TOKEN_EXPIRE = "TOKEN_EXPIRE";
    const TOKEN_INVALID = "TOKEN_INVALID";
    const USER_NOT_EXIST = "USER_NOT_EXIST";
    const USER_ALREADY_EXIST = "USER_ALREADY_EXIST";
    const LOGIN_FAILED = "LOGIN_FAILED";
    const CREATE_TOKEN_FAILED = "CREATE_TOKEN_FAILED";
    const INPUT_INCOMPLETE = "INPUT_INCOMPLETE";

    //dinning time related
    const DINNING_TIME_CONFLICT = "DINNING_TIME_CONFLICT";


}