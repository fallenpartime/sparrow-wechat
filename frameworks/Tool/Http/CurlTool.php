<?php
/**
 * Curl工具
 * Date: 2018/11/16
 * Time: 23:05
 */
namespace Frameworks\Tool\Http;

use Frameworks\Tool\Http\Traits\RequestTrait;
use Illuminate\Http\Request;

class CurlTool
{
    use RequestTrait;

    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36';
    private $useUserAgent = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        }
    }

    protected function initCurl($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        if (!empty($this->useUserAgent) && !empty($this->userAgent)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($this->isPost()) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        return $ch;
    }

    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @return bool|mixed
     */
    public function curl($url, $params = false)
    {
        $httpInfo = array();
        $ch = $this->initCurl($url, $params);
        if ($this->isSecure()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 2); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
     * CA证书验证
     * @param $url
     * @param bool $params
     * @return bool|mixed
     */
    public function curlByCA($url, $params = false)
    {
        $httpInfo = array();
        $ch = $this->initCurl($url, $params);
        curl_setopt($ch, CURLOPT_CAINFO, ''); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_CAPATH, ''); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}