<?php


namespace app\web\controller;

use think\Request;
use app\web\model\PurchaseReject as PurchaseRejectModel;
use app\web\validate\PurchaseRejectOrder as PurchaseRejectOrderValidate;

class PurchaseReject extends Controller
{

    /**
     * @desc  生成采购退货单
     * @return array
     * Date: 2021/5/28
     * Time: 15:43
     */
    public function create()
    {
        $data = $this->request->post();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('create')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $res = $model->updateRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('保存失败'));
        }
        return $this->renderSuccess(['orders_code' => $res], '保存成功');
    }

    /**
     * @desc  采购退货单详情
     * @return array
     * Date: 2021/5/28
     * Time: 15:43
     */
    public function details()
    {
        $data = $this->request->param();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('detail')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $data = $model->getRejectOrderDetail($this->getData($data));
        if (!empty($data['settlement_name'])) {
            $data['settlement_name'] = '';
        }
        if (!$data) {
            return $this->renderError($model->getError('查询失败'));
        }
        return $this->renderSuccess( $data);
    }
    /**
     * @desc  删除采购退货单
     * @return array
     * Date: 2021/5/28
     * Time: 17:43
     */
    public function delete()
    {
        $data = $this->request->get();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $res = $model->delRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * @desc  采购退货单列表列表展示
     * @return array
     * Date: 2021/5/28
     * Time: 15:43
     */
    public function getList()
    {
        $model = new PurchaseRejectModel();
        $data = $this->request->param();
        $res = $model->getPageList($this->getData($data));
        return $this->renderSuccess($res);
    }
}