const App = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    options: {}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options) {
    let _this = this;
    _this.setData({
      options
    });
  },


    //新的授权登录接口
    getUserProfile(e){
      let _this = this;
      wx.getUserProfile({
        desc:'用来完善用户资料',
        success(e){
          wx.setStorageSync('avatarUrl', e.userInfo.avatarUrl)
          App.getUserInfo(e, () => {
          _this.onNavigateBack(1);
        });
        },
      });
    },

  /**
   * 暂不登录
   */
  onNotLogin() {
    let _this = this;
    // 跳转回原页面
    _this.onNavigateBack(_this.data.options.delta);
  },

  /**
   * 授权成功 跳转回原页面
   */
  onNavigateBack(delta) {
    wx.navigateBack({
      delta: Number(delta || 1)
    });
  },

})