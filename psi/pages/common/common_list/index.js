const app = getApp()
//列表类型数据
let datas = {

  skd: {
    url: '',
    title: '收款单列表',
    bottom_text: '新增收款单',
    to_page: '/pages/finance/receipt_order/index',
    sumNumberName:'商品'
  },
  fkd: {
    url: '',
    title: '付款单列表',
    bottom_text: '新增付款单',
    to_page: '/pages/finance/pay_order/index',
    sumNumberName:'商品',
  },
  fyd: {
    url: '',
    title: '费用单列表',
    bottom_text: '新增费用单',
    to_page: '/pages/finance/cost_order/index',
    sumNumberName:'商品'
  },
  srd: {
    url: '',
    title: '收入单列表',
    bottom_text: '新增收入单',
    to_page: '/pages/finance/income_order/index',
    sumNumberName:'商品'
  },
  pdd: {
    url: 'web/inventory/list',
    delApi:'web/inventory/delete',
    title: '盘点单列表',
    bottom_text: '',
    to_page: '/pages/stock/check_order/index',
    sumNumberName:'盘点'
  },
  dbd: {
    url: 'web/TransferOrders/list',
    title: '调拨单列表',
    delApi:'web/TransferOrders/delete',
    bottom_text: '',
    to_page: '/pages/stock/allot_order/index',
    sumNumberName:'调拨'
  },
  // czd: {
  //   url: '',
  //   title: '拆装单列表',
  //   bottom_text: '',
  //   to_page: ''
  // },
  // cbtzd: {
  //   url: '',
  //   title: '成本调整单列表',
  //   bottom_text: '',
  //   to_page: ''
  // },
  // kctzd: {
  //   url: '',
  //   title: '库存调整单列表',
  //   bottom_text: '',
  //   to_page: ''
  // },
}
Page({
  data: {

    TabCur: -1,
    statusList: [{
        index: -1,
        item: '全部'
      },
      // {
      //   index: 0,
      //   item: '未保存'
      // },
      {
        index: 1,
        item: '草稿'
      },
      {
        index: 9,
        item: '完成'
      }
    ],
    type: '',
    last_page: 1, //总页数
    now_page: 1, //当前页
    allMoney: 189, //合计
    list: []
  },
  onLoad: function (e) {
    let qxList = app.getSomeNodeQx([  '盘点单编辑',  '盘点单删除', '调拨单编辑', '调拨单删除'])
    this.setData({
      qxList
    })
    let _this = this
    builderPageHeight(this, function(data) {
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.navBarHeight - app.globalData.CustomBar
      });

    });
    let type = e.type
    this.setData({
      title: datas[type].title,
      url: datas[type].url,
      bottomText: datas[type].bottom_text,
      toPage: datas[type].to_page,
      sumNumberName:datas[type].sumNumberName,
      delApi:datas[type].delApi,
      type,
      
    })
  },
  onShow() {
    this.setData({
      now_page: 1,
      list: [],
      loadOk:false,
      active:''
    })
    this.getData()

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
      list: [],nomore:false,
      scrollLeft: (status - 1) * 60,
      loadOk:false,
    })
    this.getData()
  },
  //获取数据
  getData(msg,isRefresh) {
    let _this = this
    wx.showLoading({
      title: msg ? msg : '加载中',
    })
    let url = this.data.url
    let orders_status = this.data.TabCur==1?0:this.data.TabCur
    if(this.data.type=='pdd'){
      orders_status==0?orders_status=1:''
    }
    app._get(url, {
      page: this.data.now_page,
      orders_status 
    }, res => {
      wx.hideLoading()
      if (res.errcode != 0) {
        wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        });
      }
      let datas = res.data.data
      let changeData = []
      let status = {
        0: '未保存',
        1: '草稿',
        9: '完成'
      }
      for (let i of datas) {
        changeData.push({
          money: '',
          salesman: '',
          id: i.orders_id, //单据id
          client: i.organization&&i.organization.org_name?i.organization.org_name:false, //仓库名称
          inorg: i.inorg?i.inorg.org_name:false, //调入仓库名称
          outorg: i.outorg?i.outorg.org_name:false, //调入仓库名称
          state: status[i.orders_status], //状态
          goods_number: i.goods_number, //总数
          goods_plnumber: i.goods_plnumber, //盈亏数量?i.goods_plnumber:false
          user_id: i.user_id, //制单员id
          date: i.update_time, //更新日期
          orders_status:i.orders_status,
          user:i.user
        })
      }
      _this.setData({
        list:isRefresh? changeData : [..._this.data.list, ...changeData],
        last_page: res.data.last_page,
        now_page: ++_this.data.now_page,
        loadOk:true,
        triggered:false,
      })
      //给列表设置权限
      //盘点单
      let list = _this.data.list
      if(_this.type='pdd'){
        for(let i  of list){
          i.editqx = _this.data.qxList[0],
          i.delqx = _this.data.qxList[1]
        }
      }
      //调拨单
      if(_this.type='dbd'){
        for(let i  of list){
          i.editqx = _this.data.qxList[2],
          i.delqx = _this.data.qxList[3]
        }
      }
      _this.setData({
        list
      })
      if(isRefresh){
        app.toast('刷新成功',500,'success')
      }

    })
  },
  //触底刷新
  tolower() {
    if (this.data.now_page <= this.data.last_page) {
      this.getData()
    } else {
      this.setData({
        nomore: true,
      })
    }
  },
  details(e) {
    if(!e.currentTarget.dataset.qx){
      app.toast('暂无权限')
      return
    }
    this.setData({
      active:''
    })
    let id = e.currentTarget.dataset.id;
    let index = e.currentTarget.dataset.index;
    let ind = `active[${index}]`
    this.setData({
      [ind]:true
    })
    wx.navigateTo({
      url: this.data.toPage + '?id=' + id
    })
  },
  delete(e) {
    let state = e.target.dataset.state
    if (state == '完成') {
      wx.showToast({
        title: '完成单据不可删除',
        icon: 'none',
      });
      return
    }
    let _this = this;
    wx.showModal({
      title: '提示',
      content: '确定删除吗？',
      success(res) {
        if (res.confirm) {
          del()
        } else if (res.cancel) {

        }
      }
    })

    function del() {
      // let lock_version = e.target.dataset.version;
      // let orders_code = e.target.dataset.code;
      let orders_id = e.target.dataset.id;
      app._get(_this.data.delApi, {
          orders_id
        },
        (res) => {
          if (res.errcode != 0) {
            return wx.showToast({
              title: res.errmsg,
              icon: 'error',
              duration: 1500
            });
          }

          _this.setData({
            last_page: 1,
            now_page: 1,
            list: [],
            loadOk:false
          })
          _this.getData('删除成功')
        }
      )
    }

  },
  to() {
    wx.navigateTo({
      url: this.data.toPage,
    })
  },
  onRefresh(){
    this.setData({
      last_page: 1,
      now_page: 1,
      nomore:false,
      loadOk:false,
    })
    this.getData('',true)
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
    success: function(res) {
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