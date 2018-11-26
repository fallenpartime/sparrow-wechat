<?php
/**
 * 默认redis拓展
 * Date: 2018/11/17
 * Time: 23:18
 */
namespace Frameworks\Traits;

use Frameworks\Tool\Cache\RedisTool;

trait DefaultCacheTrait
{
    protected $redisConnect = '';

    protected function getRedis()
    {
        if (isset($this->redisConnect)) {
            $this->redisConnect = new RedisTool();
        }
        return $this->redisConnect;
    }
}