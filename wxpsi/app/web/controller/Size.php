<?php


namespace app\web\controller;

use app\web\model\Goods as GoodsModel;
use app\web\model\GoodsDetails;
use app\web\model\Size as SizeModel;
use app\web\validate\Size as SizeValidate;
use app\Request;
use think\db\Raw;
use think\response\Json;

/**
 * Class Size
 * @package app\web\controller
 * Date: 2021/1/5
 * Time: 15:49
 * @author mll
 */
class Size extends Controller
{

    protected $sizeModel;
    /**
     * @var SizeValidate
     */
    private $sizeValidate;

    public function initialize()
    {
        parent::initialize();
        $this->sizeModel = new SizeModel();
        $this->sizeValidate = new SizeValidate();
    }
    /**
     * 函数描述: 小程序列表接口
     * @return mixed
     * Date: 2021/1/5
     * Time: 11:26
     * @author mll
     */
  public function loadSizeList()
  {
      $where = [
          'com_id' => $this->com_id,
      ];
      $res = $this->sizeModel->loadData($where);
      if ($res){
          return $this->renderSuccess($res,'数据查询成功');
      }
      return $this->renderError('服务器出错');
  }

    /**
     * 函数描述:添加尺码
     * @param Request $request
     * @return array
     * Date: 2021/1/8
     * Time: 11:04
     * @author mll
     */
    public function saveSize(Request $request)
    {
        $data = $request->post();
        if (!$this->sizeValidate->scene('add')->check($data)) {
            return $this->renderError($this->sizeValidate->getError());
        }
        $where = [
            'size_name' => $data['size_name'],
            'size_code' => $data['size_code'],
            'com_id' => $this->com_id
        ];
        if ($this->sizeModel->findDataByExample($where)) {
            return $this->renderError('已经存在，请勿重复添加');
        }
        $res = $this->sizeModel->savesize($where);
        if (!$res){
            return  $this->renderError('添加颜色出错');
        }
        return $this->renderSuccess($res,'添加成功');
    }

    /**
     * 函数描述:修改尺码
     * @param Request $request
     * Date: 2021/1/8
     * Time: 11:07
     * @author mll
     */

    public function editSize(Request $request){
        $data = $request->post();
        if (!$this->sizeValidate->scene('edit')->check($data)){
            return $this->renderError($this->sizeValidate->getError());
        }
        $lock_where = [
            'com_id' => $this->com_id,
            'size_id' => $data['size_id']
        ];
        $lock = $this->sizeModel->findDataByExample($lock_where);
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不正确，不可以修改');
        }
        $update = [
            'size_name' => $data['size_name'],
            'size_code' => $data['size_code'],
            'lock_version' => $data['lock_version']+1
        ];
        $where = [
            'com_id' => $this->com_id,
            'size_id' => $data['size_id']
        ];
        $res = $this->sizeModel->editSize($where,$update);
        if (!$res){
            return $this->renderError('服务器错误');
        }
        return $this->renderSuccess('','修改成功');
    }

    /**
     * 函数描述:删除尺码
     * @param Request $request
     * Date: 2021/1/8
     * Time: 11:12
     * @author mll
     */
    public function delSize(Request $request){
        $data = $request->post();
        //验证
        if (!$this->sizeValidate->scene('del')->check($data)){
            return $this->renderError($this->sizeValidate->getError());
        }
        $size_id = $data['size_id'];
        $lock_version = $data['lock_version'];
        //先判断是否在商品中使用,如果存在则不允许删除
        $goodsModel = new GoodsDetails();
        $goods = $goodsModel->where(['size_id' => $size_id, 'com_id' => $this->com_id])->find();
        if ($goods){
            return $this->renderError('此分类已经使用，无法删除');
        }
        $lock = $this->sizeModel->findDataByExample(['com_id' => $this->com_id,'size_id'=>$size_id]);
        if (!$lock['lock_version'] == $lock_version){
            return $this->renderError('版本信息不正确，无法删除');
        }
        $res = $this->sizeModel->delSize(['com_id'=>$this->com_id,'size_id'=>$size_id]);
        if (!$res){
            return $this->renderError('删除出错');
        }
        return $this->renderSuccess('删除成功');
    }
}