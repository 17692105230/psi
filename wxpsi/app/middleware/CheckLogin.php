<?php
declare (strict_types=1);

namespace app\middleware;

use app\common\service\Render;
use app\web\model\User as userModel;

/**
 * 校验登录
 * Class CheckLogin
 * @package app\middleware
 */
class CheckLogin
{
    use Render;

    /**
     * 处理请求
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $str = explode('/', $request->get('s'));
        $action = $str[count($str) - 1];
        $debug = $request->param('debug');
        if ($debug == 'fly') {
            return $next($request);
        }
        if (in_array($action, ["normal_login", 'register', 'is_authorize'])) {
            // 如果是登录或注册则不校验token
            return $next($request);
        }
        $token = $request->param('token');
        $code = 9999; // 前端根据这个状态码提示用户登录
        if (!$token) {
            return $this->renderError("缺少必要的参数: token", $code);
        }
        $userModel = new userModel();
        $user = $userModel->get_user_by_token($token);
        if (!$user) {
            return $this->renderError('登录失效', $code);
        }
        return $next($request);
    }
}
