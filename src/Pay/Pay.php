<?php

namespace Goatgames\Sdk\Pay;

use Goatgames\Sdk\Utils\Helper;

class Pay
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

    public function cpOrder($data)
    {

        // 获取签名
        $sign = Helper::getSign($data, $this->private_key);

        // 封装参数
        $params = array_merge($data, ['sign' => $sign]);

        // header数据
        $header = [
            'Content-Type:application/json;charset=utf-8',
        ];

        return Helper::sendPostRequest($this->req_url, $params, $header,  'json');
    }

    public function cpOrderQuery($data)
    {
        // 获取签名
        $sign = Helper::getSign($data, $this->private_key);

        // 封装参数
        $params = array_merge($data, ['sign' => $sign]);

        // header
        $header = [];

        return Helper::sendGetRequest($this->req_url, $params, $header);
    }
}
