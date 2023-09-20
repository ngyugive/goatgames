<?php

namespace Goatgames\Sdk\Translate;

use Goatgames\Sdk\Utils\Helper;

class Translate
{
    // 加密key
    protected $private_key;
    //请求地址
    protected $req_url;

    public function __construct($req_url, $key) {
        $this->req_url = $req_url;
        $this->private_key = $key;
    }

    public function translate($data)
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
            'source' => $data['source'],
            'target' => $data['target'],
            'content' => $data['content'],
        ];

        // header数据
        $header = [
            'Content-Type:application/json;charset=utf-8',
            'gameId:'.$data['gameId'],
            'requestId:'.$data['requestId'],
            'ts:'.$timestamp,
            'sign:'.$sign,
        ];

        return Helper::sendPostRequest($this->req_url, $params, $header, 5, 'json');
    }
}