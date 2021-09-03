// components/bills_list_sale/index.js
const app = getApp();
Component({
  /**
   * 组件的属性列表
   */
  options: {
    addGlobalClass: true
  },
  properties: {
    loadOk:{
type:Boolean,value:false,
    },
    dataList: { // 属性名
        type: Array,
        value: []
      },
  },
  lifetimes:{
    created(){
     
    },
    ready(){
      
    },
    attached(){
      
    },
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
    list:[
      {
        orders_id:1,
        orders_code:'DJ20201235468465',
        orders_date:'2020-12-24',
        client_name:'北京供应商',
        org_name:'石家庄仓库',
        goods_number:99,
        orders_rmoney:5999.23,
        audit:'待审核'
      }
    ],
  },

  /**
   * 组件的方法列表
   */
  methods: {
    delete(e){
      let orders_id = e.currentTarget.dataset.id;
      let orders_code = e.currentTarget.dataset.code;
      let lock_version = e.currentTarget.dataset.version;
      let detelTemp = {
        orders_id:orders_id,
        orders_code:orders_code,
        lock_version:lock_version
      }
      let _this = this;
      console.log(this,111)
      wx.showModal({
        title: '提示',
        content: '确定要删除该订单吗',
        success (res) {
          if (res.confirm) {
            _this.triggerEvent("delete",{detelTemp})
          } else if (res.cancel) {
            console.log('用户点击取消')
          }
        }
      })
    },
    details(e){
      let qx = e.currentTarget.dataset.qx
      if(!qx){
        app.toast('暂无权限')
        return
      }
      this.setData({
        active: ''
      })
      let id = e.currentTarget.dataset.id;
      let orders_type = e.currentTarget.dataset.orders_type;
      let index = e.currentTarget.dataset.index;
      let ind = `active[${index}]`
      this.setData({
        [ind]: true
      })
      this.triggerEvent("details",{id,orders_type});
    }
  }
})
