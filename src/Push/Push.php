<?php

namespace Goatgames\Sdk\Push;

use Goatgames\Sdk\Utils\Helper;

class Push
{

    // 加密key
    protected $private_key;
    //请求地址
    protected $req_url;

    public function __construct($req_url, $key)
    {
        $this->req_url = $req_url;
        $this->private_key = $key;
    }


    public function sendPush($pushData)
    {
        // 获取时间戳
        $timestamp = time();

        // 获取签名
        $signData = [
            $pushData['gameId'],
            $timestamp,
            $pushData['requestId'],
            $this->private_key,
        ];
        $sign = Helper::genSign($signData);

        // 参数数据
        $data = [
            'user_ids'=> $pushData['userIds'],
            'title' => $pushData['title'],
            'content' => $pushData['content'],
        ];

        if (isset($pushData['serverId'])) {
            $data['server_id'] = $pushData['serverId'];
        }

        if (isset($pushData['appId'])) {
            $data['app_id'] = $pushData['appId'];
        }

        $params = [
            'type' => $pushData['type'],
            'data' => json_encode($data)
        ];

        // header数据
        $header = [
            'Content-Type:application/x-www-form-urlencoded;charset=utf-8',
            'gameId:'.$pushData['gameId'],
            'requestId:'.$pushData['requestId'],
            'ts:'.$timestamp,
            'sign:'.$sign,
        ];

        return Helper::sendPostRequest($this->req_url, $params, $header);
    }
}
