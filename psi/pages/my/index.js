let app = getApp()
//获取权限节点 [权限管理]

Page({

  /**
   * 页面的初始数据
   */
  data: {
    list: [{
        icon: 'cuIcon-people',
        name: '员工账号/权限',
        url: '/pages/authority_management/index',
        introduce: '员工,业务员,账号,权限',
      }
    ]
  },
  onLoad: function (options) {
    this.setData({
      user_phone: wx.getStorageSync('user_phone'),
      user_name: wx.getStorageSync('user_name'),
    })
  },
  onShow() {
    //设置权限  [权限管理]
    let qxList = app.getSomeNodeQx(['权限管理'])
    let setHaveQx = `list[0].haveQx`
    this.setData({
      [setHaveQx]: qxList[0].haveQx
    })
  },
  //跳转页面
  goUrl: function (e) {
    console.log(e, 888)
    wx.navigateTo({
      url: e.currentTarget.dataset.url,
    })
  },
  //清除缓存
  clearStorage(){
    wx.showModal({
      title: '是否清除',
      content: '清除缓存后可能需要重新登录',
      success (res) {
        if (res.confirm) {
          wx.clearStorageSync()
          app.toast('清除缓存成功')
          setTimeout(function(){
            wx.redirectTo({
              url: '/pages/home/index',
            })
          },800)
        }
      }
    })
  }
})