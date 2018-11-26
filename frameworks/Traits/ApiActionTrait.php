<?php
/**
 * api接口
 * Date: 2018/7/31
 * Time: 16:23
 */
namespace Frameworks\Traits;

use Frameworks\Tool\JsonTool;

trait ApiActionTrait
{
    protected $_jsonTool = null;

    protected function getJsonTool()
    {
        if (empty($this->_jsonTool)) {
            $this->_jsonTool = JsonTool::getInstance();
        }
        return $this->_jsonTool;
    }

    protected function successJson($msg = '', $data = [])
    {
        $jsonTool = $this->getJsonTool();
        $jsonTool->code = 200;
        $jsonTool->msg = $msg;
        $jsonTool->data = $data;
        $jsonTool->exitJson();
    }

    protected function errorJson($code = 500, $msg = '', $data = [])
    {
        $jsonTool = $this->getJsonTool();
        $jsonTool->code = $code;
        $jsonTool->msg = $msg;
        $jsonTool->data = $data;
        $jsonTool->exitJson();
    }

    protected function customJson($result)
    {
        $this->getJsonTool()->customJson($result);
    }

}