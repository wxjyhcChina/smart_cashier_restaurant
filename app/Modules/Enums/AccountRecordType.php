<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 11/03/2017
 * Time: 4:52 PM
 */

namespace App\Modules\Enums;


class AccountRecordType
{
    const RECHARGE = "RECHARGE";
    const REFUND = "REFUND";
    const SYSTEM_ADD = "SYSTEM_ADD";

    const CONSUME = "CONSUME";
    const SYSTEM_MINUS = "SYSTEM_MINUS";
}