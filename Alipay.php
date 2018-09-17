<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 13/03/2017
 * Time: 1:40 PM
 */

namespace App\Common;


/**
 * Class Alipay
 * @package App\Common
 */
/**
 * Class Alipay
 * @package App\Common
 */

use App\Common\Enums\AlipayLoginType;
use App\Common\Util\OrderUtil;
use Illuminate\Support\Facades\Log;

/**
 * Class Alipay
 * @package App\Common
 */
class Alipay
{
    /**
     * order Id
     * @var
     */
    private $order_id;

    /**
     * Order price
     * @var
     */
    private $price;

    /**
     * pay description
     * @var
     */
    private $body;

    /**
     * @var
     */
    private $subject;

    /**
     * Callback Url
     * @var
     */
    private $call_back_url;

    /**
     * @var
     */
    private $refund_fee;

    /**
     * @var
     */
    private $refund_id;

    /**
     * @var
     */
    private $op_user_id;

    /**
     * @var
     */
    private $pay_no;

    /**
     * WechatPay constructor.
     * @param $order_id
     * @param $price
     * @param $body
     * @param $call_back_url
     */
    public function __construct($order_id="", $price=0, $body="",  $call_back_url="", $subject="")
    {
        $this->order_id = $order_id;
        $this->price = $price;
        $this->body = $body;
        $this->call_back_url = $call_back_url;
        $this->subject = $subject;
    }

    /**
     * @param mixed $refund_fee
     */
    public function setRefundFee($refund_fee)
    {
        $this->refund_fee = $refund_fee;
    }

    /**
     * @param mixed $op_user_id
     */
    public function setOpUserId($op_user_id)
    {
        $this->op_user_id = $op_user_id;
    }

    /**
     * @param mixed $refund_id
     */
    public function setRefundId($refund_id)
    {
        $this->refund_id = $refund_id;
    }

    /**
     * @param mixed $pay_no
     */
    public function setPayNo($pay_no)
    {
        $this->pay_no = $pay_no;
    }

    /**
     * @param $success
     * @param $error_messge
     * @param string $payInfo
     * @return array
     */
    private function response($success, $error_messge, $payInfo="")
    {
        $result = array('success'=>$success, 'error_message'=>$error_messge, 'payInfo'=>$payInfo);

        return $result;
    }

    /**
     * @param $type
     * @return \AopClient
     */
    private function getAopClient($type = AlipayLoginType::APP)
    {
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $type == AlipayLoginType::APP ? config('constants.alipay.app_id') : config('constants.alipay.app_miniprogram_id');
        $aop->rsaPrivateKeyFilePath = $type == AlipayLoginType::APP ? config('constants.alipay.alipay_mch_private_key') : config('constants.alipay.alipay_miniprogram_private_key');
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayPublicKey=config('constants.alipay.alipay_pub_key');

        return $aop;
    }

    /**
     * 获取第三方登录字串
     *
     * @return string
     */
    public function getAppAuthCode()
    {
        $params = array(
            "apiname" => 'com.alipay.account.auth',
            "method" => 'alipay.open.auth.sdk.code.get',
            "app_id"=> config('constants.alipay.app_id'),
            "app_name"=>'mc',
            "biz_type" => 'openservice',
            'pid' => '2088721389489661',
            'product_id' => 'APP_FAST_LOGIN',
            'scope' => 'kuaijie',
            'target_id' => OrderUtil::generateCosumeOrderId(),
            "auth_type" => 'AUTHACCOUNT',
            "sign_type" => 'RSA2'
        );

        $aop = $this->getAopClient();
        $sign = $aop->generateSign($params, 'RSA2');
        $params['sign'] = $sign;

        return http_build_query($params);
    }

