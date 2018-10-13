<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 18/9/7
 * Time: 下午8:39
 */

namespace App\Helper;

class ApiHandler
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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格认证

        if($cacErt) {
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
            // error log
            throw new SystemException("cURL Error: " . curl_error($ch), SystemCodes::SYSTEM_CURL_ERROR);
        }

        curl_close($ch);

        return $output;
    }
}
