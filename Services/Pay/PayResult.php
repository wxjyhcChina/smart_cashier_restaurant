<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/10/19
 * Time: 17:45
 */

namespace App\Modules\Services\Pay;


class PayResult
{
    /**
     * @var
     */
    private $tradeStatus;

    /**
     * @var
     */
    private $error_message;

    /**
     * @var
     */
    private $trade_no;

    /**
     * PayResult constructor.
     * @param $tradeStatus
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getTradeStatus()
    {
        return $this->tradeStatus;
    }

    /**
     * @param mixed $tradeStatus
     */
    public function setTradeStatus($tradeStatus)
    {
        $this->tradeStatus = $tradeStatus;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param mixed $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

    /**
     * @return mixed
     */
    public function getTradeNo()
    {
        return $this->trade_no;
    }

    /**
     * @param mixed $trade_no
     */
    public function setTradeNo($trade_no)
    {
        $this->trade_no = $trade_no;
    }



}