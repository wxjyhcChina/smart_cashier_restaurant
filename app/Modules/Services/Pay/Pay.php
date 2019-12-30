<?php

namespace App\Modules\Services\Pay;

use App\Common\Alipay;
use App\Common\WechatPay;
use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Enums\PayMethodType;
use App\Modules\Models\PayMethod\PayMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * @param $order_id
     * @param $barcode
     * @param $price
     * @param string $subject
     * @return PayResult
     * @throws ApiException|\Exception
     */
    public function barcodeWechatPay($order_id, $barcode, $price, $subject='付款')
    {
        $restaurant_id = Auth::User()->restaurant_id;

        $payMethod = PayMethod::where('method', PayMethodType::WECHAT_PAY)
            ->where('restaurant_id', $restaurant_id)
            ->where('enabled', 1)
            ->first();

        if ($payMethod == null)
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        $wechatPayDetail = $payMethod->wechat_pay_detail;
        if ($wechatPayDetail == null)
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        $wechatPay = new WechatPay($wechatPayDetail->app_id,
            $wechatPayDetail->mch_api_key,
            $wechatPayDetail->mch_id,
            Storage::disk('cert')->path($restaurant_id.'/'.$wechatPayDetail->ssl_cert_path),
            Storage::disk('cert')->path($restaurant_id.'/'.$wechatPayDetail->ssl_key_path));
        $wechatPay->setPrice($price);
        $wechatPay->setOrderId($order_id);
        $wechatPay->setBody($subject);
        $wechatPay->setCallBackUrl(config('app.url').'/api/v1/rechargeOrder/recharge_wechat_resp');

        $response = $wechatPay->micropay($barcode);
        $result = new PayResult();
        if ($response['success'] == true)
        {
            $response = $response['payInfo'];

            if ($response["result_code"] == "SUCCESS" && $response["result_code"] == "SUCCESS")
            {
                $result->setTradeStatus('SUCCESS');
                $result->setTradeNo($response['transaction_id']);
            }
            else if($response["return_code"] == "SUCCESS" &&
                $response["result_code"] == "FAIL" &&
                ($response["err_code"] == "USERPAYING" || $response["err_code"] == "SYSTEMERROR"))
            {
                $loopQueryResponse = $this->wechatLoopQueryResult($wechatPay);
                return $this->wechatCheckQueryAndCancel($wechatPay, $result, $loopQueryResponse);
            }
            else
            {
                $result->setTradeStatus("FAILED");
                $result->setErrorMessage(isset($response['err_code_des']) ? $response['err_code_des'] : "付款失败");
            }
        }
        else
        {
            $result->setTradeStatus("FAILED");
            $result->setErrorMessage($response['error_message']);
        }

        return $result;
    }

    /**
     * @param WechatPay $wechatPay
     * @return array|bool
     * @throws \Exception
     */
    private function wechatQuery(WechatPay $wechatPay)
    {
        $response = $wechatPay->tradeQuery();
        Log::info("wechat query result: ".json_encode($response));

        return $response['payInfo'];
    }

    /**
     * @param $response
     * @return bool
     */
    protected function wechatQuerySuccess($response)
    {
        return !empty($response)&&
            $response['return_code'] == "SUCCESS"&&
            $response['result_code'] == "SUCCESS"&&
            $response['trade_state'] == "SUCCESS";
    }


    /**
     * @param $response
     * @return bool
     */
    protected function wechatQueryClose($response)
    {
        return !empty($response)&&
            $response['return_code'] == "SUCCESS"&&
            $response['result_code'] == "SUCCESS"&&
            ($response['trade_state'] == "CLOSED" ||
                $response['trade_state'] == "REVOKED");
    }

    /**
     * @param $response
     * @return bool
     */
    protected function wechatStopQuery($response){
        if($response['return_code'] == "SUCCESS"&& $response['result_code'] == "SUCCESS"){
            if("CLOSED"==$response['trade_state']||
                "REVOKED"==$response['trade_state']||
                "SUCCESS"==$response['trade_state'] ||
                "PAYERROR"==$response['trade_state']){
                return true;
            }
        }
        return false;
    }

    /**
     * @param WechatPay $wechatPay
     * @return array|bool|null
     * @throws \Exception
     */
    protected function wechatLoopQueryResult(WechatPay $wechatPay){
        $queryResult = NULL;
        for ($i=1; $i < 20; $i++){
            try{
                sleep(3);
            }catch (\Exception $e){
                print $e->getMessage();
                exit();
            }

            $queryResponse = $this->wechatQuery($wechatPay);

            if(!empty($queryResponse)){
                if($this->wechatStopQuery($queryResponse)){
                    return $queryResponse;
                }
                $queryResult = $queryResponse;
            }
        }
        return $queryResult;
    }

    /**
     * @param WechatPay $wechatPay
     * @param $result
     * @param $response
     * @return PayResult
     * @throws \Exception
     */
    private function wechatCheckQueryAndCancel(WechatPay $wechatPay, PayResult $result, $response){
        if($this->wechatQuerySuccess($response)){
            $result->setTradeStatus('SUCCESS');
            $result->setTradeNo($response['trade_no']);
            return $result;
        }elseif($this->wechatQueryClose($response)){
            $result->setTradeStatus('CLOSED');
            return $result;
        }

        // 如果查询结果不为成功，则调用撤销h
        $cancelResponse = $wechatPay->tradeCancel();
        Log::info('cancel result '.json_encode($cancelResponse));
        if(!empty($cancelResponse)
            && $cancelResponse['payInfo']['return_code'] == "SUCCESS"
            && $cancelResponse['payInfo']['result_code'] == "SUCCESS"
        ){
            $result->setTradeStatus('CLOSED');
        }else{
            $result->setTradeStatus('UNKNOWN');
        }

        return $result;
    }

    /**
     * @param $order_id
     * @param $barcode
     * @param $price
     * @param string $subject
     * @return PayResult
     * @throws ApiException|\Exception
     */
    public function barcodeAlipay($order_id, $barcode, $price, $subject='付款')
    {
        $restaurant_id = Auth::User()->restaurant_id;

        $payMethod = PayMethod::where('method', PayMethodType::ALIPAY)
            ->where('restaurant_id', $restaurant_id)
            ->where('enabled', 1)
            ->first();

        if ($payMethod == null)
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        $alipayDetail = $payMethod->alipay_detail;
        if ($alipayDetail == null)
        {
            throw new ApiException(ErrorCode::PAY_METHOD_NOT_SUPPORTED, trans('api.error.pay_method_not_supported'));
        }

        $alipay = new Alipay($alipayDetail->app_id,
            Storage::disk('cert')->path($restaurant_id.'/'.$alipayDetail->mch_private_key_path),
            Storage::disk('cert')->path($restaurant_id.'/'.$alipayDetail->pub_key_path));
        $alipay->setPrice($price);
        $alipay->setOrderId($order_id);
        $alipay->setSubject($subject);
        $alipay->setCallBackUrl(config('app.url').'/api/v1/rechargeOrders/recharge_alipay_resp');

        $response = $alipay->alipayTradePay($barcode);
        $result = new PayResult();

        if (!empty($response) && ("10000" == $response['code'])) {
            // 支付交易明确成功
            $result->setTradeStatus('SUCCESS');
            $result->setTradeNo($response['trade_no']);
        } elseif (!empty($response) && ("10003" == $response['code'])) {

            $loopQueryResponse = $this->alipayLoopQueryResult($alipay);
            return $this->alipayCheckQueryAndCancel($alipay, $result, $loopQueryResponse);

        } elseif ($this->alipayTradeError($response)) {
            $queryResponse = $this->alipayQuery($alipay);
            return $this->alipayCheckQueryAndCancel($alipay, $result, $queryResponse);

        } else {
            // 其他情况表明该订单支付明确失败
            $result->setTradeStatus("FAILED");
            $result->setErrorMessage(isset($response['sub_msg']) ? $response['sub_msg'] : "付款失败");
        }

        return $result;
    }

    /**
     * @param $response
     * @return bool
     */
    private function alipayTradeError($response)
    {
        return empty($response)||
            $response['code'] == "20000";
    }

    /**
     * @param Alipay $alipay
     * @return array|bool
     * @throws \Exception
     */
    private function alipayQuery(Alipay $alipay)
    {
        $response = $alipay->tradeQuery();

        return $response;
    }

    /**
     * @param $response
     * @return bool
     */
    protected function alipayQuerySuccess($response)
    {
        return !empty($response)&&
            $response['code'] == "10000"&&
            ($response['trade_status'] == "TRADE_SUCCESS"||
                $response['trade_status'] == "TRADE_FINISHED");
    }

    /**
     * @param $response
     * @return bool
     */
    protected function alipayQueryClose($response)
    {
        return !empty($response)&&
            $response['code'] == "10000"&&
            $response['trade_status'] == "TRADE_CLOSED";
    }

    /**
     * @param $response
     * @return bool
     */
    protected function alipayStopQuery($response){
        if("10000"==$response['code']){
            if("TRADE_FINISHED"==$response['trade_status']||
                "TRADE_SUCCESS"==$response['trade_status']||
                "TRADE_CLOSED"==$response['trade_status']){
                return true;
            }
        }
        return false;
    }

    /**
     * @param Alipay $alipay
     * @return array|bool|null
     * @throws \Exception
     */
    protected function alipayLoopQueryResult(Alipay $alipay){
        $queryResult = NULL;
        for ($i=1; $i < 20; $i++){
            try{
                sleep(3);
            }catch (\Exception $e){
                print $e->getMessage();
                exit();
            }

            $queryResponse = $this->alipayQuery($alipay);
            if(!empty($queryResponse)){
                if($this->alipayStopQuery($queryResponse)){
                    return $queryResponse;
                }
                $queryResult = $queryResponse;
            }
        }
        return $queryResult;
    }

    /**
     * @param Alipay $alipay
     * @param $response
     * @return PayResult
     * @throws \Exception
     */
    private function alipayCheckQueryAndCancel(Alipay $alipay, PayResult $result, $response){
        if($this->alipayQuerySuccess($response)){
            $result->setTradeStatus('SUCCESS');
            $result->setTradeNo($response['trade_no']);
            return $result;
        }elseif($this->alipayQueryClose($response)){
            $result->setTradeStatus('CLOSED');
            return $result;
        }

        // 如果查询结果不为成功，则调用撤销
        $cancelResponse = $alipay->tradeCancel();
        if($this->alipayTradeError($cancelResponse)){
            $result->setTradeStatus('UNKNOWN');
        }else{
            $result->setTradeStatus('CLOSED');
        }

        return $result;
    }
}
