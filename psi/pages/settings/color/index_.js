const app = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    CustomBar: app.globalData.CustomBar,
    isColorShow: false,
    list: [{
      value: 0,
      name: '宝蓝色',
      code: 'C01',
      checked: false,
    }, {
      value: 1,
      name: '玫红色',
      code: 'C02',
      checked: true
    }, {
      value: 2,
      name: '祖母绿',
      code: 'C03',
      checked: true
    }, {
      value: 3,
      name: '藏蓝色',
      code: 'C03',
      checked: false
    }, {
      value: 4,
      name: '白色',
      code: 'C04',
      checked: false
    }, {
      value: 5,
      name: '黑色',
      code: 'C05',
      checked: false
    }]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(options) {

  },

  /**
   * 添加颜色
   */
  onShowColorAdd(e) {
    this.setData({
      isColorShow: true
    });
  },

  onHideColorAdd(e) {
    this.setData({
      isColorShow: false
    });
  }
})