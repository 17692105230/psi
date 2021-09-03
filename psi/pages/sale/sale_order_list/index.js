// pages/sale/sale_pre_order_list/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadOk: false,
    dataList: [], //数据
    last_page: 1, //总页数
    now_page: 1, //当前页
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
    console.log(e,99999)
    wx.navigateTo({
      url: `/pages/sale/sale_order/index?id=${e.detail.id}&orders_type=${e.detail.orders_type}`
    })
  },
  delete(e) {
    let _this = this;
    let lock_version = e.detail.detelTemp.lock_version;
    let orders_code = e.detail.detelTemp.orders_code;
    let orders_id = e.detail.detelTemp.orders_id;
    app._get('web/sale/delSaleOrders', {
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
          dataList: [],
          loadOk: false,
        })
        _this.getSaleOrdersList();
      }
    )
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // 0.销售单编辑 1.销售单删除
    let qxList = app.getSomeNodeQx([ '销售单编辑',  '销售单删除'])
    this.setData({
      editqx: qxList[0],
      delqx: qxList[1],
    })
    let _this = this
    builderPageHeight(this, function (data) {
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
      dataList: [],
      loadOk: false,
    })
    this.getSaleOrdersList()
  },

  tabSelect(e) {
    let status = e.currentTarget.dataset.status;
    if (this.data.TabCur == status) {
      if (status == -1) {
        //全部
        console.log(123)
      }
      return
    }
    this.setData({
      TabCur: status,
      last_page: 1,
      now_page: 1,
      dataList: [],
      loadOk: false,
      nomore: false,
      scrollLeft: (status - 1) * 60
    })
    this.getSaleOrdersList()
  },
  onRefresh() {
    this.setData({
      last_page: 1,
      now_page: 1,
      nomore: false,
      loadOk: false,
    })
    this.getSaleOrdersList('', true)
  },
  /**
   * 获取销售单列表
   */
  getSaleOrdersList(isRefresh) {
    app._get(
      "web/sale/getSaleOrdersList",
      // {orders_status_type:this.data.orders_status_type},
      {
        orders_status: this.data.TabCur,
        page: this.data.now_page,
      },
      (res) => {
        // console.log(res)
        if (res.errcode != 0) {
          return wx.showToast({
            title: '加载失败',
          });
        }
        // this.data.data_list = res.data
        //加载成功之后取出时间字段，把时间戳改为时间格式
        if (res.data.rows) {
          for (let i = 0; res.data.rows.length > i; i++) {
            let newDate = this.getDate(res.data.rows[i].orders_date);
            res.data.rows[i].orders_date = newDate
          }
        }

        let data = res.data
        let last_page = 1
        if (data.rows) {
          data = data.rows
          last_page = res.data.total % 20 ? parseInt(res.data.total / 20) + 1 : parseInt(res.data.total / 20)
        } else {
          data = []
        }
        this.setData({
          dataList: isRefresh ? data : [...this.data.dataList, ...data],
          loadOk: true,
          last_page,
          now_page: ++this.data.now_page,
          triggered: false
        });
        //给列表添加权限
        let dataList = this.data.dataList
        for(let i of dataList){
          i.editqx = this.data.editqx
          i.delqx = this.data.delqx
        }
        this.setData({
          dataList
        })

      });
  },
  //触底刷新
  tolower() {
    if (this.data.now_page <= this.data.last_page) {
      this.getSaleOrdersList()
    } else {
      this.setData({
        nomore: true,
      })
    }
  },
  getDate(date) {
    //date是传过来的时间戳，注意需为13位，10位需*1000
    //也可以不传,获取的就是当前时间
    var time = new Date(parseInt(date * 1000));
    var year = time.getFullYear() //年
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
  let data = wx.getStorageSync('commonList_height');
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
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#navBar').boundingClientRect();
  query.exec((res) => {
    data.navBarHeight = res[0].height;
    wx.setStorageSync('commonList_height', data);
    _fun && _fun(data);
  });
};