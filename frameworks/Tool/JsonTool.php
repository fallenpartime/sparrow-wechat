<?php
/**
 * Json工具
 * Date: 2018/7/27
 * Time: 12:11
 */
namespace Frameworks\Tool;

class JsonTool
{
    private $code   =   500;
    private $msg    =   '';
    private $url    =   '';
    private $data   =   [];

    public static function getInstance()
    {
        return new self();
    }

    public function _init()
    {
        $this->code = 500;
        $this->msg = '';
        $this->url = '';
        $this->data = '';
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }

    public function setDataValue($name, $value)
    {
        if (!is_array($this->data)) {
            $this->data = [];
        }
        $this->data[$name] = $value;
    }

    public function trans()
    {
        $result = [
            'code'  =>  $this->code,
            'msg'   =>  $this->msg,
            'data'  =>  $this->data,
            'url'   =>  $this->url,
        ];
        return $result;
    }

    public function toJson($result = [])
    {
        if (empty($result)) {
            $result = $this->trans();
        }
        return json_encode($result);
    }

    public function exitJson()
    {
        $resultJson = $this->toJson();
//        if (CommonTool::config('jsonp_open') && isset($_GET['callback'])) {
//            $callback = $_GET['callback'];
//            exit($callback.'('.$resultJson.')');
//        }
        echo $resultJson;
        exit();
    }

    public function customJson($result)
    {
        $resultJson = $this->toJson($result);
//        if (CommonTool::config('jsonp_open') && isset($_GET['callback'])) {
//            $callback = $_GET['callback'];
//            exit($callback.'('.$resultJson.')');
//        }
        echo $resultJson;
        exit();
    }
}