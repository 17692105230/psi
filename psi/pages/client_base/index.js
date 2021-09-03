// pages/supplier/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    clientList: [],
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
    delClientIndex: '', //删除供应商 所需index
    search:'',
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    console.log(e,clearkey)
    this.setData({
      [clearkey]:''
    })
  },
  onLoad() {
    let qxList = app.getSomeNodeQx([ '客户新增',  '客户编辑',  '客户删除'])
    this.setData({
      qxList
    })
  },
  onShow() {
    this.getClientList();
  },
  // 修改跳转
  onEdit: function (e) {
    let client_id = e.currentTarget.dataset.client_id;
    let lock_version = e.currentTarget.dataset.version;
    wx.navigateTo({
      url: '/pages/client_base/edit?client_id=' + client_id + '&&lock_version=' + lock_version
    })
  },
  //添加跳转
  onAdd() {
    wx.navigateTo({
      url: '/pages/client_base/edit?type=add'
    })
  },
  //加载列表数据请求
  getClientList() {
    let _this = this;
    app._get('web/client/getList', '', function (res) {
      if (res.errcode != 0) {
        return wx.showToast({
          title: '列表加载失败',
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        clientList: res.data
      })
    })
  },
  search(){
    let _this = this
    app._get('web/client/getList', {
      search_data:_this.data.search
    }, function (res) {
      _this.setData({
        search:""
      })
      if (res.errcode != 0) {
        return wx.showToast({
          title: '列表加载失败',
          icon: 'error',
          duration: 1500
        })
      }
      if (res.data.length == 0) {
        return wx.showToast({
          title: '没有符合条件的结果',
          icon: 'none',
          duration: 1500
        })
      }
      _this.setData({
        clientList: res.data,
        search:""
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
  showTip: function (tip, funName, color = '#9a9a9a', iscancel = true, btnlist = [{
    color: '#e53a37',
    text: '确定'
  }]) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: btnlist,
      tipItemClickName: funName,
      tipColor: color,
      tipIsCancel: iscancel
    });
  },
  /**
   * zll 隐藏提示框
   */
  tipClose: function () {
    this.setData({
      tipShow: false,
    });
  },
  /**
   * zll 删除供应商 提示
   */
  delClientTip: function (e) {
    let _this = this;
    let lock_version = e.currentTarget.dataset.version;
    let client_id = e.currentTarget.dataset.client_id;
    _this.setData({
      lock_version: lock_version,
      client_id: client_id
    });
    _this.showTip("确认删除客户吗?", "delClient");
  },
  /**
   * zll 删除供应商
   */
  delClient: function () {
    let _this = this;
    app._get('web/client/delClient', {
      client_id: _this.data.client_id,
      lock_version: _this.data.lock_version
    }, function (res) {
      if (res.errcode == 0) {
        wx.showToast({
          title: '删除成功',
          icon: 'error',
          duration: 1500
        })
        _this.getClientList();
      }
    })
    _this.setData({
      tipShow: false
    });
  }
})