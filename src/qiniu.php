<?php

namespace App\Http\Service;

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

/**
 * 七牛云上传
 */
class qiniu
{
    public static function qiniu($key, $filePath)
    {
        $accessKey = env('*************');
        $secretKey = env("*************");
        $bucket = env("*************");
        $uploadMgr = new UploadManager();
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        list($ret, $error) = $uploadMgr->putFile($token, $key, $filePath);
        if ($error != '') return $error;
        $image = "http://rlfkhagfo.hd-bkt.clouddn.com/$key";
        return $image;
    }
}
