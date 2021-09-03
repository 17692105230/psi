const app = getApp();

Page({
  /**
   * 页面的初始数据
   */
  data: {
    sytQx: {
      haveQx: true
    },
    searchMemberName: '',
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    saleScrollViewHeight: 0,
    isGoodsSearchShow: false,
    searchGoodsName: '',
    words: {
      submit: '完成',
      clear: 'Clear',
      style_code: '号型',
      size_name: '尺码',
      number_name: '存量'
    },
    //zll 门店信息
    shopIndex: 0,
    shopId: 0,
    shopList: [],
    shopListHeight: 0,
    goodsList: [], //商品列表
    searchGoodsList: [], //商品搜索列表
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
    actions: [{
      name: '删除',
      color: '#fff',
      fontsize: 30, //单位rpx
      width: 70, //单位px
      background: '#FD3B31'
    }], //swiper-action 滑动删除 按钮组
    shopListCutHeight: 0, //商品列表高度需要减去的值
    hrnum_show: false, //价格输入框 显示
    hrnum_value: 0, //价格输入框 值
  },
  toMemberDetail(e) {
    let id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/member/detail?id=' + id
    })
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },
  onLoad: function (options) {
    let _this = this;
    //获取仓库列表
    app.util.getAllList(['organization']).then(res => {
      _this.setData({
        shopList: res.organization,
      })
      //默认仓库id
      if (_this.data.check_shopId) {
        return
      }
      app._get('web/Cashier/getShopInfo', {}, res => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          })
        }
        _this.setData({
          // sum_money: res.data.sum_money,
          shopId: res.data.org_id,
          check_shopId: res.data.org_id,
          shopIndex: _this.data.shopList.findIndex(i => i.id == res.data.org_id)
        })
      })

    })
    // 0.进货 1.销售 2.库存 3.财务 4.采购单列表 5.新增采购单 6.销售单列表 7.新增销售单 8.盘点单列表 9/新增盘点单 10.调拨单列表 11.新增调拨单
    // 12.收银台 13.客户列表 14.结算账户列表  15.供应商列表 16.尺码列表 17.色码列表 18.商品分类 19.会员 20.商品列表  21.报表
    let qxList = app.getSomeNodeQx(['商品列表', '报表管理'])
    this.setData({
      qxList,
    })
    wx.showLoading({
      title: '加载中',
      mask: true
    })
    builderPageHeight(this, function (data) {
      console.log(data);
      // 算出来之后存到data对象里面
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.headerHeight - data.tabBarHeight
      });

      wx.hideLoading();
    });
  },
  onShow() {
    let data = {}
    let _this = this
    if (this.data.shopId) {
      data.org_id = this.data.shopId
    }
    //获取该门店的基础信息
    app._get('web/Cashier/getShopInfo', data, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        sum_money: res.data.sum_money,
      })

    })

  },
  // 显示查询商品
  onShowSearchGoods(e) {
    //默认先显示所有商品
    let _this = this;
    let data = {}
    _this.data.check_shopId ? data.org_id = _this.data.check_shopId : ''
    app._get("web/goods/loadPurchaseGoods", data, (res) => {
      let list = res.data
      _this.setData({
        searchGoodsList: list,
        searchGoodsListOk: true
      });
    });
    this.setData({
      isGoodsSearchShow: true
    });
  },
  // 显示会员
  onShowSearchMember(e) {
    //默认先显示所有会员
    let _this = this;
    app.util.getAllList(['member']).then(res => {
      _this.setData({
        searchMemberList: res.member.filter(i => i.member_status == 1),
        searchMemberListOk: true
      })
    })
    this.setData({
      isMemberShow: true
    });
  },
  // 隐藏查询商品
  onHideSearchGoods(e) {
    this.setData({
      isGoodsSearchShow: false,
      searchGoodsName:''
    });
  },
  //隐藏会员
  onHideMember() {
    this.setData({
      isMemberShow: false,
      searchMemberName:''
    });
  },
  //当扫码时没有款号,只有颜色或尺码时或者两者都有或两者都没有
  //模糊查询商品
  searchGoods: function () {
    let _this = this;
    let data = {}
    data.goods_name = _this.data.searchGoodsName
    _this.data.check_shopId ? data.org_id = _this.data.check_shopId : ''
    app._get("web/goods/loadPurchaseGoods", data,
      (res) => {
        let list = res.data
        for (let i of list) {
          let retailPrice = i.goods_rprice //零售价
          let purchase = i.goods_pprice //采购价
          i.goods_rprice = purchase //在采购单中 统一使用采购价格
          i.type = 'caigoudan' //采购单标识
          i.cg_rprice = retailPrice //真正的零售价


        }
        _this.setData({
          searchGoodsList: list,
          searchGoodsListOk: true,
          searchGoodsName:''
        });
      }, '', '', '', true);
  },
  //模糊查询会员
  searchMember: function () {
    let _this = this;
    app._get("/web/MemberBase/getListData", {
        search_data: _this.data.searchMemberName
      },
      (res) => {
        let list = res.data.data
        console.log(list, 999888)
        _this.setData({
          searchMemberList: list.filter(i => i.member_status == 1),
          searchMemberListOk: true,
          searchMemberName:''
        });
      }, '', '', '', true);
  },
  //选择搜索框中商品
  onSelectGoods(e) {
    let _this = this;
    let goods = _this.data.searchGoodsList[e.currentTarget.dataset.index];
    // console.log(goods);
    _this.setData({
      isGoodsSearchShow: false
    });
    _this.editGoodsFor(goods);
  },
  //选择会员
  onSelectMember(e) {
    let _this = this;
    let member = _this.data.searchMemberList[e.currentTarget.dataset.index];
    _this.setData({
      memberId: e.currentTarget.dataset.id, //会员id
      memberJf: member.accounts[0].account_points, //会员积分
      memberCz: member.accounts[0].account_money, //会员储值
      memberCx: 0, //会员促销
      memberYg: member.accounts[0].total_sum, //会员已购
      isMemberShow: false,
      select_member_name: member.member_name
    });
  },
  //zll 检索商品是否重复,然后打开商品编辑器
  editGoodsFor: function (goods) {
    console.log("即将打开编辑器");
    console.log(goods);
    let _this = this;
    //检索选择的商品是否已经被选过,如果被选过,使用已经选过的商品,因为可能已经选了数量
    try {
      _this.data.list.forEach((item) => {
        if (item.goods_code == goods.goods_code) {
          goods = item;
          throw new Error("有相同商品,使用已选商品");
        }
      });
    } catch (err) {
      console.log(err.message);
    }

    _this.setData({
      mSelectIndex: 0,
      goods: goods,
      mSelectType: true,
      listGoods: goods.list,
      mIsDisplay: true
    });
  },
  //扫码
  onScanGoods(e) {
    let _this = this;
    _this.setData({
      editOrScan: 0,
    });
    console.log('smsmsmsmsmsms')

    wx.scanCode({
      success(res) {
        console.log(res, 'smsmsmsmsmsms')
        let codeObj = JSON.parse(res.result);
        let data = {}
        data.goods_name = codeObj
        _this.data.check_shopId ? data.org_id = _this.data.check_shopId : ''
        app._get("web/goods/loadPurchaseGoods", data,
          (res) => {
            if (res.errcode != 0) {
              wx.showToast({
                title: res.errmsg,
                icon: 'none',
              })
              return
            }
            _this.setData({
              isGoodsSearchShow: true,
              searchGoodsName: codeObj,
              searchGoodsList: res.data,
              searchGoodsListOk: true
            });
          });
      }
    });
  },
  //zll 商品列表改变后,组件调用此方法
  onListChange: function (e) {
    this.setData({
      sumprice: e.detail.sumprice,
      trueMoney: e.detail.sumprice,
      sumcount: e.detail.sumcount
    });
  },
  toUrl: function (e) {
    let qx = e.currentTarget.dataset.qx
    let index = e.currentTarget.dataset.index
    if(index){
      wx.reLaunch({
        url: e.currentTarget.dataset.url,
      })
      return
    }
    if (qx) {
      wx.navigateTo({
        url: e.currentTarget.dataset.url,
      })
    } else {
      app.toast('暂无权限')
    }
  },
  // 结账确认
  onSettlement(e) {
    let _this = this;

    if (_this.data.goodsList.length <= 0) {
      wx.showToast({
        title: '请选择商品',
        icon: 'none'
      });
      return false;
    }
    if (!_this.data.memberId || !_this.data.select_member_name) {
      wx.showToast({
        title: '请选择会员',
        icon: 'none'
      });
      return false;
    }
    //zll 计算商品总价等信息
    let result = _this.billPrice();
    result.org_id = _this.data.shopId //仓库id
    result.member_id = _this.data.memberId //会员id
    result.member_name = _this.data.select_member_name //会员id
    result.goodsList = _this.data.goodsList //商品列表

    wx.showLoading({
      title: '处理中...',
      mask: true
    })
    wx.navigateTo({
      url: '/pages/example5/index',
      events: {
        // 为指定事件添加一个监听器，获取被打开页面传送到当前页面的数据
        acceptDataFromOpenedPage: function (data) {
          console.log(data)
        },
        someEvent: function (data) {
          console.log(data)
        },
        //zll 支付完成后调用此方法
        paySuccess: function (data) {
          console.log("支付完成后,清空商品列表", data);
          //清空商品列表
          _this.setData({
            goodsList: [],
            memberJf: '',
            memberCz: '',
            memberCx: '',
            memberYg: '',
            memberId: '',
            select_member_name: '',
          });
        },
      },
      success: function (res) {
        wx.hideLoading();
        // 通过eventChannel向被打开页面传送数据
        res.eventChannel.emit('acceptDataFromOpenerPage', {
          data: result
        })
      }
    });
  },

  /**
   * 跳转到IM页面
   */
  onChat(e) {
    wx.navigateTo({
      url: '/pages/chat/index'
    });
  },

  onUnload: function () {

  },

  //切换门店
  checkShop: function (e) {
    let _this = this;
    if (_this.data.shopIndex == e.currentTarget.dataset.index) {
      return false;
    }
    _this.setData({
      check_shopId_select: e.currentTarget.dataset.id,
      check_shopIndex_select: e.currentTarget.dataset.index,
    });
    _this.showTip("确定切换门店吗?", 'tipItemClick');
  },
  // 显示提示框
  showTip: function (tip, funName) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: [{
        color: '#e53a37',
        text: '确定'
      }],
      tipItemClickName: funName
    });
  },
  //点击提示框确认按钮  
  tipItemClick: function (e) {
    let _this = this;
    _this.setData({
      shopId: _this.data.check_shopId_select,
      shopIndex: _this.data.check_shopIndex_select,
      check_shopId: _this.data.check_shopId_select,
      check_shopIndex: _this.data.check_shopIndex_select,
      tipShow: false,
      shopListHeight: 0,
      goodsList: [],
    });
    _this.getShopListHeight();
    //获取该门店的基础信息
    app._get('web/Cashier/getShopInfo', {
      org_id: _this.data.shopId
    }, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        sum_money: res.data.sum_money,
      })

    })
  },

  //点击提示框取消
  tipCloseActionSheet: function (e) {
    let _this = this;
    _this.setData({
      tipShow: false,
    });
  },
  // 显示门店列表
  showShopList: function () {
    let _this = this;
    _this.setData({
      shopListHeight: _this.data.shopListHeight > 0 ? 0 : 138
    });
    _this.getShopListHeight();
  },
  //获取门店列表高度
  getShopListHeight: function () {
    let _this = this;
    let query = wx.createSelectorQuery();
    query.select("#shopListBox").boundingClientRect();
    query.selectViewport();
    query.exec((res) => {
      let height = res[0].height;
      _this.setData({
        shopListCutHeight: height
      });
    });
  },

  // 删除商品提示

  delGoods: function (e) {
    let _this = this;
    _this.setData({
      delGoodsIndex: e.currentTarget.dataset.index,
      tipItemClickName: 'delGoodsFun'
    });
    _this.showTip("确定删除商品吗?", 'delGoodsFun');
  },
  // 删除商品操作
  delGoodsFun: function () {
    let _this = this;
    let gl = _this.data.goodsList;
    gl.splice(_this.data.delGoodsIndex, 1);
    _this.setData({
      goodsList: gl,
      tipShow: false
    });
  },
  /**
   * zll 点击返回
   */
  tapUser: function () {
    wx.navigateBack();
  },
  /**
   * zll 点击设置
   */
  tapSetting: function () {

  },
  /**
   * zll goods组件点击'完成'时 回调此方法
   */
  onReturnGoodsData: function (e) {
    console.log(e, '选择商品~');
    let list = this.goodsesToGoods(e);
    list.forEach((item) => {
      this.addGoods(item);
    });
  },
  //zll 添加商品
  addGoods: function (goods) {
    let isRepeat = 0;
    let _this = this;
    let goodsListNow = _this.data.goodsList;
    goodsListNow.forEach((element, index) => {
      if (element.goods_id == goods.goods_id && element.sizeName == goods.sizeName && element.colorName == goods.colorName) {
        isRepeat = 1;
        goodsListNow[index].number = goodsListNow[index].number + goods.number;
      }
    });
    if (isRepeat) {
      _this.setData({
        goodsList: goodsListNow
      });
    } else {
      let goodsList = [..._this.data.goodsList, goods];
      _this.setData({
        goodsList: goodsList
      });
    }

  },

  /**
   * zll goods组件clear时 回调此方法
   */
  onReturnRealTimeData: function (e) {
    console.log("数量编辑");
  },
  /** 
   * zll 将商品选择器返回的商品信息分解为goodslist可用的商品信息
   */
  goodsesToGoods: function (goodses) {
    let goodsList = [];
    if (!goodses.detail.list || goodses.detail.list.length <= 0) {
      return goodsList;
    }
    let goodsInfo = goodses.detail;
    goodsInfo.list.forEach((item, index) => {
      item.sizeList.forEach((item2, index2) => {
        let goods = null;
        if (item2.inputQuantity > 0) {
          goods = {
            goods_id: goodsInfo.goods_id,
            goods_code: goodsInfo.goods_code,
            color_id: item.color_id,
            size_id: item2.size_id,
            goods_number: item2.inputQuantity,
            goods_price: goodsInfo.goods_rprice,
            goods_tmoney: item2.inputQuantity * item2.inputQuantity,

            goods_name: goodsInfo.goods_name,
            img: goodsInfo.images[0].assist_url,
            sale_price: goodsInfo.goods_rprice,
            price: goodsInfo.goods_rprice,
            colorName: item.color_name,
            sizeName: item2.size_name,
            number: item2.inputQuantity,
          };
          goodsList.push(goods);
        }
      });
    });
    return goodsList;
  },

  /**
   * zll 计算商品价格
   */
  billPrice: function () {
    let _this = this;
    let goodsList = _this.data.goodsList;
    let goodsCount = 0;
    let goodsPrice = 0;
    goodsList.forEach((item, index) => {
      goodsCount = goodsCount + item.number;
      goodsPrice = goodsPrice + item.sale_price * item.number;
    });
    return {
      goodsCount: goodsCount,
      goodsPrice: goodsPrice
    };
  },
  //zll 显示价格框
  onNumberModalShow(e) {
    this.setData({
      hrnum_show: true,
      hrnum_value: e.currentTarget.dataset.price,
      editSaleMoneyIndex: e.currentTarget.dataset.index,
      editSaleMoneyId: e.currentTarget.dataset.id,
    });
  },
  //价格框 确认
  onNumberConfirm(e) {
    let _this = this;
    let list = _this.data.goodsList;
    list[_this.data.editSaleMoneyIndex].sale_price = e.detail;
    this.setData({
      hrnum_value: 0,
      goodsList: list
    });
  },
});

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('example1_new_heigth');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    // 页面总高度将会放在这里
    windowHeight: 0,
    // header的高度
    headerHeight: 0,
    // 工具栏的高度
    toolBarHeight: 0,
    // 底部工具栏的高度
    tabBarHeight: 0
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  // 先创建一个 SelectorQuery 对象实例
  let query = wx.createSelectorQuery().in(_this);
  // 然后逐个取出header、toolbar、tabBar的节点信息
  // 选择器的语法与jQuery语法相同
  query.select('#header').boundingClientRect();
  query.select('#tabBar').boundingClientRect();
  query.select('#toolbar').boundingClientRect(); //zll 工具栏信息
  // 执行上面所指定的请求，结果会按照顺序存放于一个数组中，在callback的第一个参数中返回
  query.exec((res) => {
    // console.log(res);
    data.headerHeight = res[0].height;
    data.tabBarHeight = res[1].height;
    // data.toolBarHeight = res[2].height; //zll 获取工具栏高度

    wx.setStorageSync('example1_new_heigth', data);
    _fun && _fun(data);
  });
};

