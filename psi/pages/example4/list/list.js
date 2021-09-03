// pages/example4/list/list.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */

  data: {
    loadOk:false,
    last_page: 1, //总页数
    now_page: 1, //当前页
    data_list: [],
    TabCur: -1,
    scrollLeft: 0,
    statusList: [{
        index: -1,
        item: '全部'
      },
      {
        index: 0,
        item: '草稿'
      },
      {
        index: 9,
        item: '正式'
      }
    ]
  },
  details(e) {
    wx.navigateTo({ 
      url: '/pages/example4/index?orders_code=' + e.detail
    })
  },
  /**
   *  组件给页面传值，删除功能
   * @param {e} e 
   */
  delete(e) {
    let orders_code = e.detail.detelTemp.orders_code;
    let lock_version = e.detail.detelTemp.lock_version;
    let orders_id = e.detail.detelTemp.orders_id;
    let _this = this;
    app._get('web/PurchaseOrders/delPurchaseOrders', {
        orders_code: orders_code,
        lock_version: lock_version,
        orders_id: orders_id
      },
      (res) => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          });
        }
        //调用成功
        _this.setData({
          last_page: 1,
          now_page: 1,
          data_list: [],
          loadOk:false,
        })
        _this.getPurchaseOrdersList();
      }
    )
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let qxList = app.getSomeNodeQx(['采购单编辑', '采购单删除'])
    this.setData({
      editqx:qxList[0],
      delqx:qxList[1],
    })
    let _this = this
    builderPageHeight(this, function(data) {
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.navBarHeight - app.globalData.CustomBar
      });
    });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.setData({
      now_page: 1,
      data_list: [],  loadOk:false,
    })
    this.getPurchaseOrdersList()
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },
  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },



  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },
  tabSelect(e) {
    let status = e.currentTarget.dataset.status;
    if(this.data.TabCur == status){
      if(status == -1){
        //全部
        console.log(123)
      }
      return
    }
    this.setData({
      TabCur: status,
      last_page: 1,
      now_page: 1,
      data_list: [],nomore:false,
      scrollLeft: (status - 1) * 60,  loadOk:false,
    })
    this.getPurchaseOrdersList()
  },
  onRefresh(){
    this.setData({
      last_page: 1,
      now_page: 1,
      nomore:false,
      loadOk:false,
    })
    this.getPurchaseOrdersList(true)
  },
  /**
   * 获取采购单列表
   */
  getPurchaseOrdersList(isRefresh) {
    app._get(
      "/web/PurchaseOrders/getPurchaseOrders", {
      page: this.data.now_page,
      orders_status: this.data.TabCur,
      },
      (res) => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg
          });
        }
        if(res.data.rows){
          for(let i = 0;res.data.rows.length > i;i++){
            let newDate = this.getDate(res.data.rows[i].orders_date);
            res.data.rows[i].orders_date = newDate
         }
        }

       let data = res.data
       let last_page = 1
       if(data.rows){
        data = data.rows
        last_page = res.data.total % 20 ? parseInt(res.data.total / 20) + 1 : parseInt(res.data.total / 20)
       }else{
         data = []
       }
        this.setData({
          data_list:isRefresh? data: [...this.data.data_list,...data],  
          last_page,
          now_page: ++this.data.now_page,  loadOk:true,        triggered:false,
        });
        //给列表添加权限
        let data_list = this.data.data_list
        for(let i of data_list){
          i.editqx = this.data.editqx
          i.delqx = this.data.delqx
        }
        this.setData({
          data_list
        })
        if(isRefresh){
          app.toast('刷新成功',500,'success')
        }
      });
  },
    //触底刷新
    tolower() {
      if (this.data.now_page <= this.data.last_page) {
        this.getPurchaseOrdersList()
      } else {
        this.setData({
          nomore: true,
        })
      }
    },
  getDate(date){
    //date是传过来的时间戳，注意需为13位，10位需*1000
    //也可以不传,获取的就是当前时间
        var time = new Date(parseInt(date*1000));
        var year= time.getFullYear()  //年
        var month = ("0" + (time.getMonth() + 1)).slice(-2); //月
        var day = ("0" + time.getDate()).slice(-2); //日
        var mydate = year + "-" + month + "-" + day;
        return mydate
      }
}) 

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('example4_height_list');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    windowHeight: 0,
    navBarHeight: 0,
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function(res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#navBar').boundingClientRect();
  query.exec((res) => {
    console.log(res,999000999)
    data.navBarHeight = res[0].height;
    wx.setStorageSync('example4_height_list', data);
    _fun && _fun(data);
  });
};