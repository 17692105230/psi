<?php


namespace app\web\controller;

use app\Request;
use app\web\validate\CategoryValidate as CategoryValidateModel;
use app\web\model\Category as CategoryModel;
use think\db\Raw;
use app\web\model\Goods as GoodsModel;

class Category extends Controller
{
    //初始化
    /**
     * @var CategoryModel
     */
    protected $categoryModel;
    protected $categoryValidate;
    public function initialize()
    {
        parent::initialize();
        $this->categoryModel = new CategoryModel();
        $this->categoryValidate = new CategoryValidateModel();
    }

    /**
     * 函数描述:小程序类别查询
     * @return array
     * Date: 2021/1/5
     * Time: 14:51
     * @author mll
     */
    public function loadCategory()
    {
        $where = [
            'com_id' => $this->com_id,
//            'category_pid' => new Raw("<>0")
        ];
        $res = $this->categoryModel->loadData($where);
        if ($res){
            return $this->renderSuccess($res,'数据查询成功');
        }
        return $this->renderError('服务器出错');
    }

    /**
     * 函数描述:小程序商品类别添加
     * @param Request $request
     * Date: 2021/1/7
     * Time: 10:05
     * @author mll
     */
    public function saveCategory(Request $request){
        $data = $request->post();
        if (!$this->categoryValidate->scene('add')->check($data)){
            return $this->renderError($this->categoryValidate->getError());
        }
        if ($this->categoryModel->findCategoryId(['category_name' => $data['category_name'],'com_id'=>$this->com_id])){
            return $this->renderError('类别已经存在，请勿重复添加');
        }
        $savedata = [
            'category_name' => $data['category_name'],
            'sort' => '100',
            'com_id' => $this->com_id
        ];
        if (!$this->categoryModel->saveCategory($savedata)){
            return $this->renderError('类别添加出错');
        }
        return $this->renderSuccess('','类别添加成功');
    }

    /**
     * 函数描述:修改类别
     * @param Request $request
     * Date: 2021/1/7
     * Time: 16:32
     * @author mll
     */
    public function editCategory(Request $request){
        $data = $request->post();
        //验证
        if (!$this->categoryValidate->scene('edit')->check($data)){
            return $this->renderError($this->categoryValidate->getError());
        }
        $id = $data['category_id'];
        //查询版本是否一致
        $lock = $this->categoryModel->findCategoryByExample(['category_id' => $data['category_id'], 'com_id' => $this->com_id]);
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不匹配，不可以修改');
        }
        $update = [
            'category_name' => $data['category_name'],
            'lock_version' => $data['lock_version']+1
        ];
        $where=[
          'category_id' => $id,
          'com_id' => $this->com_id,
        ];
        $res = $this->categoryModel->editCategory($where,$update);
        if (!$res){
           return $this->renderError('服务器错误');
        }
        return $this->renderSuccess('','修改成功');
    }

    /**
     * 函数描述:删除类别
     * @param Request $request
     * Date: 2021/1/7
     * Time: 17:13
     * @author mll
     */
    public function delCategory(Request $request){
        $data = $request->post();
        //验证
        if (!$this->categoryValidate->scene('del')->check($data)){
            return $this->renderError($this->categoryValidate->getError());
        }
        //先判断是否在商品中使用,如果存在则不允许删除
        $goodsModel = new GoodsModel();
        $goods = $goodsModel->where(['category_id' => $data['category_id'], 'com_id' => $this->com_id])->find();
        if ($goods){
            return $this->renderError('此分类已经使用，无法删除');
        }
        //判断版本是否正确
        $lock = $this->categoryModel->findCategoryByExample(['category_id' => $data['category_id'], 'com_id' => $this->com_id]);
        if (!$lock['lock_version'] == $data['lock_version']){
            return $this->renderError('版本信息不匹配，不可以删除');
        }
        $res = $this->categoryModel->delCategory(['com_id'=>$this->com_id,'category_id'=>$data['category_id']]);
        if (!$res){
            return $this->renderError('删除出错');
        }
        return $this->renderSuccess('','删除成功');
    }
}