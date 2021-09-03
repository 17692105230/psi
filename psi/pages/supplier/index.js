// pages/supplier/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    supplierList: [],
    tipShow:false,//提示框显示
    tips:"",//提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName:'tipItemClick',//提示框点击确定调用的方法名
    delSupplierIndex:'',//删除供应商 所需index
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad() {
          // 0.新增 1.编辑 2.删除
          let qxList = app.getSomeNodeQx([ '供应商新增',   '供应商编辑',   '供应商删除'])
          this.setData({
            qxList
          })
  },
  onShow(){
    this.getSupplierList();
  },
  // 修改跳转
  onEdit: function(e) {
    let index=e.currentTarget.dataset.index;
    wx: wx.navigateTo({
      url: '/pages/supplier/edit?type=edit&&supplier_id='+this.data.supplierList[index].supplier_id+'&&lock_version='+this.data.supplierList[index].lock_version
    })
  },
  //添加跳转
  onAdd(){
    wx: wx.navigateTo({
      url: '/pages/supplier/edit?type=add'
    })
  },
  //加载列表数据请求
  getSupplierList(){
    let _this=this;
    app._get('web/supplier/getSupplierList','',function(res){
      console.log(res)
      _this.setData({
        supplierList:res.data
      })
    })
  },
  /**
   * zll 显示提示框
   * @param {*} tip 提示内容
   * @param {*} funName 点击非取消按钮,回调的方法名
   * @param {*} color 提示文字颜色 默认灰色
   * @param {*} iscancel 是否显示取消按钮
   * @param {*} btnlist 按钮对象 列表
   */
  showTip:function(tip,funName,color='#9a9a9a',iscancel=true,btnlist=[{color:'#e53a37',text:'确定'}]){
    let _this = this;
    _this.setData({
      tipShow:true,
      tips:tip,
      tipItemList:btnlist,
      tipItemClickName:funName,
      tipColor:color,
      tipIsCancel:iscancel
    });
  },
  /**
   * zll 隐藏提示框
   */
  tipClose:function(){
    this.setData({
      tipShow:false,
    });
  },
  /**
   * zll 删除供应商 提示
   */
  delSupplierTip:function(e){
    let _this = this;
    let index=e.currentTarget.dataset.index;
    _this.setData({
      delSupplierIndex:index
    });
    _this.showTip("确认删除供应商吗?","delSupplier");
  },
  /**
   * zll 删除供应商
   */
  delSupplier:function(){
    let _this=this;
    app._post_form('web/supplier/delSupplier',{
      supplier_id:_this.data.supplierList[_this.data.delSupplierIndex].supplier_id,
      lock_version:_this.data.supplierList[_this.data.delSupplierIndex].lock_version,
    },function(res){
      if (res.errcode==0) {
        _this.getSupplierList();
      }
    })
    _this.setData({
      tipShow:false
    });
  }
})