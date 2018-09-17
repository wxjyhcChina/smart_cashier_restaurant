<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 12/02/2017
 * Time: 11:03 AM
 */

namespace App\Common;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Qiniu
{
    public static function getUpToken($bucket, $postfix, $policy, $fileName='')
    {
        if ($fileName == '')
        {
            $fileName = Utils::guid().'.'.$postfix;
        }
        else
        {
            $fileName = $fileName.'.'.$postfix;
        }

        $policy['saveKey'] = $fileName;

        $auth = new Auth(config('constants.qiniu.access_key'), config('constants.qiniu.secret'));
        $token = $auth->uploadToken($bucket, null, 3600, $policy);

        return $token;
    }

    public static function uploadFile($bucket, $filePath, $postfix="png", $fileName='')
    {
        $token =  Qiniu::getUpToken($bucket, $postfix, array(), $fileName);
        $uploadMgr = new UploadManager();
        $ret = $uploadMgr->putFile($token, null, $filePath);

        $result = array();
        if(!empty($ret[0]))
        {
            $result = $ret[0]['key'];
        }
        else
        {
            $result = "";
        }
        return $result;
    }

    public static function fileUploadWithCorp($avatar_src, $avatar_data, $avatar_file, $width, $height, $bucket, $domain)
    {
        if($width == 0 && $height == 0)
        {
            $data_Array = json_decode($avatar_data, true);

            $width = $data_Array['width'];
            $height = $data_Array['height'];
        }

        $crop = new CropAvatar($avatar_src, $avatar_data, $avatar_file, $width, $height);

        $origin = $crop->getSrc();
        $result = $crop->getResult();

        $response = Qiniu::uploadFile($bucket, $result);

        if(!empty($response))
        {
            $response = $domain.$response;
            @unlink($origin);
            @unlink($result);
        }

        $response = array(
            'state'  => 200,
            'message' => $crop -> getMsg(),
            'result' => $response,
            'filename' => substr($response, strrpos($response, '/') + 1)
        );

        return json_encode($response);
    }
}