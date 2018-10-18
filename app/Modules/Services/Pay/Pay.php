<?php

namespace App\Modules\Services\Pay;

/**
 * Class Pay.
 */
class Pay
{
    /**
     * @param $barcode
     * @return bool
     */
    public function isWechatPay($barcode)
    {
        return starts_with($barcode, '10')
            || starts_with($barcode, '11')
            || starts_with($barcode, '12')
            || starts_with($barcode, '13')
            || starts_with($barcode, '14')
            || starts_with($barcode, '15');
    }

    /**
     * @param $barcode
     * @return bool
     */
    public function isAliPay($barcode)
    {
        return starts_with($barcode, '25')
            || starts_with($barcode, '26')
            || starts_with($barcode, '27')
            || starts_with($barcode, '28')
            || starts_with($barcode, '29')
            || starts_with($barcode, '30');
    }


    /**
     * @param $barcode
     * @param $price
     */
    private function barcodeWechatPay($barcode, $price)
    {
        //call api to pay


        //get result


        //update order status

    }

    /**
     * @param $barcode
     * @param $price
     */
    private function barcodeAlipay($barcode, $price)
    {

    }
}
