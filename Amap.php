<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 25/04/2017
 * Time: 11:47 AM
 */

namespace App\Common;


class Amap
{
    public static function getAdCodeUsingLatAndLng($lat, $lng)
    {
        $url = config('constants.amap.url').'?key='.config('constants.amap.key').'&location='.$lng.','.$lat;
        $result = Http::Get($url);

        $result = json_decode($result, true);

        return $result['regeocode']['addressComponent']['adcode'];
    }
}

