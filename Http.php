<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 10/02/2017
 * Time: 5:47 PM
 */

namespace App\Common;

class Http
{
    public static function Post($curlPost, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function Get($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function PostWithHeader($curlPost, $curlHeader, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeader);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function GetWithHeader($curlHeader, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, $curlHeader);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function WechatPostWithSecurity($curlPost, $url, $useCert=false)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($curl,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($curl,CURLOPT_SSLCERT, config('constants.wechat.ssl_cert_path'));
            curl_setopt($curl,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($curl,CURLOPT_SSLKEY, config('constants.wechat.ssl_key_path'));
        }

        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    public static function DeleteWithHeader($curlHeader, $url)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_HTTPHEADER,$curlHeader);
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'DELETE');

        ob_start();
        curl_exec($curl);
        $result = ob_get_contents();
        ob_end_clean();
        curl_close($curl);

        return $result;
    }
}