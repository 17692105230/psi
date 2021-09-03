const app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    syQx: {
      haveQx: true
    },
    isLoad: true, //是否登录
    isRegister: false, //显示注册或登录表单
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    //功能列表 下边的宫格 
    newIconList: [],
    gridCol: 4,
    cardCur: 0,
    see: 1, //金额是否可见
    user_name: '',
    l_password: ''

  },
  onLoad(o) {
    let _this = this;
    //判断是否授权
    if (o.type == 'login') {
      _this.setData({
        isLoad: false
      })
      return
    }
    wx.login({
      success(res) {
        // 发送用户信息
        app._post_form('web/user/is_authorize', {
          code: res.code, //
        }, res => {
          // 执行回调函数
          if (res.errcode != 0) { //去登录
            wx.setStorageSync('isLoad', false)
            _this.setData({
              isLoad: false
            })
          } else {
            //已授权,直接进入小程序
            if (res.data.token) {
              wx.setStorageSync('token', res.data.token)
              wx.setStorageSync('com_id', res.data.com_id)
              wx.setStorageSync('open_id', res.data.open_id)
              wx.setStorageSync('user_id', res.data.user_id)
              wx.setStorageSync('user_idcode', res.data.user_idcode)
              wx.setStorageSync('user_name', res.data.user_name)
              wx.setStorageSync('user_phone', res.data.user_phone)
            }


            //获取权限
            let token = wx.getStorageSync('token')
            if (token) {
              wx.setStorageSync('isLoad', true)
              _this.setData({
                isLoad: true
              })
              app.getUserAuthority()
            } else {
              wx.setStorageSync('isLoad', false)
              _this.setData({
                isLoad: false
              })
            }
          }
        });
      }
    });
  },
  onShow() {
    let token = wx.getStorageSync('token')
    if (this.data.isLoad && token) {
      this.getQxCb()
      this.getIndexData()
    }
  },
  getQxCb() {
    let _this = this
    //设置权限
    let qxList = app.getSomeNodeQx([
      '进货', '销售', '库存', '财务', '采购单列表',
      '采购单新增', '销售单列表', '销售单新增', '盘点单列表', '盘点单新增',
      '调拨单列表', '调拨单新增', '收银台', '客户列表', '结算账户列表',
      '供应商列表', '尺码列表', '色码列表', '分类列表', '会员列表',
      '商品列表', '报表管理'
    ])
    _this.setData({
      qxList,

      iconList: [{
          icon: 'recharge',
          color: 'orange',
          badge: 1,
          url: '/pages/example1_new/index',
          name: '收银台',
          qx: qxList[12]
        },
        {
          icon: 'picfill',
          color: 'yellow',
          badge: 0,
          url: '/pages/example4/list/list',
          name: '采购单',
          qx: qxList[4]
        },
        {
          icon: 'upstagefill',
          color: 'cyan',
          badge: 0,
          url: '/pages/common/common_list/index?type=dbd',
          name: '调拨单',
          qx: qxList[10]
        },
        {
          icon: 'friend',
          color: 'orange',
          badge: 0,
          url: '/pages/client_base/index',
          name: '客户',
          qx: qxList[13]
        }, {
          icon: 'goods',
          color: 'red',
          badge: 0,
          url: '/pages/count_ account/index',
          name: '结算账户',
          qx: qxList[14]
        },

        {
          icon: 'friend',
          color: 'red',
          badge: 0,
          url: '/pages/supplier/index',
          name: '供应商',
          qx: qxList[15]
        }, {
          icon: 'friend',
          color: 'red',
          badge: 0,
          url: '/pages/settings/size/index',
          name: '尺码管理',
          qx: qxList[16]
        }, {
          icon: 'friend',
          color: 'red',
          badge: 0,
          url: '/pages/settings/color/index',
          name: '色码管理',
          qx: qxList[17]
        }, {
          icon: 'friend',
          color: 'red',
          badge: 0,
          url: '/pages/goods_type/index',
          name: '商品分类',
          qx: qxList[18]
        },
        {
          icon: 'friend',
          color: 'red',
          badge: 0,
          url: '/pages/member/index',
          name: '会员',
          qx: qxList[19]
        },
      ],
      //快捷功能列表 中间4个
      quickList: [{
          name: '采购单',
          introduce: '从供应商处进货',
          icon: 'cuIcon-edit',
          listpage: '/pages/example4/list/list',
          addpage: '/pages/example4/index',
          isadd: true,
          qx: qxList[4],
          addqx: qxList[5]
        },
        {
          name: '销售单',
          introduce: '向批发客户销售',
          icon: 'cuIcon-scan',
          listpage: '/pages/sale/sale_order_list/index',
          addpage: '/pages/sale/sale_order/index',
          isadd: true,
          qx: qxList[6],
          addqx: qxList[7]
        },
        {
          name: '盘点单',
          introduce: '盘点商品实际库存',
          icon: 'cuIcon-edit',
          listpage: `/pages/common/common_list/index?type=pdd`,
          addpage: '/pages/stock/check_order/index',
          isadd: true,
          qx: qxList[8],
          addqx: qxList[9]
        },
        {
          name: '调拨单',
          introduce: '商品调转仓库',
          icon: 'cuIcon-edit',
          listpage: `/pages/common/common_list/index?type=dbd`,
          addpage: '/pages/stock/allot_order/index',
          isadd: true,
          qx: qxList[10],
          addqx: qxList[11]
        },
      ],

    })
    _this.initIconBtn();
  },
  //获取首页数据
  getIndexData() {
    let _this = this
    app._get("web/common/sale_volume", {}, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        todayMoney: res.data.today,
        yesterdayMoney: res.data.yesterday,
      })
    })

    app._get("web/common/news", {}, res => {
      if (res.errcode != 0) {
        wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
        _this.setHeight()
        return
      }
      _this.setData({
        tj_title: res.data.title,
      })
      _this.setHeight()
    })
  },
  toUrl: function (e) {
    let qx = e.currentTarget.dataset.qx
    let haveQx = qx.haveQx
    console.log(qx, haveQx, 996)
    if (haveQx) {
      wx.navigateTo({
        url: e.currentTarget.dataset.url,
      })
    } else {
      app.toast('暂无权限')
    }

  },
  //zll 初始化常用功能的按钮数组
  initIconBtn: function () {
    let _this = this;
    let iconList = _this.data.iconList;
    //处理功能按钮,改为二维数组
    let newList = [];
    let linshiList = [];
    iconList.forEach((item, index) => {
      linshiList.push(item);
      if (linshiList.length % 10 == 0 || ((index + 1) == iconList.length)) {
        newList.push(linshiList);
        // console.log(linshiList);
        linshiList = [];
      }
    });
    _this.setData({
      newIconList: newList
    });
  },
  //金额 可见 不可见
  seeDo: function () {
    let _this = this;
    _this.setData({
      see: !_this.data.see
    });
  },

  //注册
  register() {
    let _this = this
    let d = this.data
    let company_name = d.company_name,
      company_phone = d.company_phone,
      company_contact = d.company_contact,
      user_name = d.user_name,
      password = d.password,
      idcode = d.idcode,
      user_phone = d.user_phone

    if (company_name && company_phone && company_contact && user_name && password && idcode && user_phone) {
      let mobilStr = /^1[3456789][0-9]{9}$/;
      if (!mobilStr.test(company_phone)) {
        app.toast("请输入正确的联系电话");
        return false;
      }
      if (!mobilStr.test(user_phone)) {
        app.toast("请输入正确的用户联系电话");
        return false;
      }
      if (password.length < 6) {
        app.toast("密码最少需要6位~");
        return false;
      }
      if (user_name.length < 2) {
        app.toast("用户名最少需要2位~");
        return false;
      }
      //注册
      app._post_form('web/user/register', {
        company_name,
        company_phone,
        company_contact,
        user_name,
        password,
        idcode,
        user_phone
      }, res => {
        if (res.errcode != 0) {
          app.showToast(res.errmsg);
          return;
        }
        app.toast('注册成功')
        wx.setStorageSync('token', res.data.token)
        wx.setStorageSync('user_id', res.data.user_id)
        wx.setStorageSync('open_id', res.data.open_id)
        wx.setStorageSync('com_id', res.data.com_id)
        wx.setStorageSync('user_idcode', res.data.user_idcode)
        wx.setStorageSync('user_name', res.data.user_name)
        wx.setStorageSync('user_phone', res.data.user_phone)
        wx.setStorageSync('isLoad', true)
        setTimeout(function () {
          _this.setData({
            isLoad: true,
          })
          _this.userInfo(res.data.open_id)
          //获取权限
          app.getUserAuthority()
        }, 800)
      })
    } else {
      app.toast('请完整填写所有数据')
      return false;
    }
  },
  //登录
  login() {
    let _this = this
    let user_name = this.data.l_user_name,
      password = this.data.l_password
    if (user_name.length == 0) {
      return app.toast('请输入用户名')
    }
    if (password.length == 0) {
      return app.toast('请输入密码')
    }
    app._get('web/user/normal_login', {
      user_name,
      password
    }, res => {
      if (res.errcode != 0) {
        app.showToast(res.errmsg);
        return;
      }
      app.toast('登录成功')
      wx.setStorageSync('token', res.data.token)
      wx.setStorageSync('com_id', res.data.com_id)
      wx.setStorageSync('user_id', res.data.user_id)
      wx.setStorageSync('user_idcode', res.data.user_idcode)
      wx.setStorageSync('user_name', res.data.user_name)
      wx.setStorageSync('user_phone', res.data.user_phone)
      wx.setStorageSync('open_id', res.data.open_id)
      wx.setStorageSync('isLoad', true)
      setTimeout(function () {
        _this.setData({
          isLoad: true,
        })
        _this.userInfo(res.data.open_id)
        //获取权限
        app.getUserAuthority()
      }, 800)
    })
  },
  //用户授权
  userInfo(open_id) {
    //已授权
    if (open_id) {
      wx.setStorageSync('isLoad', true)

      this.setData({
        isLoad: true
      })
    } else { //未授权
      wx.navigateTo({
        url: '/pages/login/login',
      })
    }

  },
  //切换登录注册表单
  toggleForm() {
    this.setData({
      isRegister: !this.data.isRegister
    })
  },
  //设置高度
  setHeight() {
    let _this = this
    builderPageHeight(this, function (data) {
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.headerHeight - data.tabBarHeight
      });
      _this.setData({
        isRefresher: true
      });
    });
  },



})
const builderPageHeight = (_this, _fun) => {
  let data = null;
  data = {
    windowHeight: 0,
    headerHeight: 0,
    tabBarHeight: 0
  };
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#header').boundingClientRect();
  query.select('#tabBar').boundingClientRect();
  query.exec((res) => {
    data.headerHeight = res[0].height;
    data.tabBarHeight = res[1].height;
    wx.setStorageSync('home_height', data);
    _fun && _fun(data);
  });
};