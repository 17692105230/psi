const app = getApp();
Page({
  data: {
    loadOk: false,
    last_page: 1, //总页数
    now_page: 1, //当前页
    data_list: [],
    a: []
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad: function (options) {
    // 0.新增 1.编辑 
    let qxList = app.getSomeNodeQx(['会员新增', '会员编辑'])
    this.setData({
      qxList
    })
    let _this = this
    builderPageHeight(this, function (data) {
      console.log(data);
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.searchBarHeight  -data.footBarHeight - app.globalData.CustomBar
      });
    });
  },
  onShow: function () {
    this.setData({
      now_page: 1,
      data_list: [],
      loadOk: false,
    })
    this.getList()
  },
  getList(txt) {//txt不等于 '下拉刷新' 则是搜索
    let data = {}
    data.page = this.data.now_page
    if (txt&&txt!='下拉刷新') {
      data.search_data = txt
    }
    app._get("/web/MemberBase/getListData", data, (res) => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg
        });
      }
      let data = res.data
      this.setData({
        data_list:txt?data.data:[...this.data.data_list, ...data.data],
        last_page: data.last_page,
        now_page: ++this.data.now_page,
        loadOk: true,
        triggered: false,
        search:''
      });
    });
  },
  search() {
    if (this.data.search) {
      this.getList(this.data.search)
    }
  },
  // 下拉刷新
  onRefresh() {
    this.setData({
      last_page: 1,
      now_page: 1,
      nomore: false,
      loadOk: false,
    })
    this.getList('下拉刷新')
  },
  //触底加载更多
  tolower() {
    if (this.data.now_page <= this.data.last_page) {
      this.getList()
    } else {
      this.setData({
        nomore: true,
      })
    }
  },
  //新增
  add() {
    wx.navigateTo({
      url: '/pages/member/edit',
    })
  },
  //编辑
  onEdit(e) {
    let qx = e.currentTarget.dataset.qx
    if(!qx){
      return
    }
    let id = e.currentTarget.dataset.member_id
    wx.navigateTo({
      url: '/pages/member/edit?id=' + id
    })
  },
  //详情
  onDetail(e){
    let id = e.currentTarget.dataset.member_id
    wx.navigateTo({
      url: '/pages/member/detail?id=' + id
    })
  }
})

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('member__height_list');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    windowHeight: 0,
    searchBarHeight: 0,
    footBarHeight:0,
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#searchBar').boundingClientRect();
  query.select('#footBar').boundingClientRect();

  query.exec((res) => {
    console.log(res, 999000999)
    data.searchBarHeight = res[0].height;
    data.footBarHeight = res[1].height;
    wx.setStorageSync('member__height_list', data);
    _fun && _fun(data);
  });
};