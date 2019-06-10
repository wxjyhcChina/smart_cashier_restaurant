<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 09/03/2017
 * Time: 8:33 PM
 */

namespace App\Common;

/**
 * Class WechatPay
 * @package App\Common
 */
use App\Common\Enums\PaySource;
use Illuminate\Support\Facades\Log;

/**
 * Class WechatPay
 * @package App\Common
 */
class WechatPay
{
    /**
     * @var
     */
    private $app_id;

    /**
     * @var
     */
    private $api_key;

    /**
     * @var
     */
    private $mch_id;

    /**
     * @var
     */
    private $ssl_cert_path;

    /**
     * @var
     */
    private $ssl_key_path;
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
    private $pay_type;

    /**
     * Callback Url
     * @var
     */
    private $call_back_url;

    /**
     * Used for js and mini program
     * @var
     */
    private $open_id;

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
     * WechatPay constructor.
     * @param $app_id
     * @param $api_key
     * @param $mch_id
     * @param $ssl_cert_path
     * @param $ssl_key_path
     * @param int $pay_type
     */
    public function __construct($app_id, $api_key, $mch_id, $ssl_cert_path, $ssl_key_path, $pay_type=PaySource::APP)
    {
        $this->app_id = $app_id;
        $this->api_key = $api_key;
        $this->mch_id = $mch_id;
        $this->ssl_cert_path = $ssl_cert_path;
        $this->ssl_key_path = $ssl_key_path;
        $this->pay_type = $pay_type;
    }

    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param mixed $app_id
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param mixed $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return mixed
     */
    public function getMchId()
    {
        return $this->mch_id;
    }

    /**
     * @param mixed $mch_id
     */
    public function setMchId($mch_id)
    {
        $this->mch_id = $mch_id;
    }

    /**
     * @return mixed
     */
    public function getSslCertPath()
    {
        return $this->ssl_cert_path;
    }

    /**
     * @param mixed $ssl_cert_path
     */
    public function setSslCertPath($ssl_cert_path)
    {
        $this->ssl_cert_path = $ssl_cert_path;
    }

    /**
     * @return mixed
     */
    public function getSslKeyPath()
    {
        return $this->ssl_key_path;
    }

    /**
     * @param mixed $ssl_key_path
     */
    public function setSslKeyPath($ssl_key_path)
    {
        $this->ssl_key_path = $ssl_key_path;
    }

    /**
     * @param mixed $pay_type
     */
    public function setPayType($pay_type)
    {
        $this->pay_type = $pay_type;
    }

    /**
     * @param mixed $open_id
     */
    public function setOpenId($open_id)
    {
        $this->open_id = $open_id;
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
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getCallBackUrl()
    {
        return $this->call_back_url;
    }

    /**
     * @param mixed $call_back_url
     */
    public function setCallBackUrl($call_back_url)
    {
        $this->call_back_url = $call_back_url;
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
     * 请求微信API进行微信统一下单
     * URL地址：https://api.mch.weixin.qq.com/pay/unifiedorder
     */
    public function getUnifiedOrderInfo()
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getUnifiedOrderParam();

        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.unified_order_url'));
        $result = Utils::xmlToArray($resultXmlStr);

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "统一下单失败");
        }

        switch ($this->pay_type)
        {
            case PaySource::APP:
                $unifiedOrderInfo = $this->getAppUnifiedOrderInfo($result['prepay_id']);
                break;
            case PaySource::JS:
            case PaySource::MINI_PROGRAM:
                $unifiedOrderInfo = $this->getJSUnifiedOrderInfo($result['prepay_id']);
                break;
            case PaySource::NATIVE:
                $unifiedOrderInfo = ['code_url' => $result['code_url']];
                break;

            default:
                $unifiedOrderInfo = $this->getAppUnifiedOrderInfo($result['prepay_id']);
                break;
        }


        $unifiedOrderInfo["sign"] = $this->sign($unifiedOrderInfo);//生成签名

