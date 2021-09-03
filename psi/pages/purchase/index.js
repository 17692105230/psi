// pages/purchase/index.js
let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {

    //明细列表
    detailList:[
      //del
      // {
      //   name:'供应商对账',
      //   introduce:'查询与供应商之间的账目',
      //   icon:'cuIcon-edit',
      //   listpage:'/pages/purchase/supplier_account/index',
      //   addpage:'',
      //   isadd:false
      // }
    ],
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let qxList = app.getSomeNodeQx(['采购单列表','采购单新增'])
this.setData({
    //业务功能列表
    quickList:[
      {
        name:'采购单',
        introduce:'从供应商处进货',
        icon:'cuIcon-edit',
        listpage:'/pages/example4/list/list',
        addpage:'/pages/example4/index',
        isadd:true,
        qx:qxList[0],
        addqx:qxList[1]
      },
    ],
})
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
})