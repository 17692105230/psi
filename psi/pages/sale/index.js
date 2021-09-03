// pages/purchase/index.js
let app = getApp()
Page({
  data: {
  },
  toUrl: function (e) {
    let qx = e.currentTarget.dataset.qx
    let haveQx = qx.haveQx
    console.log(qx,haveQx,996)
    if(haveQx){
      wx.navigateTo({
        url: e.currentTarget.dataset.url,
      })
    }else{
      app.toast('暂无权限')
    }

  },
  onLoad: function (options) {
    let qxList = app.getSomeNodeQx(['销售单列表','销售单新增'])
    //业务功能列表
    this.setData({
      quickList:[
        {
          name:'销售单',
          introduce:'向批发客户销售',
          icon:'cuIcon-edit',
          listpage:'/pages/sale/sale_order_list/index',
          addpage:'/pages/sale/sale_order/index',
          isadd:true,
          qx:qxList[0],
          addqx:qxList[1],
        },
      ]
    })

  },

})