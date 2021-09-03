<?php
namespace app\web\controller;

use think\Validate;
use think\Session;
/**
 * 登录认证
 * Class Passport
 * @package app\web\controller
 */
class Passport extends Controller
{
    private $user_list = [
      ['username' => 'root', 'password' => '123456'],
    ];
    /**
     * 后台登录
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login()
    {
        if ($this->request->isAjax()) {
            return $this->renderError('登录失败');
        }
        $this->view->engine->layout(false);
        return $this->fetch('login');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        Session::clear('user');
        if ($this->request->isAjax()) {
            return json($this->renderSuccess(['url' => url('web/passport/login')]));
        }
        $this->redirect('passport/login');
    }

    /**
     * 处理登录请求
     */
    public function loginCheck()
    {
        $data = $this->request->post('data');
        $data = json_decode($data, true);
        $validate = new Validate([
            'username' => 'require',
            'password' => 'require',
            'captcha' => 'require',

        ], [
            'username.require' => '账号不能为空',
            'password.require' => '密码不能为空',
            'captcha.require' => '验证码不能为空',
        ]);
        if (!$validate->check($data)) {
            $errmsg = $validate->getError();
            return json($this->renderError($errmsg));
        }

        if (!captcha_check($data['captcha'])) {
            return json($this->renderError('验证码错误'));
        }
        $user = [];
        foreach ($this->user_list as $item) {
            if ($item['username'] == $data['username'] && $item['password'] == $data['password']) {
                $user = $item;
                break;
            }
        }
        if (empty($user)) {
            return json($this->renderError('账号或密码错误'));
        }
        session('user', $user);
        return json($this->renderSuccess(['url' => url('web/index/index')]));

    }


}
