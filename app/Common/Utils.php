<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 12/02/2017
 * Time: 11:06 AM
 */

namespace App\Common;


class Utils
{
    /**
     * @return string
     */
    public static function guid()
    {
        $timestamp = time();
        $ranstr = rand(9999, 9999999999);
        return md5($timestamp . $ranstr) . substr(md5($ranstr), 0, 8);
    }

    /**
     * xml转为数组
     */
    public static function xmlToArray($xmlStr)
    {
        $msg = array();
        $postStr = $xmlStr;

        $msg = json_decode(json_encode(simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        return $msg;
    }


    /**
     * @param $number
     * @param int $precision
     * @return float|int
     */
    public static function round_up($number, $precision = 3)
    {
        $fig = (int) str_pad('1', $precision, '0');
        return (ceil($number * $fig) / $fig);
    }

    /**
     * @param $number
     * @param int $precision
     * @return float|int
     */
    public static function round_down($number, $precision = 3)
    {
        $fig = (int) str_pad('1', $precision, '0');
        return (floor($number * $fig) / $fig);
    }

    /**
     * @param $data
     * @param $key
     * @return mixed
     */
    public static function array_remove($data, $key)
    {
        if(!array_key_exists($key, $data)){
            return $data;
        }
        $keys = array_keys($data);
        $index = array_search($key, $keys);
        if($index !== FALSE){
            array_splice($data, $index, 1);
        }
        return $data;
    }

    /**
     * @param $day1
     * @param $day2
     * @return float|int
     */
    public static function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }
}