    /**
     *  无线账户授权
     *
     * @param $code
     * @param $type
     * @return bool|mixed|\SimpleXMLElement
     */
    public function alipaySystemAccountAuth($code, $type = AlipayLoginType::APP)
    {
        $aop = $this->getAopClient($type);

        $request = new \AlipaySystemOauthTokenRequest();
        $request->setCode($code);
        $request->setGrantType('authorization_code');

        $response = $aop->execute($request);

        Log::info('response is $response!');

        if (isset($response->alipay_system_oauth_token_response))
        {
            return ['access_token' => $response->alipay_system_oauth_token_response->access_token, 'user_id'=> $response->alipay_system_oauth_token_response->user_id];
        }

        return false;
    }

    /**
     * 用户信息共享
     *
     * @param $access_token
     * @param $type
     * @return bool|mixed|\SimpleXMLElement
     */
    public function alipayUserInfoShare($access_token,  $type = AlipayLoginType::APP)
    {
        $aop = $this->getAopClient($type);
        $request = new \AlipayUserInfoShareRequest();

        $response = $aop->execute($request, $access_token);

        Log::info('response is $response!');

        if (isset($response->alipay_user_info_share_response))
        {
            return (array)$response->alipay_user_info_share_response;
        }

        return false;
    }

    /**
     * @param $property_id
     * @return array|bool
     */
    public function alipayTradePrecreate($property_id)
    {
        $aop = $this->getAopClient();

        $bizContent = $this->getPrecreateAppBizContent($property_id);
        $request = new \AlipayTradePrecreateRequest();
        $request->setNotifyUrl($this->call_back_url);
        $request->setBizContent($bizContent);

        $response = $aop->execute($request);
        if (isset($response->alipay_trade_precreate_response))
        {
            return (array)$response->alipay_trade_precreate_response;
        }

        return false;
    }

    /**
     * @param $property_id
     *
     * @return string
     */
    private function getPrecreateAppBizContent($property_id)
    {
        $param = array(
            "out_trade_no"=>$this->order_id,
            "subject" => "优卡充充值",
            'store_id' => $property_id,
            "total_amount" => $this->price,
            "timeout_express" => "10m",
        );

        return json_encode($param);
    }

    /**
     * @return mixed
     */
    public function getUnifiedOrderInfo()
    {
        $aop = $this->getAopClient();
        $request = new \AlipayTradeAppPayRequest();

        $bizContent = $this->getAppBizContent();
        $request->setNotifyUrl($this->call_back_url);
        $request->setBizContent($bizContent);

        $response = $aop->sdkExecute($request);
        return $this->response("success", "", $response);
    }

    /**
     * @return string
     */
    private function getAppBizContent()
    {
        $param = array(
            "body" => $this->body,
            "subject" => $this->body,
            "out_trade_no"=>$this->order_id,
            "timeout_express" => "40m",
            "total_amount" => $this->price,
            "product_code" => "QUICK_MSECURITY_PAY"
        );

        return json_encode($param);
    }


    /**
     * @param $input
     * @return bool
     */
    public function checkSign($input)
    {
        $aop = new \AopClient();
        $aop->alipayPublicKey=config('constants.alipay.alipay_pub_key');
        $flag = $aop->rsaCheckV1($input, config('constants.alipay.alipay_pub_key'), "RSA2");

        return $flag;
    }

    /**
     *
     */
    public function refund()
    {
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = config('constants.alipay.app_id');
        $aop->rsaPrivateKeyFilePath = config('constants.alipay.alipay_mch_private_key');
        $aop->alipayPublicKey= config('constants.alipay.alipay_pub_key');
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest();

        $bizContent = $this->getRefundBizContent();
        $request->setBizContent($bizContent);
        $response = $aop->execute ( $request);

        $this->response(true, "", htmlspecialchars($response));
    }

    /**
     * @return string
     */
    private function getRefundBizContent()
    {
        $param = array(
            "out_trade_no" => $this->order_id,
            "trade_no" => $this->pay_no,
            "refund_amount"=>$this->refund_fee,
            "refund_reason" => "refund",
            "out_request_no" => $this->refund_id,
            "operator_id" => "OP001",
            "store_id" => "NJ_S_001",
            "terminal_id" => "NJ_T_001"
        );

        return json_encode($param);
    }
}