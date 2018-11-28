<?php
/**
 * 用户微信登录验证
 */
namespace Wechat\Middleware;

use Closure;
use Common\Config\SessionConfig;
use Frameworks\Tool\Http\SessionTool;
use Overtrue\Socialite\User as SocialiteUser;

class UserOauthAuthenticate
{
    protected $sessionTool = null;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->sessionTool = new SessionTool($request);
        $sessionKey = SessionConfig::FRONT_USER_ID;
        $userId = $this->sessionTool->get($sessionKey);
        if (empty($userId)) {
            // TODO: 测试环境
            $this->processUser();

            // TODO: 正式
//            $redirectUrl = $request->fullUrl();
//            session(SessionConfig::FRONT_OAUTH_REDIRECT_URL, $redirectUrl);
//            return redirect('wechat.oauth.front');
        }
        return $next($request);
    }

    public function processUser()
    {
        $user = new SocialiteUser([
            'id' => time(),
            'name' => 'name_'.time(),
            'nickname' => 'nickname_'.time(),
            'avatar' => '/storage/20181105/bgnOSAvr0oozKHnvjs32aQNNsRTpOSqtsjUZsVse.jpeg',
            'email' => null,
            'original' => [],
            'provider' => 'WeChat',
        ]);
        $userId = $this->registerUser($user);
        if ($userId) {
            $this->sessionTool->set(SessionConfig::FRONT_USER_ID, $userId);
            $this->sessionTool->set(SessionConfig::FRONT_USER_INFO, $user);
        }
        return $user;
    }

    protected function registerUser($user)
    {
        if (empty($user)) {
            return 0;
        }
//        $data = [
//            'nick_name'         => array_get($user, 'nickname'),
//            'openid'            => array_get($user, 'id'),
//            'face'              => array_get($user, 'avatar'),
//            'last_login_at'     => date('Y-m-d H:i:s'),
//        ];
//        list($status, $user) = (new UserProcessor())->insert($data);
//        if ($status) {
//            return $user->id;
//        }
        return 0;
    }
}
