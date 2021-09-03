const app = getApp();
Component({
  /**
   * 组件的一些选项
   */
  options: {
    addGlobalClass: true,
    multipleSlots: true
  },
  /**
   * 组件的对外属性
   */
  properties: {
    BackPage: {
      type: String,
      default: ''
    },
    bgColor: {
      type: String,
      default: ''
    },
    isCustom: {
      type: [Boolean, String],
      default: false
    },
    bgImage: {
      type: String,
      default: ''
    },
    type: {
      type: [Boolean, String],
      default: ''
    },
    arrData: {
      type: Object,
      value: {
        show: false,
        text: ''
      }
    }
  },
  /**
   * 组件的初始数据
   */
  data: {
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom
  },
  /**
   * 组件的方法列表
   */
  methods: {
    BackPage() {
      if (this.data.BackPage) {
        wx.redirectTo({
          url: this.data.BackPage
        })

      } else {
        wx.navigateBack({
          delta: 1
        });
      }
    },
    toHome() {
      wx.reLaunch({
        url: '/pages/home/index',
      })
    },
    onShowSearch(e) {
      this.setData({
        'arrData.show': !this.data.arrData.show
      });
    },
    onSearch(e) {
      this.triggerEvent('Search', e.detail.value);
    }
  }
})