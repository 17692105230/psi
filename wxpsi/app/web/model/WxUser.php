<?php
/**
 * Created by PhpStorm.
 * User: zll
 * Date: 2021/1/14
 * Time: 9:18
 */

namespace app\web\model;

use app\common\exception\BaseException;
use app\common\model\web\WxUser as WxUserModel;
use app\common\library\wechat\WxUser as WxUserLib;
use think\facade\Cache;
use app\web\model\Wxapp as WxappModel;

class WxUser extends WxUserModel
{
    private $token;

    /**
     * 获取用户信息
     * @param $token
     * @return null|static
     * @throws \think\exception\DbException
     */
    public static function getUser($token)
    {
        $openId = Cache::get($token)['openid'];
        return self::detail(['open_id' => $openId]);
    }

    /**
     * 用户登录
     * @param array $post
     * @return string
     * @throws BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login($post)
    {
        // 微信登录 获取session_key
        $session = $this->wxlogin($post['code']);
        // 自动注册用户
//        $refereeId = isset($post['referee_id']) ? $post['referee_id'] : null;
        $userInfo = json_decode(htmlspecialchars_decode($post['user_info']), true);
//        $user_id = $this->register($session['openid'], $userInfo, $refereeId);
        // 生成token (session3rd)
//        $this->token = $this->token($session['openid']);
        // 记录缓存, 7天
//        Cache::set($this->token, $session, 86400 * 7);
        return $session;
    }

    /**
     * 获取open_id
     * @param $params
     * @return mixed
     */
    public function get_open_id($params): string
    {
        try {
            return $this->wxlogin($params['code'])['openid'];
        } catch (BaseException $e) {
            $this->setError($e->getMessage());
            return "";
        }
    }

    /**
     * 获取token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 微信登录
     * @param $code
     * @return array|mixed
     * @throws BaseException
     * @throws \think\exception\DbException
     */
    private function wxlogin($code)
    {
        // 获取当前小程序信息
        $wxConfig = (new WxappModel())->where(["wxapp_id" => 10001])->find();
        // var_dump($wxConfig->toArray());die;
//        $wxConfig = Wxapp::getWxappCache();
//        $wxConfig = [
//            "app_id"=>"wx6f105c89328887bd",
//            "app_secret"=>"b078da6d4336dfdab19d2d23793987a5"
//        ];
        // 验证appid和appsecret是否填写
        if (empty($wxConfig['app_id']) || empty($wxConfig['app_secret'])) {
            throw new BaseException(['msg' => '请到 [后台-小程序设置] 填写appid 和 appsecret']);
        }
        // 微信登录 (获取session_key)
        // $wxConfig['app_id'] = 'wx536ee369aea8e866';
        $WxUser = new WxUserLib($wxConfig['app_id'], $wxConfig['app_secret']);
        if (!$session = $WxUser->sessionKey($code)) {
            throw new BaseException(['msg' => $WxUser->getError()]);
        }
        return $session;
    }

    /**
     * 生成用户认证的token
     * @param $openid
     * @return string
     */
    private function token($openid)
    {
        $wxapp_id = 10001;
        // 生成一个不会重复的随机字符串
        $guid = \getGuidV4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = 'token_salt';
        return md5("{$wxapp_id}_{$timeStamp}_{$openid}_{$guid}_{$salt}");
    }

    /**
     * 自动注册用户
     * @param $open_id
     * @param $data
     * @param int $refereeId
     * @return mixed
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    private function register($open_id, $data, $refereeId = null)
    {
        // 查询用户是否已存在
        $user = self::where(['open_id' => $open_id])->find();
        $model = $user ?: $this;
        $this->startTrans();
        try {
            // 保存/更新用户记录
            if (!$model->save(array_merge($data, [
                'open_id' => $open_id,
                'wxapp_id' => 10001
            ]))) {
                throw new BaseException(['msg' => '用户注册失败']);
            }
//            // 记录推荐人关系
//            if (!$user && $refereeId > 0 ) {
//                RefereeModel::createRelation($model['user_id'], $refereeId);
//            }
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return $model['user_id'];
    }
}