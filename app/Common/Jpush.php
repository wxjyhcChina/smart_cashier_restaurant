<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 12/02/2017
 * Time: 10:58 PM
 */

namespace App\Common;

use Exception;

class Jpush
{
    public static function pushNotification($userIds, $title, $msg, $params)
    {
        $client = new \JPush(config('constants.jpush.access_key'), config('constants.jpush.secret'));

        try
        {
            $res = $client->push()
                ->setPlatform('all')
                ->addAlias($userIds)
                ->addIosNotification($msg, 'iOS sound', "+1", true, 'iOS category', $params)
                ->setMessage($msg, $title, null, $params)
                ->setOptions(null, 864000, null, (bool)config('constants.jpush.production'))
                ->send();
        }
        catch(Exception $e)
        {
            $res = 'false';
        }

        return $res;
    }
}