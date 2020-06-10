<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 5:20 PM
 */

namespace App\Modules\Enums;

class StockDetailStatus
{
    const CONSUME = "CONSUME";//消费-
    const FRMLOSSPLUS = "FRMLOSSPLUS";//报损+
    const FRMLOSSMINUS = "FRMLOSSMINUS";//报损-
    const EXPEND = "EXPEND";//取出消耗-
    const PURCHASE="PURCHASE";//采购+
}