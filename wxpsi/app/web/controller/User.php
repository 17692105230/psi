<?php


namespace app\web\controller;

use app\web\model\Company;
use app\web\model\Role;
use app\web\model\WXPowerNode;
use app\web\model\WxUser as WxUserModel;
use app\Request;
use app\web\model\User as UserModel;
use app\web\validate\User as UserValidate;
use app\web\validate\CompanyValidate as CompanyValidate;
use app\common\utils\ValidateHelper as ValidateHelper;
use app\common\utils\ToolHelper as ToolHelper;

use app\web\model\Company as CompanyModel;

class User extends Controller
{

    /**
     * 注册
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $params = $request->post();
        $companyValidate = new CompanyValidate();
        if (!$companyValidate->scene("add")->check($params)) {
            return $this->renderError($companyValidate->getError());
        }
        if (!ValidateHelper::isMobile($params['user_phone'])) {
            return $this->renderError("用户手机号格式不正确");
        }
        if (!ValidateHelper::isCreditNo($params['idcode'])) {
            return $this->renderError("用户证件号格式不正确");
        }
        if (!ValidateHelper::isGenericName($params['company_contact'])) {
            return $this->renderError("企业联系人格式不正确");
        }
        if (!ValidateHelper::isPasswd($params['password'])) {
            return $this->renderError("密码长度6~12");
        }
        $length = mb_strlen($params['user_name']);
        if ($length < 2 || $length > 12) {
            return $this->renderError("用户名长度2~12");
        }
        // 用户名是否存在
        $userModel = new UserModel();
        $exist = $userModel->getOne(['user_name' => $params['user_name']]);
        if ($exist) {
            return $this->renderError('用户名已存在');
        }
        $companyModel = new Company();
        $result = $companyModel->register_company($params);
        if (!$result) {
            return $this->renderError($companyModel->getError());
        }
        $result['user_phone'] = ToolHelper::phone_encryption($result['user_phone']);
        return $this->renderSuccess($result);
    }

    /**
     * 账号密码登录
     * @param Request $request
     * @return mixed
     */
    public function normal_login(Request $request)
    {
        $params = $request->get();
        $user_name = $params['user_name'];
        $password = $params['password'];
        if (!$user_name || !$password) {
            return $this->renderError("账号或密码格式错误");
        }
        $userModel = new UserModel();
        $ok = $userModel->compare_password($user_name, $password);
        if (!$ok) {
            return $this->renderError("账号或密码格式错误");
        }
        $fields = ['user_id', 'user_name', 'user_phone', 'user_idcode', 'com_id', 'open_id'];
        $user = $userModel->getOne(['user_name' => $user_name, 'user_status' => $userModel::USER_STATUS_OPEN], $fields);
        if (!$user) {
            return $this->renderError("登录失败");
        }
        $user = $user->toArray();
        $user['token'] = $userModel->save_token($user);
        $user['user_phone'] = ToolHelper::phone_encryption($user['user_phone']);
        // 更新登录信息
        $userModel->update([
            'user_last_time' => time(),
            'user_last_ip' => ToolHelper::get_ip(),
            'update_time' => time(),
        ], ['user_id' => $user['user_id']]);
        return $this->renderSuccess($user);
    }

    /**
     * 用户列表
     * @return mixed
     */
    public function user_list()
    {
        $userModel = new UserModel();
        $fields = ['user_id', 'user_name', 'user_phone', 'user_idcode', 'com_id', 'user_story'];
        $res = $userModel->getList(['com_id' => $this->com_id], $fields);
        return $this->renderSuccess($res);
    }

    /**
     * 用户详情
     * @param Request $request
     * @return mixed
     */
    public function user_detail(Request $request)
    {
        $params = $request->get();
        $user_id = intval($params['user_id']);
        if (!$user_id) {
            return $this->renderError("参数错误");
        }
        $userModel = new UserModel();
        $fields = ['user_id', 'user_name', 'user_phone', 'user_idcode', 'com_id', 'user_story', 'org_id', 'user_status', 'user_role_ids'];
        $user = $userModel->getOne(['user_id' => $user_id], $fields);
        if (!$user) {
            return $this->renderError("用户不存在");
        }
        $company_model = new Company();
        $company = $company_model->getOne(['user_id' => $user['user_id']]);
        $is_modify = true;
        if ($company) {
            $is_modify = false;
        }
        $user['is_modify'] = $is_modify;
        $user['org_id'] = $user['org_id'] ? array_filter(explode(',', $user['org_id'])) : [];
        $user['user_role_ids'] = $user['user_role_ids'] ? array_filter(explode(',', $user['user_role_ids'])) : [];
        return $this->renderSuccess($user);
    }

