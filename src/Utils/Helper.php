<?php

namespace Goatgames\Sdk\Utils;

class Helper
{
    public static function sendPostRequest($url, $params = [], $headers = [], $timeout = 5, $type = "form")
    {
        $responseHeaders = [];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $type == "json" ? json_encode($params, 320) : http_build_query($params),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADERFUNCTION => function ($curl, $header) use (&$responseHeaders) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) {
                    return $len;
                }

                $responseHeaders[strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
        ]);

        if (!$result = curl_exec($curl)) {
            trigger_error(curl_error($curl));
        }

        // 获取状态码
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // 关闭连接
        curl_close($curl);

        return [
            'response_headers' => array_merge($responseHeaders, [
                'http_code' => $httpCode
            ]),
            'response_body' => $result
        ];
    }

    public static function currentmicrotime()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    public static function genSign($signData)
    {
        $signStr = implode("", $signData);
        return md5($signStr);
    }
}
