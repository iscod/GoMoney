<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 18/9/7
 * Time: 下午8:39
 */

namespace App\Helper;
use App\Helper\ApiHandler;

class XueApiHandler extends ApiHandler
{
    /**
     * @param $url
     * @param $params
     * @param $cacErt
     * @return mixed
     * @throws SystemException
     */
    static function get(string $url, array $params = [], string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, false);
    }

    /**
     * @param $url
     * @param $params
     * @param $cacErt
     * @param string $input_charset
     * @return mixed
     * @throws SystemException
     */
    static function post(string $url, array $params = [], string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, true, false);
    }

    /**
     * @param $url
     * @param $params
     * @param $cacErt
     * @return mixed
     * @throws SystemException
     */
    static function put(string $url, array $params, string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, false, true);
    }

    /**
     * @param $url
     * @param $params
     * @param $cacErt
     * @param bool $is_post
     * @param bool $is_put
     * @return mixed
     * @throws SystemException
     */
    private static function _do_request(string $url, array $params, string $cacErt = '', $is_post = false, $is_put = false)
    {
        if (!$is_post) {
            if ($params) {
                $p_str = '';
                $comma = '';
                foreach ($params as $k => $v) {
                    $p_str .= $comma . $k . '=' . $v;
                    $comma = '&';
                }

                $url = $url . '?' . $p_str;
            }
        }

        $headers = [
            "Accept: text/html,application/json,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
            "Accept-Language: zh-CN,zh;q=0.9",
            "Cache-Control: max-age=0",
            "Connection: keep-alive",
            "Cookie: xq_a_token=776387e115646e8a4dcf81553387afac7c5a0279;",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // 过滤HTTP头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if($cacErt) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
            curl_setopt($ch, CURLOPT_CAINFO, $cacErt);//证书地址    
        }

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true); // post传输数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));// post传输数据
        }

        if ($is_put) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $output = curl_exec($ch);
        if ($output === FALSE) {
            throw new \Exception("Error Processing Request" . curl_error($ch), 1);
        }

        return $output;
    }
}
