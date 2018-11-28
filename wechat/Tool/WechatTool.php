<?php
/**
 * 微信工具
 * Date: 2018/11/28
 * Time: 9:42
 */
namespace Wechat\Tool;

use Wechat\Config\WechatConfig;

class WechatTool
{
    protected $app = null;

    public function __construct($account = '')
    {
        $account = !empty($account)? $account: WechatConfig::DEFAULT_OFFICIAL_ACCOUNT;
        $this->app = app($account);
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getConfig()
    {
        return $this->app->conifg;
    }

    public function getUserInfo($openId)
    {
        return $this->app->user->get($openId);
    }

    public function pushTextMessage($message)
    {
        $this->app->server->push(function ($message) {
            return 'Welcome!!';
        });
    }

    public function setMessageHandler($func)
    {
        $this->app->server->setMessageHandler($func);
    }

    public function getAccessToken()
    {
        return $this->app->access_token->getToken();
    }

    public function serve()
    {
        return $this->app->server->serve();
    }

    public function valid()
    {
        return $this->app->server->validate();
    }
}