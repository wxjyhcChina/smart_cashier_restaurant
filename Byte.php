<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 20/03/2017
 * Time: 2:17 PM
 */

namespace App\Common;


class Byte
{
    /**
     * 转换一个String字符串为byte数组(10进制)
     * @param $str
     * @return array
     */
    public static function getBytes_10($str) {
        $len = strlen($str);
        $bytes = array();
        for($i=0;$i<$len;$i++) {
            if(ord($str[$i]) >= 128){
                $byte = ord($str[$i]) - 256;
            }else{
                $byte = ord($str[$i]);
            }
            $bytes[] =  $byte ;
        }
        return $bytes;
    }


    /**
     * 转换一个String字符串为byte数组(16进制)
     * @param $str
     * @return array
     */
    public static function getBytes_16($str) {


        $len = strlen($str);
        $bytes = array();
        for($i=0;$i<$len;$i++) {
            if(ord($str[$i]) >= 128){
                $byte = ord($str[$i]) - 256;
            }else{
                $byte = ord($str[$i]);
            }
            $bytes[] =  "0x".dechex($byte) ;
        }
        return $bytes;
    }


    /**
     * 转换一个String字符串为byte数组(2进制)
     * @param $str
     * @return array
     */
    public static function StrToBin($str){
        //1.列出每个字符
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        //2.unpack字符
        foreach($arr as &$v){
            $temp = unpack('H*', $v);
            $v = base_convert($temp[1], 16, 2);
            unset($temp);
        }

        return $arr;
    }


    /**
     * 转换一个byte数组为String(2进制)
     * @param $str 需要转换的字符串
     * @return string
     */
    function BinToStr($str){
        $arr = explode(' ', $str);
        foreach($arr as &$v){
            $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
        }

        return $v;
    }

    /**
     * 将字节数组转化为String类型的数据
     * @param $bytes
     * @return string
     */
    public static function toStr($bytes) {
        $str = '';
        foreach($bytes as $ch) {
            $str .= chr($ch);
        }


        return $str;
    }

    /**
     * 转换一个int为byte数组
     * @param $val
     * @return array
     */

    public static function integerToBytes($val) {
        $byt = array();
        $byt[0] = ($val & 0xff);
        $byt[1] = ($val >> 8 & 0xff);
        $byt[2] = ($val >> 16 & 0xff);
        $byt[3] = ($val >> 24 & 0xff);
        return $byt;
    }

    /**
     * 从字节数组中指定的位置读取一个Integer类型的数据
     * @param $bytes 字节数组
     * @param $position 指定的开始位置
     * @return 一个Integer类型的数据
     */

    public static function bytesToInteger($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 3] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 2] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position + 1] & 0xff;
        $val <<= 8;
        $val |= $bytes[$position] & 0xff;
        return $val;
    }


    /**
     * 转换一个short字符串为byte数组
     * @param $val
     * @return array
     */

    public static function shortToBytes($val) {
        $byt = array();
        $byt[0] = ($val & 0xff);
        $byt[1] = ($val >> 8 & 0xff);
        return $byt;
    }

    /**
     * 从字节数组中指定的位置读取一个Short类型的数据。
     * @param $bytes
     * @param $position
     * @return short
     */
    public static function bytesToShort($bytes, $position) {
        $val = 0;
        $val = $bytes[$position + 1] & 0xFF;
        $val = $val << 8;
        $val |= $bytes[$position] & 0xFF;
        return $val;
    }

}