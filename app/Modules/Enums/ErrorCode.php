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
    const FATAL_ERROR = "FATAL_ERROR";

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
    const NOT_IN_DINNING_TIME = "NOT_IN_DINNING_TIME";
    const DINNING_TIME_NOT_EXIST = "DINNING_TIME_NOT_EXIST";

    //consume rule conflict
    const CONSUME_RULE_CONFLICT = "CONSUME_RULE_CONFLICT";

    //card related
    const CARD_NOT_EXIST = "CARD_NOT_EXIST";
    const CARD_STATUS_INCORRECT = "CARD_STATUS_INCORRECT";

    //customer related
    const BALANCE_NOT_ENOUGH = 'BALANCE_NOT_ENOUGH';

    //label related
    const LABEL_NOT_EXIST = "LABEL_NOT_EXIST";
    const LABEL_CATEGORY_NOT_BINDED = "LABEL_CATEGORY_NOT_BINDED";
    const LABEL_CATEGORY_ALREADY_BINDED = "LABEL_CATEGORY_ALREADY_BINDED";
    const LABEL_CATEGORY_NOT_BIND_GOOD = "LABEL_CATEGORY_NOT_BIND_GOOD";

    //goods related
    const GOODS_NOT_EXIST = 'GOODS_NOT_EXIST';

    //recharge order
    const RECHARGE_ORDER_CANCELED = 'RECHARGE_ORDER_CANCELED';

    //order related
    const ORDER_GOODS_NOT_EXIST = 'ORDER_GOODS_NOT_EXIST';
    const ORDER_STATUS_INCORRECT = 'ORDER_STATUS_INCORRECT';

    //pay method related
    const PAY_METHOD_NOT_SUPPORTED = 'PAY_METHOD_NOT_SUPPORTED';
    const PAY_FAILED = 'PAY_FAILED';
}