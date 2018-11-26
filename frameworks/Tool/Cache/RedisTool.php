<?php
/**
 * Redis工具
 * Date: 2018/11/17
 * Time: 23:05
 */
namespace Frameworks\Tool\Cache;

use Illuminate\Support\Facades\Redis;

class RedisTool
{
    protected $connect = null;
    protected $dbIndex = 0;

    public function __construct($connectName = 'default', $dbIndex = 0)
    {
        $this->connect = Redis::connection($connectName);
        $this->dbIndex = $dbIndex;
        $this->_init();
    }

    public function _init()
    {
        $this->select($this->dbIndex);
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->connect, $name], $arguments);
    }

    public function select($dbIndex)
    {
        $this->connect->select($dbIndex);
    }

    public function remove($key)
    {
        return $this->connect->del($key);
    }

    public function hgetall($key)
    {
        return $this->connect->hgetall($key);
    }

    public function hmset($key, $data, $timeout = 0)
    {
        $result = $this->connect->hmset($key, $data);
        if ($result && $timeout > 0) {
            $this->expire($key, $timeout);
        }
        return $result;
    }

    public function hexists($key, $column)
    {
        return $this->connect->hexists($key, $column);
    }

    public function hincrby($key, $column, $disc)
    {
        return $this->connect->hincrby($key, $column, $disc);
    }

    public function set($key, $value)
    {
        return $this->connect->set($key, $value);
    }

    public function setex($key, $value, $timeout)
    {
        return $this->connect->setex($key, $timeout, $value);
    }

    public function expire($key, $timeout)
    {
        return $this->connect->expire($key, $timeout);
    }

    public function exists($key)
    {
        return $this->connect->exists($key);
    }
}