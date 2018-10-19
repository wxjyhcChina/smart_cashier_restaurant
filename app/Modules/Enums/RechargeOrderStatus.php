<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 11/03/2017
 * Time: 4:52 PM
 */

namespace App\Modules\Enums;


class RechargeOrderStatus
{
    const REFUND_IN_PROGRESS = 'REFUND_IN_PROGRESS';
    const REFUNDED = 'REFUNDED';
    const WAIT_PAY = 'WAIT_PAY';
    const PAY_IN_PROGRESS = 'PAY_IN_PROGRESS';
    const COMPLETE = 'COMPLETE';
    const CLOSED = 'CLOSED';
}