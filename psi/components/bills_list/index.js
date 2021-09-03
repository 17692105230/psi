// components/bills_list/index.js
const app = getApp();
Component({
  /**
   * 组件的属性列表
   */
  options: {
    addGlobalClass: true
  },
  properties: {
    loadOk: {
      type: Boolean,
      value: false,
    },
    height:{
      type:Number,
      value:0
    },
    nomore: {
      type: Boolean,
      value: false,
    },
    data_list: {
      type: Array,
      value: [],
      observer(newVal, oldVal) {}
    },
  },
  lifetimes: {
    created() {},
    attached() {
      console.log(this.properties.data_list)
    }
  },
  pageLifetimes: {
    show() {
      this.setData({
        active: ''
      })
    }
  },

  /**
   * 组件的初始数据
   */
  data: {
    list: [{
      id: 1,
      billno: 'DJ20201235468465',
      date: '2020-12-24',
      supplier: '北京供应商',
      warehouse: '石家庄仓库',
      number: 99,
      money: 5999.23,
      audit: '待审核'
    }],
  },

  /**
   * 组件的方法列表
   */
  methods: {
    delete(e) {
      console.log(this, 1)
      let orders_id = e.currentTarget.dataset.id;
      let orders_code = e.currentTarget.dataset.code;
      let lock_version = e.currentTarget.dataset.version;
      let detelTemp = {
        orders_id: orders_id,
        orders_code: orders_code,
        lock_version: lock_version
      }
      let _this = this;
      // this.triggerEvent("delete",{detelTemp});
      wx.showModal({
        title: '提示',
        content: '确定要删除该订单吗',
        success(res) {
          if (res.confirm) {
            _this.triggerEvent("delete", {
              detelTemp
            })
          } else if (res.cancel) {
            console.log('用户点击取消')
          }
        }
      })
    },
    details(e) {
      let qx = e.currentTarget.dataset.qx
      console.log(qx,'采购单编辑权限')
      if(!qx){
        app.toast('暂无权限')
        return
      }
      this.setData({
        active: ''
      })
      let id = e.currentTarget.dataset.id;
      let index = e.currentTarget.dataset.index;
      let ind = `active[${index}]`
      this.setData({
        [ind]: true
      })
      this.triggerEvent("details", id);
    },
  }
})