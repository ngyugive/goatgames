<?php

namespace Goatgames\Sdk\RiskControl;

use Goatgames\Sdk\Utils\Helper;

class RiskControl
{
    // 加密key
    protected $private_key;
    //请求地址
    protected $req_url;

    public function __construct($req_url, $key) {
        $this->req_url = $req_url;
        $this->private_key = $key;
    }

    public function text($data)
    {
        // 获取时间戳
        $timestamp = time();

        // 获取签名
        $signData = [
            $data['gameId'],
            $timestamp,
            $data['requestId'],
            $this->private_key,
        ];
        $sign = Helper::genSign($signData);

        // 参数
        $params = [
            'content' => $data['content'],
        ];

        // header数据
        $header = [
            'Content-Type:application/json;charset=utf-8',
            'gameId:'.$data['gameId'],
            'requestId:'.$data['requestId'],
            'ts:'.$timestamp,
            'sign:'.$sign,
            'serverId:'.$data['serverId'],
            'roleId:'.$data['roleId'],
        ];

        return Helper::sendPostRequest($this->req_url, $params, $header, 5, 'json');
    }
}