    /**
     * 添加
     * @param Request $request 5151700410
     * @return mixed
     */
    public function add_user(Request $request)
    {
        $params = $request->post();
        $user_name = trim($params['user_name']); // 用户名
        $password = trim($params['password']); // 密码
        $user_phone = trim($params['user_phone']); // 手机号
        $user_idcode = trim($params['idcode']); // 用户证据号
        $user_role_ids = json_decode($params['role_ids'], true); // 角色
        $org_id = $params['org_id']; // 组织机构id
        $user_story = $params['user_story']; // 用户描述
        $user_status = intval($params['user_status']); // 用户状态
        foreach (['user_name', 'password', 'user_phone', 'user_idcode', 'user_role_ids', 'org_id'] as $name) {
            if (!${$name}) {
                return $this->renderError("参数{$name}不能为空");
            }
        }
        if (!ValidateHelper::isMobile($params['user_phone'])) {
            return $this->renderError("用户手机号格式不正确");
        }

        if (!ValidateHelper::isCreditNo($params['idcode'])) {
            return $this->renderError("用户证件号格式不正确");
        }
        if (!ValidateHelper::isPasswd($params['password'])) {
            return $this->renderError("密码格式6~12位");
        }
        $length = mb_strlen($user_name);
        if ($length < 2 || $length > 12) {
            return $this->renderError("用户名长度2~12");
        }
        $org_ids = json_decode($params['org_id'], true);
        $user_model = new UserModel();
        $ok = $user_model->insert(array_merge(
            [
                'user_name' => trim($params['user_name']),
                'user_phone' => trim($params['user_phone']),
                'user_idcode' => trim($params['idcode']),
                'user_role_ids' => ',' . implode(',', json_decode($params['role_ids'], true)) . ',',
                'org_id' => ',' . implode(',', $org_ids) . ',',
                'user_story' => trim($params['user_story']),
                'user_status' => $user_status,
                'com_id' => $this->com_id,
                'create_time' => time()
            ],
            $user_model->make_password($params['password'])
        ));
        if (!$ok) {
            return $this->renderError("添加失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 编辑用户
     * @param Request $request
     * @return mixed
     */
    public function edit_user(Request $request)
    {
        $params = $request->post();
        $user_name = trim($params['user_name']); // 用户名
        $password = trim($params['password']); // 密码
        $user_phone = trim($params['user_phone']); // 手机号
        $user_idcode = trim($params['idcode']); // 用户证据号
        $user_role_ids = json_decode($params['role_ids'], true); // 角色
        $org_id = $params['org_id']; // 组织机构id
        $user_story = $params['user_story']; // 用户描述
        $user_status = intval($params['user_status']); // 用户状态
        foreach (['user_name', 'user_phone', 'user_idcode', 'user_role_ids', 'org_id'] as $name) {
            if (!${$name}) {
                return $this->renderError("参数{$name}不能为空");
            }
        }
        if (!ValidateHelper::isMobile($params['user_phone'])) {
            return $this->renderError("用户手机号格式不正确");
        }
        if (!ValidateHelper::isCreditNo($params['idcode'])) {
            return $this->renderError("用户证件号格式不正确");
        }
        if ($params['password'] && !ValidateHelper::isPasswd($params['password'])) {
            return $this->renderError("密码格式6~12位");
        }
        $length = mb_strlen($user_name);
        if ($length < 2 || $length > 12) {
            return $this->renderError("用户名长度2~12");
        }
        $user_id = intval($params['user_id']);
        $org_ids = json_decode($params['org_id'], true);
        $user_model = new UserModel();
        $user = $user_model->getOne(['com_id' => intval($this->com_id), 'user_id' => $user_id]);
        if (!$user) {
            return $this->renderError("用户不存在");
        }
        $update = [
            'user_name' => trim($params['user_name']),
            'user_phone' => trim($params['user_phone']),
            'user_idcode' => trim($params['idcode']),
            'user_role_ids' => ',' . implode(',', json_decode($params['role_ids'], true)) . ',',
            'user_status' => $user_status,
            'user_story' => trim($params['user_story']),
            'update_time' => time()
        ];
        if ($org_ids) {
            $update['org_id'] = ',' . implode(',', $org_ids) . ',';
        }
        if ($password) {
            $update = array_merge($update, $user_model->make_password($params['password']));
        }
        $ok = $user_model->update($update, ['user_id' => $user_id]);
        if (!$ok) {
            return $this->renderError("编辑失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 删除用户
     * @param Request $request
     * @return mixed
     */
    public function delete_user(Request $request)
    {
        $params = $request->post();
        $user_id = intval($params['user_id']);
        if (!$user_id) {
            return $this->renderError("参数错误");
        }
        $user_model = new UserModel();
        $user = $user_model->getOne(['user_id' => $user_id, 'com_id' => $this->com_id]);
        if (!$user) {
            return $this->renderError("该用户不存在");
        }
        if (in_array(Role::SUPER_ADMIN_ROLE_ID, array_filter(explode(',', $user['user_role_ids'])))) {
            // 如果是超级管理员则不能删除
            return $this->renderError("该账户不能删除");
        }
        $ok = $user_model->update([
            'user_status' => $user_model::USER_STATUS_CLOSE,
            'update_time' => time()
        ], ['user_id' => $user_id]);
        if (!$ok) {
            return $this->renderError("删除失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 小程序进入判断是否已经授权
     * @param Request $request
     * @return mixed
     */
    public function is_authorize(Request $request)
    {
        $params = $request->post();
        $model = new WxUserModel();
        $open_id = $model->get_open_id($params);
        if (!$open_id) {
            return $this->renderSuccess();
        }
        $userModel = new UserModel();
        $fields = ['user_id', 'user_name', 'user_phone', 'user_idcode', 'com_id', 'open_id'];
        $user = $userModel->getOne(['open_id' => $open_id, 'user_status' => $userModel::USER_STATUS_OPEN], $fields);
        if (!$user) {
            return $this->renderSuccess();
        }
        $user = $user->toArray();
        $user['token'] = $userModel->save_token($user);
        // 更新登录信息
        $userModel->update([
            'user_last_time' => time(),
            'user_last_ip' => ToolHelper::get_ip(),
            'update_time' => time(),
        ], ['user_id' => $user['user_id']]);
        return $this->renderSuccess($user);
    }

    /**
     * 授权
     * @param Request $request
     * @return mixed
     */
    public function authorize(Request $request)
    {
        $params = $request->post();
        $model = new WxUserModel();
        $open_id = $model->get_open_id($params);
        if (!$open_id) {
            return $this->renderError($model->getError());
        }
        $user_info = json_decode($params['user_info'], true);
        $user_model = new UserModel();
        $open_id_user = $user_model->getOne(['open_id' => $open_id]);
        if ($open_id_user) {
            return $this->renderError("该微信已绑定其它账号,授权失败");
        }
        $user_id = $params['user_id']; // 用户id
        $model->delByWhere(['open_id' => $open_id]);
        $wx_ok = $model->insert([
            'user_id' => $user_id,
            'open_id' => $open_id,
            'nickName' => $user_info['nickName'],
            'avatarUrl' => $user_info['avatarUrl'],
            'gender' => $user_info['gender'],
            'country' => $user_info['country'],
            'province' => $user_info['province'],
            'wxapp_id' => $params['wxapp_id'],
        ]);
        if ($wx_ok) {
            $ok = $user_model->update([
                'open_id' => $open_id,
                'update_time' => time(),
            ], ['user_id' => $user_id]);
            if (!$ok) {
                return $this->renderError("授权失败");
            }
        }
        return $this->renderSuccess("授权成功");
    }

    /**
     * 用户自动登录
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $model = new WxUserModel;
        return $this->renderSuccess([
            'user_id' => $model->login($this->request->post()),
            'token' => $model->getToken()
        ]);

    }

    /**
     * 函数描述:用户注册
     * @param Request $request
     * @return array
     * Date: 2021/1/14
     * Time: 11:47
     * @author mll
     */
    public function registerUser(Request $request)
    {
        $data = $request->post();
        $userModel = new UserModel();
        $validate = new UserValidate();
        if (!$validate->scene('add')->check($data)) {
            return $this->renderError($validate->getError());
        }
        if (!$data['user_login_password'] === $data['user_login_password2']) {
            return $this->renderError('两次输入密码不一致');
        }
        $save_data = [
            'user_login_password' => $data['user_login_password'],
            'user_phone' => $data['user_phone']
        ];
        $res = $userModel->save($save_data);
        if (!$res) {
            return $this->renderError('注册失败');
        }
        return $this->renderSuccess('', '注册成功');
    }

    //获取销售员列表
    public function getList()
    {
        $model = new UserModel();
        $list = $model->where('com_id', $this->com_id)->select();
        return $this->renderSuccess($list);
    }
}