let app = getApp()
Component({
  options: {
    addGlobalClass: true,
  },
  /**
   * 组件的属性列表
   */
  properties: {
    // 列表表头文字
    listHead: {
      type: Array,
      value: [],
    },
    listFoot: {
      type: Array,
      value: [],
    },
    // 列表数据
    list: {
      type: Array,
      value: [],
    },
    // 列表数据的字段名
    variateList: {
      type: Array,
      value: [],
    },
    // 上下滚动区域的高度
    scroll_height: {
      type: String,
      value: ''
    },
    nomore: {
      type: Boolean,
      value: false
    }
  },

  /**
   * 组件的初始数据
   */
  data: {

  },

  /**
   * 组件的方法列表
   */
  methods: {
    // 同步滑动
    scroll(e) {
      this.setData({
        scrollLeft: e.detail.scrollLeft
      })
    },
    tolower_child() {
      this.triggerEvent('tolower_parentEvent')
    },
    to(e) {
      let qxList = app.getSomeNodeQx(['采购单编辑','销售单编辑','盘点单编辑','调拨单编辑'])
      console.log(qxList)
      let id = e.currentTarget.dataset.id
      let type = e.currentTarget.dataset.type
      let code = e.currentTarget.dataset.code
      let url = ''
      if (type == 'cgd') {
        if (!qxList[0].haveQx) {
          app.toast('暂无权限')
          return
        }
        url = `/pages/example4/index?orders_code=${code}`
      }
      if (type == 'xsd') {
        if (!qxList[1].haveQx) {
          app.toast('暂无权限')
          return
        }
        url = `/pages/sale/sale_order/index?id=${id}`

      }
      if (type == 'pdd') {
        if (!qxList[2].haveQx) {
          app.toast('暂无权限')
          return
        }
        url = `/pages/stock/check_order/index?id=${id}`

      }
      if (type == 'dbd') {
        if (!qxList[3].haveQx) {
          app.toast('暂无权限')
          return
        }
        url = `/pages/stock/allot_order/index?id=${id}`
      }
      wx.navigateTo({
        url,
      })
    }
  }
})