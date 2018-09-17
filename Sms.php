<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 6:23 PM
 */

namespace App\Common;
use Illuminate\Support\Facades\Log;

/**
 * Class Sms
 * @package App\Common
 */
class Sms
{
    /**
     * @return array
     */
    private static function getHeadArray()
    {
        $AppKey = config('constants.sms.app_key');
        $AppSecret = config('constants.sms.app_Secret');
        $Nonce = rand(100000, 999999);
        $CurTime = time();
        $CheckSum = strtolower(sha1($AppSecret . $Nonce . $CurTime));
        $head_arr = array();
        $head_arr[] = 'Content-Type: application/x-www-form-urlencoded';
        $head_arr[] = 'charset: utf-8';
        $head_arr[] = 'AppKey:' . $AppKey;
        $head_arr[] = 'Nonce:' . $Nonce;
        $head_arr[] = 'CurTime:' . $CurTime;
        $head_arr[] = 'CheckSum:' . $CheckSum;

        return $head_arr;
    }

    /**
     * @param $mobile
     * @return bool
     */
    public static function sendCode($mobile)
    {
        $target = config('constants.sms.base_url').config('constants.sms.code_url');

        $head_arr = self::getHeadArray();

        $post_data = "mobile=".$mobile."&codeLen=".config('constants.sms.code_len');

        if (config('constants.sms.template_id'))
        {
            $post_data .= "&templateid=".config('constants.sms.template_id');
        }

        $result = Http::PostWithHeader($post_data, $head_arr, $target);

        Log::info('send code result: '.$result);

        if (strstr($result,"\"code\":200"))
            return true;
        else
            return false;
    }

    /**
     * @param $mobile
     * @param $code
     * @return bool
     */
    public static function verifyCode($mobile, $code)
    {
        $target = config('constants.sms.base_url').config('constants.sms.verify_url');

        $head_arr = self::getHeadArray();

        $post_data = "mobile=".$mobile."&code=".$code;

        $result = Http::PostWithHeader($post_data, $head_arr, $target);

        if (strstr($result,"\"code\":200"))
            return true;
        else
            return false;
    }

    /**
     * @param $mobiles
     * @param $template_id
     * @param $params
     * @return bool
     */
    public static function sendTemplateMessage($mobiles, $template_id, $params)
    {
        $target = config('constants.sms.base_url').config('constants.sms.template_url');

        $head_arr = self::getHeadArray();

        $post_data = "templateid=".$template_id."&mobiles=".json_encode($mobiles)."&params=".json_encode($params);

        $result = Http::PostWithHeader($post_data, $head_arr, $target);

        if (strstr($result,"\"code\":200"))
            return true;
        else
            return false;
    }
}