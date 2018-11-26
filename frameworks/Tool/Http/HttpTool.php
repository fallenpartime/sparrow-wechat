<?php
/**
 * http工具
 * Date: 2018/11/16
 * Time: 22:56
 */
namespace Frameworks\Tool\Http;

use Frameworks\Tool\CommonTool;
use Frameworks\Tool\Http\Config\HttpConfig;
use Frameworks\Tool\Http\Traits\RequestTrait;
use Illuminate\Http\Request;

class HttpTool
{
    use RequestTrait;

    private $_get_params = null;
    private $_post_params = null;
    private $_both_params = null;
    private $_session = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getSessionTool()
    {
        if (empty($this->_session)) {
            $this->_session = new SessionTool($this->request);
        }
        return $this->_session;
    }

    public function getParams($method = 2)
    {
        switch ($method)
        {
            case 0:
                {
                    if (is_null($this->_get_params)) {
                        $this->_get_params = $this->request->query->all();
                        foreach ($this->_get_params as &$param) {
                            $param = static::remove_xss($param);
                        }
                    }
                    return $this->_get_params;
                }
            case 1:
                {
                    if (is_null($this->_post_params)) {
                        $this->_post_params = $this->request->request->all();
                        foreach ($this->_post_params as &$param) {
                            $param = static::remove_xss($param);
                        }
                    }
                    return $this->_post_params;
                }
            case 2:
                {
                    if (is_null($this->_both_params)) {
                        static::getParams(HttpConfig::METHOD_GET);
                        static::getParams(HttpConfig::METHOD_POST);
                        $this->_both_params = $this->request->all();
                        foreach ($this->_both_params as &$param) {
                            $param = static::remove_xss($param);
                        }
                    }
                    return $this->_both_params;
                }
            default:
                return null;
        }
    }

    /**
     * 安全获取参数
     * @param int $requestMethod
     * @param int $paramType
     * @param $paramName
     * @param bool $required
     * @param null $defaultValue
     * @return int|null|string
     */
    public function getSafeParam($requestMethod = 2, $paramType = 1, $paramName, $required = false, $defaultValue = null)
    {
        if (!in_array($requestMethod, HttpConfig::getMethodList())) {
            return null;
        }
        $intType = HttpConfig::PARAM_NUMBER_TYPE;
        $txtType = HttpConfig::PARAM_TEXT_TYPE;
        $pi_InValid = null;
        $params = self::getParams($requestMethod);
        if($paramType == $intType) {
            $pi_InValid = HttpConfig::PARAM_NUMBER_DEFAULT;
        } else if($paramType == $txtType) {
            $pi_InValid = HttpConfig::PARAM_TEXT_DEFAULT;
        }
        if ($required == false && $defaultValue != null) {
            $t_Val = $defaultValue;
        } else {
            $t_Val = $pi_InValid;
        }
        if($paramValue = array_get($params, $paramName)) {
            $t_Val = trim($paramValue);
        }
        if ($intType == $paramType)  {
            // Integer
            if (CommonTool::isDigital($t_Val)) {
                return $t_Val;
            } else {
                return $defaultValue;
            }
        } else if ($txtType == $paramType) {
            $t_Val = strip_tags($t_Val);
            $t_Val = addslashes($t_Val);
            return $t_Val;
        }
        return null;
    }

    public function getGetSafeParam($paramName, $paramType = 1, $required = false, $defaultValue = null)
    {
        return $this->getSafeParam(HttpConfig::METHOD_GET, $paramType, $paramName, $required, $defaultValue);
    }

    public function getPostSafeParam($paramName, $paramType = 1, $required = false, $defaultValue = null)
    {
        return $this->getSafeParam(HttpConfig::METHOD_POST, $paramType, $paramName, $required, $defaultValue);
    }

    public function getBothSafeParam($paramName, $paramType = 1, $required = false, $defaultValue = null)
    {
        return $this->getSafeParam(HttpConfig::METHOD_BOTH, $paramType, $paramName, $required, $defaultValue);
    }

    /**
     * 过滤xss攻击参数
     * @param $val
     * @return null|string|string[]
     */
    public static function remove_xss($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=@avascript:alert('XSS')>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
            // @ @ search for the hex values
            $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // @ @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }
        // now the only remaining whitespace attacks are \t, \n, and \r
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
        $ra = array_merge($ra1, $ra2);
        $found = true; // keep replacing as long as the previous round replaced something
        while ($found == true) {
            $val_before = $val;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $pattern = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $pattern .= '(';
                        $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                        $pattern .= '|';
                        $pattern .= '|(�{0,8}([9|10|13]);)';
                        $pattern .= ')*';
                    }
                    $pattern .= $ra[$i][$j];
                }
                $pattern .= '/i';
                $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
                $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
                if ($val_before == $val) {
                    // no replacements were made, so exit the loop
                    $found = false;
                }
            }
        }
        return $val;
    }

    /**
     * 判断是否手机访问
     * @return bool
     */
    public static function isMobile()
    {
        $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
        $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
        $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
        $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";
        $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
        $regex_match.=")/i";
        return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
    }

    /**
     * 是否微信浏览器
     */
    public static function isWeixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    public function setSession($key, $value)
    {
        $this->getSessionTool()->set($key, $value);
    }

    public function removeSession($key)
    {
        $this->getSessionTool()->remove($key);
    }

    public function getSession($key)
    {
        return $this->getSessionTool()->get($key);
    }
}