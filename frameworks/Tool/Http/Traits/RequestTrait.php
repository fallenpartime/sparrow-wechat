<?php
/**
 * request扩展
 * Date: 2018/11/16
 * Time: 23:39
 */
namespace Frameworks\Tool\Http\Traits;

trait RequestTrait
{
    protected $request = null;

    /**
     * 是否https请求
     * @return mixed
     */
    public function isSecure()
    {
        return $this->request->isSecure();
    }

    /**
     * 是否ajax请求
     * @return mixed
     */
    public function isAjax()
    {
        return $this->request->ajax();
    }

    /**
     * 是否post请求
     * @return mixed
     */
    public function isPost()
    {
        return $this->isMethod('POST');
    }

    /**
     * 获取ip
     * @return string
     */
//    public static function getIp(){
//        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
//            $PHP_IP = getenv('HTTP_CLIENT_IP');
//        }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
//            $PHP_IP = getenv('HTTP_X_FORWARDED_FOR');
//        }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
//            $PHP_IP = getenv('REMOTE_ADDR');
//        }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
//            $PHP_IP = $_SERVER['REMOTE_ADDR'];
//        }
//        preg_match("/[\d\.]{7,15}/", $PHP_IP, $ipmatches);
//        $PHP_IP = $ipmatches[0] ? $ipmatches[0] : 'unknown';
//        return $PHP_IP;
//    }
}
