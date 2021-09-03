const app = getApp();
Page({
  data: {
    dropShowTop: '300rpx',
    search: '',
    dropShow: [false, false],
    TabCur: -1,
    navList: [{
        name: '商品分类',
        ico: 'cuIcon-triangledownfill' //
      },
      {
        name: '商品状态',
        ico: 'cuIcon-triangledownfill'
      },
    ],
    TabCur: 0,
    category: [],
    categoryArr: [],
    categoryIndex: 0,
    goodsStatus: [{
        id: 0,
        name: '全部',
        checked: true,
      },
      {
        id: 1,
        name: '上架',
        checked: false,
      },
      {
        id: -1,
        name: '下架',
        checked: false,
      },
    ],
    goodsStatusIndex: 0,
    goodsStatusSelect: 0,
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    zixun: [{
      title: '不管这世界阴晴圆缺只愿和春天有个约会,管这世界阴晴圆缺只愿和春天有个约会',
      tag: '生活主题',
      url: ''
    }, {
      title: '不管这世界阴晴圆缺只愿和春天有个约会,管这世界阴晴圆缺只愿和春天有个约会',
      tag: '居家攻略',
      url: ''
    }, {
      title: '不管这世界阴晴圆缺只愿和春天有个约会,管这世界阴晴圆缺只愿和春天有个约会',
      tag: '周边好货',
      url: ''
    }],
    goodsList: [],
    triggered: true, // scroll-view 刷新状态
    nowPage: 0,
    allPage: 1,
    nomore: false,
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },

  //显示分类
  navChange(e) {
    let i = e.currentTarget.dataset.index

    let dropShow, dropShowOld
    if (i) {
      dropShow = `dropShow[1]`
      dropShowOld = `dropShow[0]`
    } else {
      dropShow = `dropShow[0]`
      dropShowOld = `dropShow[1]`
    }

    this.setData({
      [dropShowOld]: false,
      [dropShow]: !this.data.dropShow[i],
      TabCur: i,
    })
  },
  //筛选
  dropShowCheckBox(e) {
    let i = e.currentTarget.dataset.index
    if (!this.data.TabCur) { //分类
      let checked = `category[${i}].checked`
      let bool = this.data.category[i].checked
      this.setData({
        [checked]: !bool
      })
    } else { //状态
      let checked = `goodsStatus[${i}].checked`
      let goodsStatus = this.data.goodsStatus
      let bool = this.data.goodsStatus[i].checked
      for (let i of goodsStatus) {
        i.checked = false
      }
      this.setData({
        goodsStatus,
        [checked]: !bool
      })
    }
  },
  //确定筛选
  dropConfirm() {
    //获取商品状态选项
    let goodsStatusSelect = this.data.goodsStatus.filter(i => i.checked).map(a => a.id)
    if (goodsStatusSelect.length > 0) {
      goodsStatusSelect = goodsStatusSelect[0]
    } else {
      goodsStatusSelect = 0
    }
    this.setData({
      categoryArr: this.data.category.filter(i => i.checked).map(a => a.id),
      goodsStatusSelect,
      dropShow: [false, false],
      nowPage: 0,
      allPage: 1,
      nomore: false,
      // goodsList: [],
      loadOk: true,
    })
    this.loadGoodsWX(true);
  },
  //隐藏删选
  dropHide() {
    this.setData({
      'dropShow[0]': false,
      'dropShow[1]': false,
    })
  },
  //重置
  dropReset() {
    if (this.data.TabCur == 0) { //分类
      let category = this.data.category.map(i => {
        i.checked = false
        return i
      })
      let categoryArr = this.data.category.map(i => {
        i.checked = false
        return i.id
      })
      this.setData({
        category,
        categoryArr,
      })
    }
    if (this.data.TabCur == 1) { //状态
      let goodsStatus = this.data.goodsStatus
      for (let i of goodsStatus) {
        i.checked = false
      }
      goodsStatus[0].checked = true
      this.setData({
        goodsStatus,
        goodsStatusSelect: 0,
      })
    }
  },

  onShow() {
    //获取权限
    let qxList = app.getSomeNodeQx(['商品新增', '商品编辑'])
    this.setData({
      qxList
    })
    let _this = this
    setTimeout(function(){
      builderPageHeight(_this, function (data) {
        _this.setData({
          saleScrollViewHeight: data.windowHeight - data.headerHeight - data.navBarHeight,
          dropShowTop: data.headerHeight + data.navBarHeight + 'px'
        });
        _this.setData({
          isRefresher: true
        });
      });
    },500);

    this.setData({
      goodsList: [],
      loadOk: false,

      nowPage: 0,
      nomore: false,
      allPage: 1,
    })
    app.util.getAllList(['category']).then(r => {
      let category = r.category
      let categoryArr = []
      for (let i of category) {
        i.checked = true
        categoryArr.push(i.id)
      }
      _this.setData({
        category,
        categoryArr,
      })
      this.loadGoodsWX();

    })
  },
  // 列表数据请求
  loadGoodsWX(isSearch) {
    let _this = this;
    if (this.data.nowPage >= this.data.allPage) {
      this.setData({
        nomore: true
      })
      return
    }
    let search = _this.data.search,
      category = _this.data.categoryArr,
      goods_status = _this.data.goodsStatusSelect
    if (category.length == 0) { //r如果一个商品分类都没选,则默认查所有
      category = this.data.category.filter(i => i.checked).map(a => a.id)
    }
    category = category.join(',')
    app._get('web/goods/loadGoodsWX', {
      page: _this.data.nowPage + 1,
      search,
      category,
      goods_status,
    }, function (res) {
      if (res.errcode != 0) {
        app.showToast(res.errmsg);
      }
      _this.setData({
        goodsList: isSearch ? res.data.data : [..._this.data.goodsList, ...res.data.data],
        loadOk: true,
        allPage: res.data.last_page,
        nowPage: _this.data.nowPage + 1,
        search:'',
        categoryArr:[],
        goodsStatusSelect:0
      })
    })
  },
  loadMore() {
    this.loadGoodsWX();
  },
  onReachBottom() {
    let nowPage = this.data.nowPage
    let allPage = this.data.allPage
    if (nowPage >= allPage) {
      this.setData({
        nomore: true
      })
      return
    }
    this.loadGoodsWX()

  },
  onChangeModel(e) {
    this.setData({
      MsTabCur: e.currentTarget.dataset.index
    });
    console.log(this.data.MsTabCur);
  },
  //跳转到商品详情
  todetail: function () {
    wx.navigateTo({
      url: '/pages/example2/index',
    })
  },
  //点击添加商品
  addGoods: function () {
    if (!this.data.qxList[0].haveQx) {
      app.toast('暂无权限')
      return
    }
    wx.navigateTo({
      url: '/pages/example2/index?type=save',
    })
  },
  //返回上一页
  back: function () {
    wx.navigateBack({
      delta: 1,
    })
  },
  //点击 商品列表组件
  onTapGoods: function (e) {
    if(this.data.dropShow[0]||this.data.dropShow[1]){ //遮罩状态
      return
    }
    if (!this.data.qxList[1].haveQx) {
      app.toast('暂无权限')
      return
    }
    wx.navigateTo({
      url: '/pages/example2/index?type=edit&&goods_id=' + e.detail.goods_id,
    })
  },
  //商品列表 下拉刷新
  // onrefresh:function(){
  //   let _this = this;
  //   setTimeout(function(){
  //     _this.setData({
  //       triggered:false
  //     });
  //   },2000);
  // },


})

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('example6_heigth');
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
    //导航栏的高度
    navBarHeight: 0,
    // 底部工具栏的高度
    tabBarHeight: 0
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#header').boundingClientRect();
  query.select('#navBar').boundingClientRect();
  query.exec((res) => {
    data.headerHeight = res[0].height;
    data.navBarHeight = res[1].height;
    console.log("navbar:"+res[1].height);
    wx.setStorageSync('example6_heigth', data);
    _fun && _fun(data);
  });
};