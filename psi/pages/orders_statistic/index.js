const app = getApp();
let now_date = app.util.getNowDate()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    last_page: 1, //总页数
    now_page: 1, //当前页
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    tabsIndex: 0,
    tabDates: [
      "日", "周", "月", "季", "年"
    ],
    scrollTop: 0, //滚动距离
    date_index: [0, 0, 0, 0], // 时间筛选
    // 进货走势 时间筛选 列表
    date_list: ["全部时间", "昨日", "今日", "本周", "本月", "本季", "本年", "自定义"],
    start_date: app.util.getPreMonthDay(now_date), // 自定义日期 开始日期 默认为向前一个月
    end_date: now_date, // 自定义日期 结束日期

    order_type: ['采购单', '销售单', '盘点单', '调拨单'],
    order_type_index: 0,
    pageData: {
      listHead: ["单据编号", "业务日期", "往来单位", '店仓', '业务员', '单据备注', "数量", '金额', ],
      listFoot: ['合计', '-', '-', '-', '-', '-', '555', '666'],
      list: [],
    },
    variateList: ['name', 'create_time', 'reciprocal', 'warehouse', 'user', 'orders_remark', 'goods_number', 'orders_money'],
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function () {
    let _this = this;
    _this.countSize();
    _this.getList()


  },
  //zll 时间下拉框打开
  onShowMore: function (e) {
    this.setData({
      showEcMask: true,
    })
    let _this = this;
    _this.setData({
      data_select_show: !_this.data.data_select_show
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //zll 点击 时间按钮
  change_purchase_date: function (e) {
    let _this = this;
    let index = e.currentTarget.dataset.index;
    let dateindex = e.currentTarget.dataset.dateindex;
    let date_index = _this.data.date_index;
    date_index[dateindex] = e.currentTarget.dataset.index;
    _this.setData({
      date_index: date_index
    });
    if (index <= 6) {
      _this.btnCloseDrop();
    }
  },
  //zll 修改开始日期
  bindDateStart: function (e) {
    let _this = this;
    let start_date = _this.data.start_date;
    start_date = e.detail.value;
    _this.setData({
      start_date: start_date
    });
  },
  //zll 修改结束日期
  bindDateEnd: function (e) {
    let _this = this;
    let end_date = _this.data.end_date;
    end_date = e.detail.value;
    _this.setData({
      end_date: end_date
    });
  },
  //cpf 时间下拉框 关闭
  btnCloseDrop() {
    this.setData({
      showEcMask: false,
    })
    let _this = this;
    //如果是自定义日期,判断时间有效性
    if (_this.data.date_index[_this.data.tabsIndex] == 7) {
      let starttime = new Date(_this.data.start_date).getTime();
      let endtime = new Date(_this.data.end_date).getTime();
      if (starttime > endtime) {
        wx.showToast({
          title: '开始时间不能大于结束时间',
          icon: 'none'
        });
        return false;
      }
      let startYear, startMonth, startDay, endYear, endMonth, endDay;
      if (!_this.data.start_date) {
        let dateObj = new Date();
        startYear = dateObj.getFullYear();
        startMonth = dateObj.getMonth();
        startDay = dateObj.getDay();
      } else {
        let dateObj = new Date(_this.data.start_date);
        startYear = dateObj.getFullYear();
        startMonth = dateObj.getMonth();
        startDay = dateObj.getDay();
      }
      if (!_this.data.end_date) {
        let enddateObj = new Date();
        endYear = enddateObj.getFullYear();
        endMonth = enddateObj.getMonth();
        endDay = enddateObj.getDay();
      } else {
        let enddateObj = new Date(_this.data.end_date);
        endYear = enddateObj.getFullYear();
        endMonth = enddateObj.getMonth();
        endDay = enddateObj.getDay();
      }
      _this.setData({
        start_date: startYear + "-" + startMonth + "-" + startDay,
        end_date: endYear + "-" + endMonth + "-" + endDay,
      })
    }
    _this.setData({
      scrollTop: 0,
      data_select_show: !_this.data.data_select_show

    });

    _this.getList();
  },
  //单据打开
  onShowOrderType: function (e) {
    this.setData({
      showEcMask: true,
    })
    let _this = this;
    _this.setData({
      order_type_show: !_this.data.order_type_show
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //选择仓库
  change_warehouse: function (e) {
    let _this = this;
    let order_type_index = e.currentTarget.dataset.index
    this.setData({
      order_type_index,
      scrollTop: 0,
      order_type_show: !_this.data.order_type_show,
      last_page: 1,
      now_page: 1,
    })


  },
  //zll 仓库 下拉框 关闭
  onCloseWarehouse: function (e) {
    this.setData({
      showEcMask: false,
      
    })
    let _this = this;
    _this.setData({
      scrollTop: 0,
      order_type_show: !_this.data.order_type_show
    });
  },
  //搜索
  searchList(){
    this.setData({
      nomore: false,
      last_page: 1, //总页数
      now_page: 1, //当前页
    })
    this.getList()
  },
  //cpf 销售分析 客户
  getList(isTolower) {
    this.setData({
      nomore: false
    })
    let _this = this;
    let search_type = _this.data.order_type_index
    app._get('web/Finance/blendBill', {
      search_type,
      start_time: _this.data.start_date,
      end_time: _this.data.end_date
    }, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let d = res.data.data
      let list = []
      for (let i of d) {
        let time = i.create_time.slice(0, 10)
        let data = {}
        data.name = i.orders_code
        data.orders_id = i.orders_id
        data.orders_code = i.orders_code
        data.create_time = time
        data.orders_remark = i.orders_remark || '-'
        data.goods_number = i.goods_number
        data.user = i.user && i.user.user_name ? i.user.user_name : '-'
        if (search_type == 0) {
          console.log(i, i.orders_rmoney, 100000)
          data.reciprocal = i.supplier.supplier_name || '-'
          data.warehouse = i.warehouse.org_name
          data.orders_money = i.orders_rmoney
          data.orders_type = 'cgd'

        }
        if (search_type == 1) {
          console.log(i, 11111111)
          data.reciprocal = i.member && i.member.member_name ? i.member.member_name : '-'
          data.warehouse = i.warehouse.org_name
          data.orders_money = i.orders_rmoney
          data.orders_type = 'xsd'

        }
        if (search_type == 2) {
          data.reciprocal = '-'
          data.orders_money ='-'
          data.warehouse = i.organization.org_name
          data.orders_type = 'pdd'

        }
        if (search_type == 3) {
          data.warehouse = '-'
          data.reciprocal = i.outorg.org_name+ ' 调入 ' + i.inorg.org_name
          data.orders_money ='-'
          data.orders_type = 'dbd'

        }
        list.push(data)
      }
      if (isTolower === true) {
        list = [..._this.data.pageData.list, ...list]
      }
      _this.setData({
        'pageData.list': list,
        'pageData.listFoot[6]': res.data.sum_data.sum_number,
        'pageData.listFoot[7]': res.data.sum_data.sum_money?res.data.sum_data.sum_money:'-',
        last_page: res.data.last_page,
        now_page: ++_this.data.now_page,
      })
    })
  },
  //触底刷新
  tolower() {
    if (this.data.now_page <= this.data.last_page) {
      this.getList(true)
    } else {
      this.setData({
        nomore: true,
      })
    }
  },
  //重新处理页面尺寸计算
  countSize: function () {
    let _this = this;
    builderPageHeight(this, function (data) {
      // 算出来之后存到data对象里面
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.headerHeight - app.globalData.CustomBar,
        sizeData: data
      });
      _this.setData({
        isRefresher: true
      });
    });
  },
})


/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('sales_analysis_height');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    // 页面总高度将会放在这里
    windowHeight: 0,
    // header的高度
    headerHeight: 0,
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#header').boundingClientRect();
  query.exec((res) => {
    data.headerHeight = res[0].height;
    wx.setStorageSync('sales_analysis_height', data);
    _fun && _fun(data);
  });
};