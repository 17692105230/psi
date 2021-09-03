<?php


namespace app\web\model;

use app\common\model\web\User as UserModel;
use app\common\utils\BuildCode as BuildCodeUtil;
use think\facade\Cache;
use think\cache\driver\Redis;

class User extends UserModel
{
    /** @var int 用户状态 开启 */
    public const USER_STATUS_OPEN = 1;
    /** @var int 用户状态 关闭 */
    public const USER_STATUS_CLOSE = -1;

    /**
     * 生成密码
     * @param $password string
     * @return array
     */
    public function make_password(string $password): array
    {
        $pass_key = BuildCodeUtil::getRandomString();
        return [
            'pass_key' => $pass_key,
            'user_login_password' => md5($pass_key . $password)
        ];
    }

    /**
     * 验证密码是否正确
     * @param $user_name string 用户名
     * @param $password string 密码
     * @return bool
     */
    public function compare_password(string $user_name, string $password): bool
    {
        $user = $this->getOne(['user_name' => $user_name]);
        if (!$user) {
            return false;
        }
        $md5 = md5($user['pass_key'] . $password);
        if ($md5 != $user['user_login_password']) {
            return false;
        }
        return true;
    }

    /**
     * 保存token
     * @param $user array
     * @return string
     */
    public function save_token(array $user): string
    {
        $redis = new Redis();
        $expire = 24 * 3600;
        $token = md5(uniqid($user['user_id']));
        $old_token = $redis->get("user:{$user['user_id']}:token");
        $redis->delete("user:token:{$old_token}");
        $redis->set("user:{$user['user_id']}:token", $token, $expire);
        $redis->set("user:token:{$token}", $user['user_id'], $expire);
        return $token;
    }

    /**
     * 获取token
     * @param $user array
     * @return mixed
     */
    public function get_token(array $user)
    {
        $redis = new Redis();
        return $redis->get("user:{$user['user_id']}:token");
    }

    /**
     * 根据token获取用户信息
     * @param $token string
     * @return array
     */
    public function get_user_by_token(string $token): array
    {
        $redis = new Redis();
        $user_id = $redis->get("user:token:{$token}");
        if (!$user_id) {
            return [];
        }
        return json_decode($this->getOne(['user_id' => $user_id]), true);
    }
}