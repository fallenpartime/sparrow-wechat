<?php
/**
 * 基础worker拓展
 * Date: 2018/10/3
 * Time: 17:34
 */
namespace Frameworks\Services\Basic\Processor;

abstract class BaseWorkProcessor
{
    protected $status = 0;

    protected function simpleResult(...$otherParams)
    {
        $result = [$this->status];
        foreach ($otherParams as $otherParam) {
            $result[] = $otherParam;
        }
        return $result;
    }

    protected function parseResult($message = '', ...$otherParams)
    {
        $result = [$this->status, $message];
        foreach ($otherParams as $otherParam) {
            $result[] = $otherParam;
        }
        return $result;
    }

    public function process(){}
}