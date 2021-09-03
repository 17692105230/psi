<?php


namespace app\web\controller;


use app\BaseController;
use app\Request;
use \app\web\model\Color as ColorModel;
use app\web\model\Goods as GoodsModel;
use app\web\model\GoodsDetails;
use app\web\validate\Color as ColorValidate;
use think\db\Raw;

/** 颜色管理
 * Class Color
 * @package app\web\controller
 * Date: 2020/12/31
 * Time: 11:55
 * @author mll
 */
class Color extends Controller
{
    //定义color模型
    protected $colorModel;
    protected $colorValidate;

    //初始化
    public function initialize()
    {
        parent::initialize();
        $this->colorModel = new ColorModel();
        $this->colorValidate = new ColorValidate();
    }

    /**
     * 函数描述:查询颜色列表
     * @return \think\response\Json
     * Date: 2020/12/31
     * Time: 11:55
     * @author mll
     */
    public function loadColorList()
    {
        $where = [
            'com_id' => $this->com_id,
        ];
        $res = $this->colorModel->loadData($where);
        if ($res) {
            return $this->renderSuccess($res, '数据查询成功');
        }
        return $this->renderError('服务器出错');
    }

    /**
     * 函数描述:添加颜色
     * Date: 2021/1/7
     * Time: 11:14
     * @param Request $request
     * @return array
     * @author mll
     */
    public function saveColor(Request $request)
    {
        $data = $request->post();
        if (!$this->colorValidate->scene('add')->check($data)) {
            return $this->renderError($this->colorValidate->getError());
        }
        $where = [
            'color_name' => $data['color_name'],
            'color_code' => $data['color_code'],
            'com_id' => $this->com_id
        ];
        if ($this->colorModel->findDataByExample($where)) {
            return $this->renderError('颜色已经存在，请勿重复添加');
        }
        $res = $this->colorModel->saveColor($where);
        if (!$res){
            return  $this->renderError('添加颜色出错');
        }
        return $this->renderSuccess($res,'添加成功');
    }

    /**
     * 函数描述:修改颜色
     * @param Request $request
     * Date: 2021/1/7
     * Time: 17:23
     * @author mll
     */
    public function editColor(Request $request){
        $data = $request->post();
        if (!$this->colorValidate->scene('edit')->check($data)){
            return $this->renderError($this->colorValidate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'color_id' => $data['color_id']
        ];
        $lock = $this->colorModel->findDataByExample($lock_where);
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不正确，不可以修改');
        }
        $update = [
            'color_name' => $data['color_name'],
            'color_code' => $data['color_code'],
            'lock_version' => $data['lock_version']+1
        ];
        $where = [
            'com_id'=>$this->com_id,
            'color_id'=>$data['color_id']
        ];
        $res = $this->colorModel->editColor($where,$update);
        if (!$res){
            return $this->renderError('服务器错误');
        }
        return $this->renderSuccess('','修改成功');
    }

    /**
     * 函数描述:删除颜色
     * @param Request $request
     * Date: 2021/1/7
     * Time: 18:12
     * @author mll
     */
    public function delColor(Request $request){
        $data = $request->post();
        //验证
        if (!$this->colorValidate->scene('del')->check($data)){
            return $this->renderError($this->colorValidate->getError());
        }
        $color_id = $data['color_id'];
        $lock_version = $data['lock_version'];
        //先判断是否在商品中使用,如果存在则不允许删除
        $goodsModel = new GoodsDetails();
        $goods = $goodsModel->where(['color_id' => $color_id, 'com_id' => $this->com_id])->find();
        if ($goods){
            return $this->renderError('此分类已经使用，无法删除');
        }
        $lock = $this->colorModel->findDataByExample(['com_id' => $this->com_id,'color_id'=>$color_id]);
        if (!$lock['lock_version'] == $lock_version){
            return $this->renderError('版本信息不正确，无法删除');
        }
        $res = $this->colorModel->delColor(['com_id'=>$this->com_id,'color_id'=>$color_id]);
        if (!$res){
            return $this->renderError('删除出错');
        }
        return $this->renderSuccess('删除成功');
    }
}