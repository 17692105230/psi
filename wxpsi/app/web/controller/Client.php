<?php

namespace app\web\controller;

use app\Request;
use app\web\model\ClientBase as ClientBaseModel;
use app\web\model\MemberBase as MemberBaseModel;
use app\web\model\SaleOrders;
use app\web\validate\ClientBase as ClientBaseValiedate;
use app\web\model\ClientAccount as ClientAccountModel;

use app\common\utils\ValidateHelper as ValidateHelper;
use think\facade\Config;

/**
 * 客户管理
 * Class Client
 * @package app\web\controller
 */
class Client extends Controller
{
    /**
     * 删除客户
     * @param Request $request
     * @return array
     */
    public function delClient(Request $request)
    {
        $model = new ClientBaseModel();
        $lockVersion = $request->get('lock_version');
        $clientId = $request->get('client_id');
        if (!$clientId) {
            return $this->renderError("参数错误");
        }
        $row = $model->getOne(['client_id' => $clientId, 'com_id' => $this->com_id]);
        $client = json_decode($row, true);
        if (empty($client)) {
            return $this->renderError("客户不存在");
        }
        if ($row['lock_version'] != $lockVersion) {
            return $this->renderError("删除内容不是最新版本");
        }
        // 查询是否存在销售单
        $saleModel = new SaleOrders();
        $saleData = $saleModel->getOne(['client_id' => $clientId]);
        $saleResult = json_decode($saleData, true);
        if (!empty($saleResult)) {
            return $this->renderError("该客户存在销售单，不能删除");
        }
        // 删除账户记录
        $accountModel = new ClientAccountModel();
        $accountOk = $accountModel->where(['client_id' => $clientId])->delete();
        if (!$accountOk) {
            return $this->renderError("账户信息删除失败");
        }
        $ok = $model->where(['client_id' => $clientId])->delete();
        if (!$ok) {
            return $this->renderError("删除失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 获取客户列表
     * zll
     * Date: 2021/2/3
     * Time: 15:21
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $searchData = $this->request->get();
        $model = new ClientBaseModel();
        $list = $model->where('com_id', $this->com_id)
            ->when((isset($searchData['search_data'])), function ($query) use ($searchData) {
                return $query->where(function ($query_child) use ($searchData) {
                    return $query_child
                        ->whereOr("client_phone", "like", "%" . $searchData['search_data'] . "%")
                        ->whereOr("client_name", "like", "%" . $searchData['search_data'] . "%");
                });
            })
            ->select();
        $clientList = json_decode($list, true);
        if (!$clientList) {
            return $this->renderSuccess();
        }
        // 查询账户余额
        $accountModel = new ClientAccountModel();
        $clientIds = array_unique(array_column($clientList, 'client_id'));
        $fields = "client_id,account_id,account_money";
        $accountData = $accountModel->getListByWhereIn('client_id', implode(',', $clientIds), $fields);
        $accountList = array_column(json_decode($accountData, true), null, 'client_id');
        $result = array_map(function ($client) use ($accountList) {
            $client['account'] = null;
            if (isset($accountList[$client['client_id']])) {
                $client['account'] = $accountList[$client['client_id']];
            }
            return $client;
        }, $clientList);
        return $this->renderSuccess($result);
    }

    /**
     * 客户详情
     * @param Request $request
     * @return array
     */
    public function clientDetail(Request $request)
    {
        $model = new ClientBaseModel();
        $clientId = $request->get('client_id');
        if (!$clientId) {
            return $this->renderError("参数错误");
        }
        $row = $model->getOne(['client_id' => $clientId, 'com_id' => $this->com_id]);
        $client = json_decode($row, true);
        if (empty($client)) {
            return $this->renderError("客户不存在");
        }
        // 查询账户余额
        $accountModel = new ClientAccountModel();
        $fields = "client_id,account_id,account_money";
        $accountData = $accountModel->getOne(['client_id' => $clientId], $fields);
        $account = json_decode($accountData, true);
        $client['account'] = $account ?: null;
        return $this->renderSuccess($client);
    }

    /**
     * 添加编辑客户
     * @param Request $request
     * @return array
     */
    public function addClient(Request $request)
    {
        $params = $request->post();
        $validate = new ClientBaseValiedate();
        if (!$validate->scene('save')->check($params)) {
            return $this->renderError($validate->getError());
        }
        if (!ValidateHelper::isEmail($params['client_email'])) {
            return $this->renderError("邮箱格式不正确", 400);
        }
        $clientModel = new ClientBaseModel();
        $clientId = intval($params['client_id']);

        $accountMoney = $params['account_money'];
        $token = $params['token'];
        $wxapp_id = $params['wxapp_id'];
        unset($params['account_money'], $params['token'], $params['wxapp_id']);

        $accountModel = new ClientAccountModel();
        if ($clientId) {
            // 更新
            $client = $clientModel->getOne(['client_id' => $clientId])->toArray();
            if (empty($client)) {
                return $this->renderError($clientModel->getError(), 401);
            }
            if ($client['lock_version'] != $params['lock_version']) {
                return $this->renderError("更新内容不是最新版本", 401);
            }
            //查询手机号是否存在
            $phoneExist = $clientModel->getOne(
                [
                    ['client_phone', "=", $params['client_phone']],
                    ['client_id', "<>", $clientId],
                ]
            );
            if ($phoneExist) {
                return $this->renderError("联系电话已存在", 403);
            }
            $params['lock_version'] += 1;
            $isOk = $clientModel->where(['client_id' => $clientId])->update(array_merge(['update_time' => time()], $params));
            if (!$isOk) {
                return $this->renderError($clientModel->getError(), 402);
            }
            return $this->renderSuccess();
        }
        // 查询client_code是否存在0
        $codeExist = $clientModel->getOne(['client_code' => $params['client_code']]);
        if ($codeExist) {
            return $this->renderError("客户编号已存在", 403);
        }
        //查询手机号是否存在
        $phoneExist = $clientModel->getOne(['client_phone' => $params['client_phone']]);
        if ($phoneExist) {
            return $this->renderError("联系电话已存在", 403);
        }
        // 新增base记录
        $clientId = $clientModel->insert(array_merge($params, ['create_time' => time(), 'com_id' => $this->com_id]), true);
        if (!$clientId) {
            return $this->renderError($clientModel->getError(), 403);
        }
        // 客户id不存在 则新增account记录
        $accountOk = $accountModel->insert([
            'client_id' => $clientId,
            'account_money' => $accountMoney,
            'create_time' => time(),
            'com_id' => $this->com_id,
        ]);
        if (!$accountOk) {
            return $this->renderError($accountModel->getError(), 404);
        }
        return $this->renderSuccess();
    }

    /**
     * 会员零售单接口
     * @param Request $request
     * @return mixed
     */
    public function sale_orders(Request $request)
    {
        $member_id = intval($request->get('member_id'));
        $sale_order_model = new SaleOrders();
        $where = [
            'orders_type' => $sale_order_model::ORDERS_ORDER_TYPE_RETAIL
        ];
        $result = [];
        if ($member_id) {
            // 查询会员信息
            $base_model = new \app\web\model\MemberBase();
            $base = $base_model->getOne(['member_id' => $member_id]);
            if (!$base) {
                return $this->renderJson("会员不存在");
            }
            $account_model = new \app\web\model\MemberAccount();
            $account = $account_model->getOne(['member_code' => $base['member_code']]);
            $result['base'] = $base;
            $result['account'] = $account;

            $where['member_id'] = $member_id;
        }
        $result = array_merge($sale_order_model->easyPaginate($where), $result);
        return $this->renderSuccess($result);
    }


}