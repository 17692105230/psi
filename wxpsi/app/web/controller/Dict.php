<?php


namespace app\web\controller;

use app\web\validate\Dict as DictValidate;
use app\web\model\Dict as DictModel;

class Dict extends Controller
{
    protected $dictValidate;
    protected $model;

    public function initialize()
    {
        parent::initialize();
        $this->dictValidate = new DictValidate();
        $this->model = new DictModel();
    }


    public function add()
    {
        $data = $this->request->post();
        // 验证
        if (!$this->dictValidate->scene('add')->check($data)) {
            return $this->renderError($this->dictValidate->getError());
        }
        // model add
        unset($data['dict_id']);
        $result = $this->model->save($this->getData($data));
        if ($result) {
            return $this->renderSuccess();
        }

        return $this->renderError($this->model->getError() ?: '添加失败');
    }

    public function delete()
    {
        $data = $this->request->post();
        // 验证
        if (!$this->dictValidate->scene('del')->check($data)) {
            return $this->renderError($this->dictValidate->getError());
        }
        // model delete
        $result = $this->model->del($this->getData($data));
        if ($result) {
            return $this->renderSuccess();
        }

        return $this->renderError($this->model->getError() ?: '删除失败');
    }

    public function edit()
    {
        $data = $this->request->post();
        // 验证
        if (!$this->dictValidate->scene('edit')->check($data)) {
            return $this->renderError($this->dictValidate->getError());
        }
        // model edit
        $result = $this->model->edit($this->getData($data));
        if ($result) {
            return $this->renderSuccess();
        }

        return $this->renderError($this->model->getError() ?: '修改失败');
    }

    public function loadList()
    {
        $dict_type = $this->request->param('dict_type');
        $data = $this->model->getList($this->getData(['dict_type' => $dict_type]),'*', 'sort asc');
        return json($data);
    }

    public function loadTreeData()
    {
        $dict_type = $this->request->param('dict_type');
        $data = $this->model->getList($this->getData(['dict_type' => $dict_type]),'dict_id as id,dict_pid as pid,dict_name as text,dict_disabled,lock_version', 'sort asc');
        return json(createTree($data->toArray()));
    }

    /**
     * 函数描述:其他费用列表
     * @return \think\response\Json
     * Date: 2021/1/27
     * Time: 11:33
     * @author mll
     */
    public function loadAccountListY()
    {
        $list = $this->model->getList(['dict_type' => 'account','dict_value'=>'Y'], '*', 'sort asc');
        return $this->renderSuccess($list,'查询成功');
    }

    //获取发货方式
    public function getDeliveryList(){
        $list = $this->model->getList(['dict_type' => 'delivery'], '*', 'sort asc');
        return $this->renderSuccess($list,'查询成功');
    }

}