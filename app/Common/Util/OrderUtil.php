<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 11/03/2017
 * Time: 11:12 AM
 */

namespace App\Common\Util;


class OrderUtil
{
    private static function generateOrderId()
    {
        $time_ms_array = explode(" ", microtime());
        $ms_part = sprintf("%06d", $time_ms_array[0] * 1000 * 1000);
        $s_part = $time_ms_array[1];

        return date('YmdHis', $s_part) . $ms_part . rand(10000000,99999999);
    }

    public static function generateRechargeOrderId()
    {
        return 'ykr'.self::generateOrderId();
    }

    public static function generateCosumeOrderId()
    {
        return 'ykc'.self::generateOrderId();
    }
}