        return $this->response(true, "", $unifiedOrderInfo);
    }

    /**
     * @return string
     */
    private function getTradeType()
    {
        switch ($this->pay_type)
        {
            case PaySource::APP:
                return "APP";
                break;
            case PaySource::JS:
            case PaySource::MINI_PROGRAM:
                return "JSAPI";
                break;
            case PaySource::NATIVE:
                return "NATIVE";
                break;
            default:
                return "APP";
                break;
        }
    }


    /**
     * 获取同意下单参数
     * @return string
     */
    private function getUnifiedOrderParam()
    {
        $price=sprintf("%.2f",$this->price);
        $param = array(
            "appid" => $this->getAppId(),
            "body" => $this->body,
            "mch_id" => $this->getMchId(),
            "nonce_str" => $this->getNonceStr(),
            "notify_url" => $this->call_back_url,
            "out_trade_no" => $this->order_id,
            "total_fee" => $price * 100,
            "trade_type" => $this->getTradeType(),
        );

        if ($this->pay_type == PaySource::JS || $this->pay_type == PaySource::MINI_PROGRAM)
        {
            $param['openid'] = $this->open_id;
        }

        $sign = $this->sign($param);//生成签名
        $param['sign'] = $sign;
        $paramXml = "<xml>";
        foreach ($param as $k => $v) {
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";

        }
        $paramXml .= "</xml>";

        return $paramXml;
    }

    /**
     * @param $prepay_id
     * @return array
     */
    private function getAppUnifiedOrderInfo($prepay_id)
    {
        $unifiedOrderInfo = [
            "appid" => $this->getAppId(),
            "noncestr" => $this->getNonceStr(),
            "package" => "Sign=WXPay",
            "partnerid" => $this->getMchId(),
            "prepayid" => $prepay_id,
            "timestamp" => substr(time().'',0,10)
        ];

        return $unifiedOrderInfo;
    }

    /**
     * @param $prepay_id
     * @return array
     */
    private function getJSUnifiedOrderInfo($prepay_id)
    {
        $unifiedOrderInfo = [
            "appId" => $this->getAppId(),
            "nonceStr" => $this->getNonceStr(),
            "package" => "prepay_id=".$prepay_id,
            "signType" => "MD5",
            "timeStamp" => substr(time().'',0,10)
        ];

        return $unifiedOrderInfo;
    }


    /**
     * 获取随机字符串
     * @return string
     */
    private function getNonceStr()
    {
        $nonceStr = md5(rand(100, 1000) . time());
        return $nonceStr;
    }

    /**
     * 检测配置信息是否完整
     */
    public function checkConfigParam()
    {

        if ($this->getAppId() == "") {
            return "微信APPID未配置";
        } elseif ($this->getMchId() == "") {
            return "微信商户号MCHID未配置";
        } elseif ($this->getApiKey() == "") {
            return "微信API密钥KEY未配置";
        }

        return "";
    }

    /**
     * @return array
     */
    public function refund()
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getRefundInfo();

        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.refund_url'), true, $this->getSslCertPath(), $this->getSslKeyPath());
        $result = Utils::xmlToArray($resultXmlStr);

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "退款失败");
        }

        return $this->response(true, "", $result);
    }

    /**
     * @return string
     */
    private function getRefundInfo()
    {
        $price=sprintf("%.2f",$this->price);
        $refund_fee = sprintf("%.2f", $this->refund_fee);
        $param = array(
            "appid" => $this->getAppId(),
            "mch_id" => $this->getMchId(),
            "nonce_str" => $this->getNonceStr(),
            "out_trade_no" => $this->order_id,
            "out_refund_no" => $this->refund_id,
            "total_fee" => $price * 100,
            "refund_fee" => $refund_fee * 100,
            "op_user_id" => $this->getMchId(),
        );

        $sign = $this->sign($param);//生成签名
        $param['sign'] = $sign;
        $paramXml = "<xml>";
        foreach ($param as $k => $v) {
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";

        }
        $paramXml .= "</xml>";

        return $paramXml;
    }

    /**
     * @param $barcode
     * @return array
     */
    public function micropay($barcode)
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getMicropayInfo($barcode);
        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.micropay_url'));
        $result = Utils::xmlToArray($resultXmlStr);

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "付款失败");
        }

        return $this->response(true, "", $result);
    }

    /**
     * @param $barcode
     * @return string
     */
    private function getMicropayInfo($barcode)
    {
        $price=sprintf("%.2f",$this->price);
        $param = array(
            "appid" => $this->getAppId(),
            "body" => $this->body,
            "mch_id" => $this->getMchId(),
            "nonce_str" => $this->getNonceStr(),
            "out_trade_no" => $this->order_id,
            "total_fee" => $price * 100,
            "auth_code" => $barcode,
            "spbill_create_ip" => '10.10.10.10',
        );

        $sign = $this->sign($param);//生成签名
        $param['sign'] = $sign;
        $paramXml = "<xml>";
        foreach ($param as $k => $v) {
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";

        }
        $paramXml .= "</xml>";

        return $paramXml;
    }

    /**
     * @return array|bool
     * @throws \Exception
     */
    public function tradeQuery()
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getTradeQueryInfo();
        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.order_query_url'));
        $result = Utils::xmlToArray($resultXmlStr);

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "获取订单失败");
        }

        return $this->response(true, "", $result);
    }

    /**
     * @return string
     */
    private function getTradeQueryInfo()
    {
        $param = array(
            "appid" => $this->getAppId(),
            "mch_id" => $this->getMchId(),
            "nonce_str" => $this->getNonceStr(),
            "out_trade_no" => $this->order_id,
        );

        $sign = $this->sign($param);//生成签名
        $param['sign'] = $sign;
        $paramXml = "<xml>";
        foreach ($param as $k => $v) {
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";

        }
        $paramXml .= "</xml>";

        return $paramXml;
    }

    /**
     * @return array
     */
    public function tradeCancel()
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getTradeCancelInfo();
        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.order_reverse_url'));
        Log::info('trade cancel result : '.$resultXmlStr);
        $result = Utils::xmlToArray($resultXmlStr);

        Log::info('trade cancel result : '.json_encode($result));

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "撤销订单失败");
        }

        return $this->response(true, "", $result);
    }

    /**
     * @return string
     */
    private function getTradeCancelInfo()
    {
        $param = array(
            "appid" => $this->getAppId(),
            "mch_id" => $this->getMchId(),
            "nonce_str" => $this->getNonceStr(),
            "out_trade_no" => $this->order_id,
        );

        $sign = $this->sign($param);//生成签名
        $param['sign'] = $sign;
        $paramXml = "<xml>";
        foreach ($param as $k => $v) {
            $paramXml .= "<" . $k . ">" . $v . "</" . $k . ">";

        }
        $paramXml .= "</xml>";

        return $paramXml;
    }

    /**
     * sign拼装获取
     * @param Array $param
     * @return String
     */
    private function sign($param)
    {
        ksort($param);
        $sign = "";
        foreach ($param as $k => $v) {
            if ($v != "" && !is_array($v))
            {
                $sign .= $k . "=" . $v . "&";
            }
        }

        $sign .= "key=" . $this->getApiKey();
        $sign = strtoupper(md5($sign));
        return $sign;

    }

    /**
     * 检查微信回调签名
     * @param $param
     * @return bool
     */
    public function checkRespSign($param)
    {
        if ($param['return_code'] == "SUCCESS") {
            $wxSign = $param['sign'];
            unset($param['sign']);

            $sign = $this->sign($param);//生成签名

            if ($this->checkSign($wxSign, $sign)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $type
     * @param $msg
     * @return string
     */
    public static function returnInfo($type, $msg)
    {
        if ($type == "SUCCESS") {
            return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code></xml>";
        } else {
            return $returnXml = "<xml><return_code><![CDATA[{$type}]]></return_code><return_msg><![CDATA[{$msg}]]></return_msg></xml>";
        }
    }

    /**
     * 签名验证
     * @param $sign1
     * @param $sign2
     * @return bool
     */
    private function checkSign($sign1, $sign2)
    {
        return trim($sign1) == trim($sign2);
    }
}