<?php

namespace App\Http\Service;

/**
 * 百度智能云图片审核
 */
class baidu
{
    /**
     * @param $url
     * @param $param
     * @return bool|string
     * Date: 2022/11/16
     * Time: 21:00
     * User: 尘
     * 使用curl发送请求
     */
    public static function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL, $postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);

        return $data;
    }

    /**
     * @return bool|string
     * Date: 2022/11/16
     * Time: 21:17
     * User: 尘
     * 获取token
     */
    public static function getToken()
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type'] = 'client_credentials';
        $post_data['client_id'] = env('BAIDU_RECORDS_APIKEY');
        $post_data['client_secret'] = env('BAIDU_RECORDS_SECRETKEY');
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);

        $res = self::request_post($url, $post_data);

        return $res;
    }

    /**
     * 图片审核
     */
    public static function pictureReview($token, $tmp_name)
    {
        $url = 'https://aip.baidubce.com/rest/2.0/solution/v1/img_censor/v2/user_defined?access_token=' . $token;
        $img = file_get_contents($tmp_name);
        $img = base64_encode($img);
        $bodys = array(
            'image' => $img
        );
        $res = baidu::request_post($url, $bodys);
        $res = json_decode($res, true);
        return $res;
    }

    /**
     * 内容审核
     */
    public function textReview($comment)
    {
        $appId = env('BAIDU_RECORDS_APPID');
        $apiKey = env('BAIDU_RECORDS_APIKEY');
        $secretKey = env('BAIDU_RECORDS_SECRETKEY');
        $client = new \Luffy\TextCensor\Core($appId, $apiKey, $secretKey);
        $res = $client->textCensorUserDefined($comment);
        return $res;
    }

}
