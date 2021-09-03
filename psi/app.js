var app = getApp();
/**
 * tabBar页面路径列表 (用于链接跳转时判断)
 * tabBarLinks为常量, 无需修改
 */
const tabBarLinks = [
  'pages/index/index',
  'pages/category/index',
  'pages/flow/index',
  'pages/user/index'
];
// 站点配置文件
import siteinfo from './siteinfo.js';
// 工具类
import util from './utils/util.js';
// 权限节点
import authority_node from './utils/authority_const.js'
App({
  util,
  onLaunch: function () {
    wx.getSystemInfo({
      success: e => {
        this.globalData.StatusBar = e.statusBarHeight;
        let custom = wx.getMenuButtonBoundingClientRect();
        this.globalData.Custom = custom;
        this.globalData.CustomBar = custom.bottom + custom.top - e.statusBarHeight;
      }
    });
    // 清除缓存
    wx.removeStorageSync('selected_goods_list_01');
    wx.removeStorageSync('sale_goods_list_01');
  },
  globalData: {
    user_id: null
  },
  // api地址
  // api_root: siteinfo.siteroot + 'index.php?s=/api/',
  api_root: siteinfo.siteroot,
  /**
   * 小程序启动场景
   */
  onStartupScene(query) {
    // 获取场景值
    let scene = this.getSceneData(query);
    // 记录推荐人id
    let refereeId = query.referee_id ? query.referee_id : scene.uid;
    refereeId > 0 && (this.saveRefereeId(refereeId));
  },

  // 获取商城ID
  getWxappId() {
    return siteinfo.uniacid || 10001;
  },

  /**
   * 记录推荐人id
   */
  saveRefereeId(refereeId) {
    if (!wx.getStorageSync('referee_id'))
      wx.setStorageSync('referee_id', refereeId);
  },

  /**
   * 获取场景值(scene)
   */
  getSceneData(query) {
    return query.scene ? util.scene_decode(query.scene) : {};
  },

  onShow(options) {
    // 获取小程序基础信息
    // this.getWxappBase();
  },

  /**
   * 当前用户id
   */
  getUserId() {
    return wx.getStorageSync('user_id');
  },

  /**
   * 显示成功提示框
   */
  showSuccess(msg, callback) {
    wx.showToast({
      title: msg,
      icon: 'success',
      mask: true,
      duration: 1500,
      success() {
        callback && (setTimeout(function () {
          callback();
        }, 1500));
      }
    });
  },

  //zll 消息提示,无图标,有回调
  showToastCollback(msg, callback) {
    wx.showToast({
      title: msg,
      icon: 'none',
      mask: true,
      duration: 1500,
      success() {
        callback && (setTimeout(function () {
          callback();
        }, 1500));
      }
    });
  },

  toast: function (text, duration, success) {
    wx.showToast({
      title: text,
      icon: success ? 'success' : 'none',
      duration: duration || 2000
    })
  },

  /**
   * 显示失败提示框
   */
  showError(msg, callback) {
    wx.showModal({
      title: '友情提示',
      content: msg || '未知异常',
      showCancel: false,
      success(res) {
        // callback && (setTimeout(function() {
        //   callback();
        // }, 1500));
        callback && callback();
      }
    });
  },
  // 封装console.log
  p(...v) {
    console.log(...v)
  },
  //检查是否录入了必要的基础信息
  checkNullData() {
    this._get('web/controller/checkNullData', {}, res => {
      if (res.errmsg != '1') {
        wx.hideLoading()
        wx.showModal({
          title: '提示',
          content: res.errmsg,
          success(res) {
            wx.navigateTo({
              url: '/pages/home/index',
            })
          }
        })
      }
    }, '', '', '', true)
  },
  //获取用户权限
  getUserAuthority(cb) {
    this._get('web/common/permission', {}, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      if (res.data.is_super_admin == 1) {
        let data = 'superAdmin'
        wx.setStorageSync('userAuthority', data)
        cb&&cb()
        return data
      }
      let data = res.data.node_list
      //给所有权限添加noHave字段  无权限,默认值为false
      let allData = res.data.all_node_list.map(i => {
        i.noHave = false;
        return i
      })

      //把无权限的节点 noHave值设为true
      for (let i of data) {
        let index = allData.findIndex(j => j.id == i.id)
        if (index != -1) {
          allData[index].noHave = true
        }
        
      }
      wx.setStorageSync('userAuthority', allData)
      cb && cb()

    })
  },
  //获取一些节点的权限
  getSomeNodeQx(arr) {
    let _arr = []
    for(let i of arr){
      _arr.push(authority_node[i])
    }
    arr = _arr
    let nodes_list = {

    }
    let userAuthority = wx.getStorageSync('userAuthority')
    let res = []

    if (userAuthority == 'superAdmin') { //如果是超级管理员,则拥有所有权限
      for (let _i of arr) {
        res.push({
          haveQx: true,
          node_name: '超级管理员'
        })
      }
      return res
    }

    for (let _i of arr) {
      var qx = false
      var haveQx = true
      var index = userAuthority.findIndex(i => i.id == _i)
      qx = userAuthority[index].noHave
      if (qx) { //无权限
        haveQx = false
      }
      res.push({
        haveQx,
        node_name: userAuthority[index].node_name
      })
    }
    return res
  },

  /**
   * get请求
   */
  _get(url, data, success, fail, complete, check_login, noLOading) {
    wx.showNavigationBarLoading();
    let _this = this;
    // 构造请求参数
    data = data || {};
    data.wxapp_id = _this.getWxappId();
    data.token = wx.getStorageSync('token');
    data.com_id = wx.getStorageSync('com_id');

    // 构造get请求
    let request = function () {
      if (!noLOading) {
        wx.showLoading({
          title: '加载中',
          mask: true
        });
      }
      // console.log(data,'我是get请求提交的数据')

      wx.request({
        url: _this.api_root + url,
        header: {
          'content-type': 'application/json'
        },
        data: data,
        success(res) {
          if (res.data.errcode === 9999) {
            _this.redirectLogin()
            return
          }
          if (!noLOading) {
            wx.hideLoading();
          }
          if (res.data.errcode === -1) {
            console.log("重新授权登录");
            // 登录态失效, 重新登录
            wx.hideNavigationBarLoading();
            _this.doLogin();
          } else if (res.data.errcode === 1) {
            _this.showError(res.data.errmsg, function () {
              fail && fail(res);
            });
            return false;
          } else {
            setTimeout(function(){
              success && success(res.data);
            },200)
          }
        },
        fail(res) {
          wx.hideLoading();
          _this.showError('网络请求出错', function () {
            fail && fail(res);
          });
        },
        complete(res) {
          wx.hideNavigationBarLoading();
          complete && complete(res);
        },
      });
    };
    // 判断是否需要验证登录
    check_login ? _this.doLogin(request) : request();
  },

  /**
   * post提交
   */
  _post_form(url, data, success, fail, complete, isShowNavBarLoading) {
    let _this = this;
    isShowNavBarLoading || true;
    data.wxapp_id = _this.getWxappId();
    data.token = wx.getStorageSync('token');
    data.com_id = wx.getStorageSync('com_id');
    // 在当前页面显示导航条加载动画
    if (isShowNavBarLoading == true) {
      wx.showNavigationBarLoading();
    }
    wx.showLoading({
      title: '加载中',
      mask: true
    });
    console.log(data, '我是form请求提交的数据')

    wx.request({
      url: _this.api_root + url,
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      method: 'POST',
      data: data,
      success(res) {
        wx.hideLoading();
        if (res.data.errcode === 9999) {
          _this.redirectLogin()
          return
        }
        // if (res.statusCode !== 200 || typeof res.data !== 'object') {
        //   _this.showError('网络请求出错');
        //   return false;
        // }
        if (res.data.code === -1) {
          // 登录态失效, 重新登录
          wx.hideNavigationBarLoading();
          _this.doLogin();
          return false;
        } else if (res.data.errcode === 1) {
          _this.showError(res.data.errmsg, function () {
            fail && fail(res);
          });
          return false;
        }
      
        setTimeout(function(){
          success && success(res.data);
        },200)
      
      },
      fail(res) {
        wx.hideLoading();
        // console.log(res);
        _this.showError('网络请求出错', function () {
          fail && fail(res);
        });
      },
      complete(res) {
        wx.hideLoading();
        wx.hideNavigationBarLoading();
        complete && complete(res);
      }
    });
  },

  /**
   * 跳转登录页面
   */
  redirectLogin() {
    wx.setStorageSync('token', '')
    wx.setStorageSync('isLoad', false)
    let isRedirectLogin = this.isRedirectLogin()
    if (isRedirectLogin) {
      return;
    }
    wx.showToast({
      title: '登录过期,请重新登录',
      icon: 'none'
    })
    // 登录标识不存在,则登录
    wx.setStorageSync('isRedirectLogin', 1)
    setTimeout(function () {
      wx.navigateTo({
        url: '/pages/home/index?type=login',
      })
      wx.setStorageSync('isRedirectLogin', 0)
    }, 1500)
  },

  /**
   * 是否跳转到登录页面
   */
  isRedirectLogin() {
    let exist = wx.getStorageSync('isRedirectLogin');
    if (exist) {
      return true
    }
    return false
  },

  /**
   * 验证是否存在user_info
   */
  validateUserInfo() {
    let user_info = wx.getStorageSync('user_info');
    return !!wx.getStorageSync('user_info');
  },

  /**
   * 小程序主动更新
   */
  updateManager() {
    if (!wx.canIUse('getUpdateManager')) {
      return false;
    }
    const updateManager = wx.getUpdateManager();
    updateManager.onCheckForUpdate(function (res) {
      // 请求完新版本信息的回调
      // console.log(res.hasUpdate)
    });
    updateManager.onUpdateReady(function () {
      wx.showModal({
        title: '更新提示',
        content: '新版本已经准备好，即将重启应用',
        showCancel: false,
        success(res) {
          if (res.confirm) {
            // 新的版本已经下载好，调用 applyUpdate 应用新版本并重启
            updateManager.applyUpdate()
          }
        }
      });
    });
    updateManager.onUpdateFailed(function () {
      // 新的版本下载失败
      wx.showModal({
        title: '更新提示',
        content: '新版本下载失败',
        showCancel: false
      })
    });
  },

  /**
   * 获取tabBar页面路径列表
   */
  getTabBarLinks() {
    return tabBarLinks;
  },

  /**
   * 跳转到指定页面
   * 支持tabBar页面
   */
  navigationTo(url) {
    if (!url || url.length == 0) {
      return false;
    }
    let tabBarLinks = this.getTabBarLinks();
    // tabBar页面
    if (tabBarLinks.indexOf(url) > -1) {
      wx.switchTab({
        url: '/' + url
      });
    } else {
      // 普通页面
      wx.navigateTo({
        url: '/' + url
      });
    }
  },

  /**
   * 记录formId
   */
  saveFormId(formId) {
    let _this = this;
    console.log('saveFormId');
    if (formId === 'the formId is a mock one') {
      return false;
    }
    _this._post_form('wxapp.formId/save', {
      formId: formId
    }, null, null, null, false);
  },

  /**
   * 生成转发的url参数
   */
  getShareUrlParams(params) {
    let _this = this;
    return util.urlEncode(Object.assign({
      referee_id: _this.getUserId()
    }, params));
  },

  /**
   * 发起微信支付
   */
  wxPayment(option) {
    let options = Object.assign({
      payment: {},
      success: () => {},
      fail: () => {},
      complete: () => {},
    }, option);
    wx.requestPayment({
      timeStamp: options.payment.timeStamp,
      nonceStr: options.payment.nonceStr,
      package: 'prepay_id=' + options.payment.prepay_id,
      signType: 'MD5',
      paySign: options.payment.paySign,
      success(res) {
        options.success(res);
      },
      fail(res) {
        options.fail(res);
      },
      complete(res) {
        options.complete(res);
      }
    });
  },
  //消息提示框无图标
  showToast(img) {
    wx.showToast({
      title: img,
      icon: 'none',
      duration: 2000
    })
  },
  /**
   * 授权登录
   */
  getUserInfo(e, callback) {
    let App = this;
    console.log(e, 333)
    if (e.errMsg !== 'getUserProfile:ok') {
      return false;
    }
    console.log(e);
    wx.showLoading({
      title: "正在登录",
      mask: true
    });
    // 执行微信登录
    wx.login({
      success(res) {
        // 发送用户信息
        App._post_form('web/user/authorize', {
          code: res.code, //
          user_info: e.rawData,
          encrypted_data: e.encryptedData, //
          iv: e.iv, //
          signature: e.signature, //
          referee_id: wx.getStorageSync('referee_id'),
          user_id: wx.getStorageSync('user_id'),
          open_id: wx.getStorageSync('open_id'),
        }, result => {
          // 执行回调函数
          callback && callback();
        }, false, () => {
          wx.hideLoading();
        });
      }
    });
  },
  // 格式化时间
  formatTime(myDate) {
    // var myDate = new Date();
    var month = myDate.getMonth() + 1;
    var day = myDate.getDate();
    month = (month.toString().length == 1) ? ("0" + month) : month;
    day = (day.toString().length == 1) ? ("0" + day) : day;
    var result = myDate.getFullYear() + '-' + month + '-' + day;
    return result;
  },



})