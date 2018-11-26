<?php
/**
 * sql
 * Date: 2018/10/7
 * Time: 19:57
 */
namespace Frameworks\Services\Basic\Processor;

abstract class BaseSqlProcessor
{
    protected function mergerOtherParams($source, $dest)
    {
        if (is_array($source) && is_array($dest) && !empty($dest)) {
            return array_merge($source, $dest);
        }
        return $source;
    }
}