/**
 * 构造编辑商品数据
 */
var builderEditGoodsData = () => {
  let item = {
    colorId: 0,
    checked: true,
    colorName: '颜色',
    stockQuantity: 0,
    inputQuantity: 0,
    sizeList: [{
        sizeName: '155/80A',
        stockQuantity: 10,
        inputQuantity: 0
      }, {
        sizeName: '160/84A',
        stockQuantity: 10,
        inputQuantity: 0
      },
      {
        sizeName: '165/88A',
        stockQuantity: 11,
        inputQuantity: 0
      },
      {
        sizeName: '170/92A',
        stockQuantity: 12,
        inputQuantity: 0
      },
      {
        sizeName: '175/96A',
        stockQuantity: 13,
        inputQuantity: 0
      },
      {
        sizeName: '180/100A',
        stockQuantity: 14,
        inputQuantity: 0
      },
      {
        sizeName: '180/100B',
        stockQuantity: 15,
        inputQuantity: 0
      },
    ]
  };
  let arr = [];
  let row;
  for (var i = 0; i < 6; i++) {
    row = JSON.parse(JSON.stringify(item));
    row.colorId = i + 1;
    row.colorName += i + 1;
    row.stockQuantity = i;
    row.checked = i == 0;
    row.sizeList[0].sizeName += i + 1;
    arr[i] = row;
  }
  return arr;
};