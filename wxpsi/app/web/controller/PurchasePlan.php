<?php


namespace app\web\controller;


use think\Request;
use app\common\utils\Compare;
use app\web\validate\PurchasePlan as PurchasePlanValidate;
use app\web\model\PurchasePlan as PurchasePlanModel;
use think\facade\Db;

class PurchasePlan extends Controller
{


    /**
     * @desc 创建采购单
     * @return array
     */
    public function create()
    {
        $data = $this->getData($this->request->post());
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('create')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $res = $model->updateOrder($data);
        if ($res) {
            return $this->renderSuccess([], '创建成功');
        }
        return $this->renderError($model->getError());
    }

    /**
     * @desc 查看单据详情
     * @return array
     */
    public function details()
    {
        $data = $this->getData($this->request->get());
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('details')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $res = $model->getPlanDetail($data);
        if ($res) {
            return $this->renderSuccess($res, '查询成功');
        }

        return $this->renderError('单据不存在');

    }

    /**
     * @desc 采购定单列表页
     * @return array
     */
    public function loadList()
    {
        $purchase_plan_model = new PurchasePlanModel();
        $data = $this->request->get();
        $result = $purchase_plan_model->getPageList($this->getData($data));
        return $this->renderSuccess($result);
    }

    /**
     * @desc 删除单据
     * @return array
     */
    public function delete()
    {
        $data = $this->request->get();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $res = $model->delPlan($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * @desc 更新单据
     * @return array
     */
    public function update()
    {
        $data = $this->getData($this->request->post());
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $res = $model->updateOrder($data);
        if ($res) {
            return $this->renderSuccess([], '修改成功');
        }
        return $this->renderError($model->getError());

    }


    /**
     * @desc 保存采购订单草稿
     * @return array
     */
    public function savePlanRoughDraft()
    {
        $data = $this->request->post();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $data['orders_status'] = 0;
        $res = $model->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('保存失败'));
        }
        $orders = $model->getPlanDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * @desc 提交采购订单
     * @return array
     */
    public function savePlanFormally()
    {
        $data = $this->request->post();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $data['orders_status'] = 9;
        $data["user_id"] = $this->user["user_id"];
        $res = $model->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError("保存失败"));
        }
        $orders = $model->getPlanDetail($this->getData(["orders_code" => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    // xxx
    public function save()
    {

    }


    /**
     * @desc  新老数据对比 demo
     * Date: 2021/5/19
     * Time: 14:37
     */
    public function compareDemo()
    {
        // 原始数据
        $originData = [
            ['goods_id' => 1, 'size_id' => 1, 'color_id' => 1, 'number' => 1],
            ['goods_id' => 1, 'size_id' => 1, 'color_id' => 2, 'number' => 2],
            ['goods_id' => 1, 'size_id' => 1, 'color_id' => 3, 'number' => 3],
        ];
        // 新数据
        $newData = [
            ['goods_id' => 1, 'size_id' => 1, 'color_id' => 2, 'number' => 10],
            ['goods_id' => 1, 'size_id' => 1, 'color_id' => 4, 'number' => 10],

        ];
        // 唯一性字段
        $map = ['goods_id', 'size_id', 'color_id'];
        $compare = new Compare($originData, $newData, $map);
        $compare->handle();
        $compare->getAllResult($addResult, $updateResult, $delResult);
        dump($updateResult);
        dump($addResult);
        dump($delResult);
    }


}