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

/**
 * Class WechatPay
 * @package App\Common
 */
class WechatPay
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
     * @param $order_id
     * @param $price
     * @param $body
     * @param $pay_type
     * @param $call_back_url
     */
    public function __construct($order_id="", $price=0, $body="",  $call_back_url="", $pay_type=PaySource::APP)
    {
        $this->order_id = $order_id;
        $this->price = $price;
        $this->body = $body;
        $this->call_back_url = $call_back_url;
        $this->pay_type = $pay_type;
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
     * @return mixed|string
     */
    private function getAppId()
    {
        switch ($this->pay_type)
        {
            case PaySource::APP:
            case PaySource::NATIVE:
                return config('constants.wechat.app_id');
                break;
            case PaySource::JS:
                return config('constants.wechat.js_id');
                break;
            case PaySource::MINI_PROGRAM:
                return config('constants.wechat.mini_program_id');
                break;
        }

        return "";
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
            "mch_id" => config('constants.wechat.mch_id'),
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
            "partnerid" => config('constants.wechat.mch_id'),
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
        } elseif (config('constants.wechat.mch_id') == "") {
            return "微信商户号MCHID未配置";
        } elseif (config('constants.wechat.api_key') == "") {
            return "微信API密钥KEY未配置";
        }

        return "";
    }

    public function refund()
    {
        $check_result = $this->checkConfigParam();
        if (!empty($check_result))
        {
            return $this->response(false, $check_result);
        }

        $param = $this->getRefundInfo();

        //wechat unified order request
        $resultXmlStr = Http::WechatPostWithSecurity($param, config('constants.wechat.refund_url'), true);
        $result = Utils::xmlToArray($resultXmlStr);

        if (!$this->checkRespSign($result))
        {
            return $this->response(false, "退款失败");
        }

        return $this->response(true, "", $result);
    }

    private function getRefundInfo()
    {
        $price=sprintf("%.2f",$this->price);
        $refund_fee = sprintf("%.2f", $this->refund_fee);
        $param = array(
            "appid" => $this->getAppId(),
            "mch_id" => config('constants.wechat.mch_id'),
            "nonce_str" => $this->getNonceStr(),
            "out_trade_no" => $this->order_id,
            "out_refund_no" => $this->refund_id,
            "total_fee" => $price * 100,
            "refund_fee" => $refund_fee * 100,
            "op_user_id" => config('constants.wechat.mch_id'),
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

        $sign .= "key=" . config('constants.wechat.api_key');
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