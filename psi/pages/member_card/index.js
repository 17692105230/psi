// pages/supplier/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    cardList: [], //会员卡列表
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
    delCardIndex: '', //删除会员 所需index
    oid:0,
    lock_version:0,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {},
  onShow() {
    this.getCardList();
  },
  // 修改跳转
  onEdit: function (e) {
    let oid = e.currentTarget.dataset.oid;
    let lock_version = e.currentTarget.dataset.lock_version;
    wx.navigateTo({
      url: '/pages/member_card/edit?oid=' + oid + '&&lock_version=' + lock_version
    })
  },
  //添加跳转
  onAdd() {
    wx.navigateTo({
      url: '/pages/member_card/edit?type=add'
    })
  },
  //加载列表数据请求
  getCardList() {
    let _this = this;
    app._get('web/MemberCard/listMemberCard',{},
      (res) => {
        if(res.errcode != 0) {
          return wx.showToast({
            title: '获取会员列表失败',
            icon: 'error',
            duration: 1500
          })
        }
        //请求成功
        _this.setData({
          cardList:res.data
        })
      }
    );
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
  delCardTip: function (e) {
    let _this = this;
    let lock_version = e.currentTarget.dataset.version;
    let oid = e.currentTarget.dataset.oid;
    _this.setData({
      lock_version: lock_version,
      oid: oid
    });
    _this.showTip("确认此会员吗?", "delClient");
  },
  /**
   * zll 删除供应商
   */
  delCard: function () {
    // let _this = this;
    // app._get('web/client/delClient', {
    //   client_id: _this.data.oid,
    //   lock_version: _this.data.lock_version
    // }, function (res) {
    //   if (res.errcode == 0) {
    //     wx.showToast({
    //       title: '删除成功',
    //       icon: 'error',
    //       duration: 1500
    //     })
    //     _this.getCardList();
    //   }
    // })
    // _this.setData({
    //   tipShow: false
    // });
  }
})