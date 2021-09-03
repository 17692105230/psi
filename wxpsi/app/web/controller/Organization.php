<?php


namespace app\web\controller;

use app\Request;
use app\web\model\GoodsStock;
use app\web\model\Organization as OrganizationModel;
use app\web\validate\Organization as OrganizationValidate;
use app\web\model\PurchaseOrders as PurchaseOrders;
use app\web\model\SaleOrders as SaleOrdersModel;
use app\web\model\StockDiary as StockDiaryModel;
use app\common\utils\ValidateHelper as ValidateHelper;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class Organization extends Controller
{
    protected $org_type = 2;

    /**
     * 组织机构列表
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        $params = $request->get();
        $org_type = isset($params['org_type']) ? $params['org_type'] : ''; // 仓库类型
        $organization_model = new OrganizationModel();
        $where = [
            'com_id' => $this->com_id
        ];
        if (isset($params['org_type']) && $org_type != "") {
            $where['org_type'] = intval($org_type);
        }
        $list = $organization_model->getList($where);
        return $this->renderSuccess($list);
    }

    /**
     * 添加组织机构
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        $params = $request->post();
        $org_name = trim($params['org_name']);
        $org_head = trim($params['org_head']); // 组织机构负责人
        $org_phone = trim($params['org_phone']); // 联系电话
        $org_type = intval($params['org_type']); // 组织机构类型
        foreach (["org_name", "org_head", "org_phone"] as $name) {
            // 判断参数不能为空
            if (!${$name}) {
                return $this->renderError("{$name}不能为空");
            }
        }
        $validate_helper = new ValidateHelper();
        if (!$validate_helper::isMobile($org_phone)) {
            return $this->renderError("手机号码格式不正确");
        }
        if (!$validate_helper::isName($org_head)) {
            return $this->renderError("组织机构负责人格式错误");
        }
        $organization_model = new OrganizationModel();
        if (!in_array($org_type, [
            $organization_model::ORG_TYPE_INSIDE,
            $organization_model::ORG_TYPE_OUTSIDE,
            $organization_model::ORG_TYPE_WAREHOUSE
        ])) {
            return $this->renderError("组织机构类型错误");
        }
        $organization_model = new OrganizationModel();
        $ok = $organization_model->insert([
            'org_pid' => intval($params['org_pid']),
            'org_name' => trim($params['org_name']),
            'org_head' => trim($params['org_head']),
            'org_phone' => trim($params['org_phone']),
            'org_type' => intval($params['org_type']),
            'create_time' => time(),
            'update_time' => time(),
            'com_id' => $this->com_id
        ]);
        if (!$ok) {
            return $this->renderError("添加失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 详情
     * @param Request $request
     * @return mixed
     */
    public function detail(Request $request)
    {
        $params = $request->get();
        $org_id = intval($params['org_id']);
        $organization_model = new OrganizationModel();
        $organization = $organization_model->getOne(['com_id' => intval($this->com_id), 'org_id' => $org_id]);
        if (!$organization) {
            return $this->renderError("该组织机构不存在");
        }
        $org_pid = $organization['org_pid'];
        $result = [
            'org' => $organization
        ];
        if ($org_pid) {
            $result['org_parent'] = $organization_model->getOne(['org_id' => $org_pid]);
        }
        // 查询该企业下的组织机构
        try {
            $select = $organization_model->getList([
                ['com_id', '=', $this->com_id], // 本企业
                ['org_id', '<>', $org_id], // 过滤掉自己
                ['org_pid', '<>', $org_id], // 过滤掉子组织
            ]);
            if ($select) {
                $result['select'] = $select;
            }
            return $this->renderSuccess($result);
        } catch (DataNotFoundException | ModelNotFoundException | DbException $e) {
            return $this->renderError($e->getMessage());
        }
    }

    /**
     * 编辑
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        $params = $request->post();
        $org_name = trim($params['org_name']);
        $org_head = trim($params['org_head']); // 组织机构负责人
        $org_phone = trim($params['org_phone']); // 联系电话
        $org_type = intval($params['org_type']); // 组织机构类型
        foreach (["org_name", "org_head", "org_phone"] as $name) {
            // 判断参数不能为空
            if (!${$name}) {
                return $this->renderError("{$name}不能为空");
            }
        }
        $validate_helper = new ValidateHelper();
        if (!$validate_helper::isMobile($org_phone)) {
            return $this->renderError("手机号码格式不正确");
        }
        if (!$validate_helper::isName($org_head)) {
            return $this->renderError("组织机构负责人格式错误");
        }
        $organization_model = new OrganizationModel();
        if (!in_array($org_type, [
            $organization_model::ORG_TYPE_INSIDE,
            $organization_model::ORG_TYPE_OUTSIDE,
            $organization_model::ORG_TYPE_WAREHOUSE
        ])) {
            return $this->renderError("组织机构类型错误");
        }
        $org_id = intval($params['org_id']);
        $organization_model = new OrganizationModel();
        $organization = $organization_model->getOne(['org_id' => $org_id]);
        if (!$organization) {
            return $this->renderError("组织机构不存在");
        }
        $ok = $organization_model->update([
            'org_pid' => intval($params['org_pid']),
            'org_name' => trim($params['org_name']),
            'org_head' => trim($params['org_head']),
            'org_phone' => trim($params['org_phone']),
            'org_type' => intval($params['org_type']),
            'update_time' => time(),
        ], ['org_id' => $org_id]);
        if (!$ok) {
            return $this->renderError("编辑失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 删除
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request)
    {
        $params = $request->post();
        $org_id = intval($params['org_id']);
        $organization_model = new OrganizationModel();
        $organization = $organization_model->getOne(['org_id' => $org_id, 'com_id' => $this->com_id]);
        if (!$organization) {
            return $this->renderError("组织机构不存在");
        }
        $children_organization = $organization_model->getOne(['org_pid' => $org_id, 'com_id' => $this->com_id]);
        if ($children_organization) {
            return $this->renderError("该机构已存在子机构,不能删除");
        }
        $goods_stock_model = new GoodsStock();
        $stock = $goods_stock_model->getOne(['warehouse_id' => $org_id]);
        if ($stock) {
            return $this->renderError("该组织机构已存在商品不能删除");
        }
        $ok = $organization_model->delByWhere(['org_id' => $org_id]);
        if (!$ok) {
            return $this->renderError("删除失败");
        }
        return $this->renderSuccess();
    }

    /**
     * 函数描述:获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/8
     * Time: 15:55
     * @author mll
     */
    public function getList()
    {
        $orgModel = new OrganizationModel();
        $where = [
            ["com_id", "=", $this->com_id],
        ];
        if ($this->org_ids) {
            array_push($where, ['org_id', 'in', $this->org_ids]);
        }
        $res = $orgModel->getList($where);
        if (!$res) {
            return $this->renderError('查询出错');
        }
        return $this->renderSuccess($res, '查询成功');
    }

    /**
     * 函数描述: 添加仓库
     * @param Request $request
     * Date: 2021/1/8
     * Time: 15:55
     * @author mll
     */
    public function saveOrg(Request $request)
    {
        $data = $request->post();
        $orgModel = new OrganizationModel();
        $orgValidate = new OrganizationValidate();
        if (!$orgValidate->scene('add')->check($data)) {
            return $this->renderError($orgValidate->getError());
        }
        $save_data = [
            'org_name' => $data['org_name'],
            'org_head' => $data['org_head'],
            'org_phone' => $data['org_phone'],
            'org_type' => $this->org_type,
            'com_id' => $this->com_id
        ];
        $res = $orgModel->save($save_data);
        if (!$res) {
            return $this->renderError('添加失败');
        }
        return $this->renderSuccess('', '添加成功');

    }

    /**
     * 函数描述:仓库详情回显
     * @param Request $request
     * @return array
     * Date: 2021/1/11
     * Time: 15:13
     * @author mll
     */
    public function loadOrgInfo(Request $request)
    {
        $data = $request->get();
        $orgModel = new OrganizationModel();
        $orgValidate = new OrganizationValidate();
        if (!$orgValidate->scene('del')->check($data)) {
            return $this->renderError($orgValidate->getError());
        }
        $lock_where = ['com_id' => $this->com_id, 'org_id' => $data['org_id']];
        $lock = $orgModel->where($lock_where)->find();
        if (empty($lock)) {
            return $this->renderError('未找到相对应版本');
        }
        if (!$lock['lock_version'] == $data['lock_version']) {
            return $this->renderError('版本信息不对应，无数据');
        }
        $res = $orgModel->where(['com_id' => $this->com_id, 'org_id' => $data['org_id']])->find();
        if (!$res) {
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res, '查询成功');

    }

    /**
     * 函数描述:修改仓库信息
     * @param Request $request
     * Date: 2021/1/8
     * Time: 16:03
     * @return array
     * @author mll
     */
    public function editOrg(Request $request)
    {
        $data = $request->post();
        $orgModel = new OrganizationModel();
        $orgValidate = new OrganizationValidate();
        //采购单
        if (!$orgValidate->scene('edit')->check($data)) {
            return $this->renderError($orgValidate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'org_type' => $this->org_type,
            'org_id' => $data['org_id']
        ];
        $lock = $orgModel->where($lock_where)->find();
        if ($lock['lock_version'] != $data['lock_version']) {
            return $this->renderError('版本信息不匹配');
        }

        $where = [
            'org_id' => $data['org_id'],
            'com_id' => $this->com_id,
            'org_type' => $this->org_type
        ];
        $update = [
            'org_name' => $data['org_name'],
            'org_head' => $data['org_head'],
            'org_phone' => $data['org_phone'],
            'lock_version' => $data['lock_version'] + 1
        ];
        $res = $orgModel->where($where)->save($update);
        if (!$res) {
            return $this->renderError('修改出错');
        }
        return $this->renderSuccess('', '修改成功');
    }

    /**
     * 函数描述:删除仓库
     * @param Request $request
     * @return array
     * Date: 2021/1/8
     * Time: 16:39
     * @author mll
     */
    public function delOrg(Request $request)
    {
        $data = $request->post();
        $orgModel = new OrganizationModel();
        $orgValidate = new OrganizationValidate();
        if (!$orgValidate->scene('del')->check($data)) {
            return $this->renderError($orgValidate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'org_id' => $data['org_id'],
            'org_type' => $this->org_type
        ];
        $lock = $orgModel->where($lock_where)->find();
        if (!$lock['lock_version'] == $data['lock_version']) {
            return $this->renderError('版本不匹配');
        }
        //删除仓库时候，需要判断仓库是否在单据中使用过。如果有则返回：无法删除仓库
        //  查询采购单
        $purchaseModel = new PurchaseOrders();
        $pur = $purchaseModel->where(['warehouse_id' => $data['org_id'], 'com_id' => $this->com_id])->find();
        if ($pur) {
            return $this->renderError('此仓库已被采购单使用，无法删除');
        }
        //  查询销售单
        $saleModel = new SaleOrdersModel();
        $sale = $saleModel->where(['warehouse_id' => $data['org_id'], 'com_id' => $this->com_id])->find();
        if ($sale) {
            return $this->renderError('此仓库已被销售单使用，无法删除');
        }
        //  查询库存
        $stockModel = new StockDiaryModel();
        $stock = $stockModel->where(['warehouse_id' => $data['org_id'], 'com_id' => $this->com_id])->find();
        if ($stock) {
            if ($stock['stock_number'] > 0) {
                return $this->renderError('此仓库中还有库存，无法删除');
            }
        }

        $where = [
            'com_id' => $this->com_id,
            'org_type' => $this->org_type,
            'org_id' => $data['org_id']
        ];
        $res = $orgModel->where($where)->delete();
        if (!$res) {
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('', '删除成功');
    }
}