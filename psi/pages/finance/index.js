// pages/purchase/index.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    //业务功能列表
    quickList:[
      {
        name:'收款单',
        introduce:'向客户预收货款,收款,结算欠款等',
        icon:'cuIcon-edit',
        listpage:`/pages/common/common_list/index?type=skd`,
        addpage:'/pages/finance/receipt_order/index',
        isadd:true
      },
      {
        name:'付款单',
        introduce:'向供应商预付货款,付款,结算欠款等',
        icon:'cuIcon-edit',
        listpage:`/pages/common/common_list/index?type=fkd`,
        addpage:'/pages/finance/pay_order/index',
        isadd:true
      },
      {
        name:'费用单',
        introduce:'录入除商品成本外的支出',
        icon:'cuIcon-edit',
        listpage:`/pages/common/common_list/index?type=fyd`,
        addpage:'/pages/finance/cost_order/index',
        isadd:true
      },
      {
        name:'收入单',
        introduce:'录入非主营业务收入所得',
        icon:'cuIcon-edit',
        listpage:`/pages/common/common_list/index?type=srd`,
        addpage:'/pages/finance/income_order/index',
        isadd:true
      }
    ],
    //明细
    detailList:[
      {
        name:'单据综合统计',
        introduce:'查询一段时间内所产生的所有单据及其详情',
        icon:'cuIcon-edit',
        listpage:'/pages/orders_statistic/index',
        addpage:'',
        isadd:false
      },
      // {
      //   name:'经营历程',
      //   introduce:'记录系统所产生的所有过账审核的单据',
      //   icon:'cuIcon-edit',
      //   listpage:'',
      //   addpage:'',
      //   isadd:false
      // }
    ],
  },
  toUrl(e){
    wx.navigateTo({
      url: e.currentTarget.dataset.url
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})