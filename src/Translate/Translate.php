<?php

namespace Goatgames\Sdk\Translate;

use Goatgames\Sdk\Utils\Helper;

class Translate
{
    // 加密key
    const PRIVATE_KEY = '';
    //请求地址
    const REQ_URL = '';

    public function translate($data)
    {
        // 获取时间戳
        $timestamp = time();

        // 获取签名
        $signData = [
            $data['gameId'],
            $timestamp,
            $data['requestId'],
            self::PRIVATE_KEY,
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

        return Helper::sendPostRequest(self::REQ_URL, $params, $header, 5, 'json');
    }
}