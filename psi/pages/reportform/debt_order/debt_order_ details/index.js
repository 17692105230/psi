// pages/reportform/debt_order/debt_order_ details/index.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    initialMoney:0,
    currentPeriodMoney:'-36',
    allMoney:'-36',
    list:[
      {
        date:'2020-12-28',
        formList:[
          {name:'付款单',code:'JUIT3659652652',beforeMoney:0,afterMoney:'-31',money:'-31'},
          {name:'付款单',code:'JUIT3659652652',beforeMoney:0,afterMoney:'-31',money:'-31'},
        ]
      }
    ],
    scroll_height:''
  },
// 生成当前日期
  nowDate(){
    var nowDate = new Date();
      var year = nowDate.getFullYear(), month = nowDate.getMonth() + 1, day = nowDate.getDate();
      this.setData({
          "strDate": `${year}-01-01`,
          "endDate": `${year}-${month}-${day}`
      })
  },
  // 开始日期选择
  strDateChange(e){
    this.setData({
      strDate:e.detail.value
    })
  },
  //结束日期选择
  endDateChange(e){
    this.setData({
      endDate:e.detail.value
    })
  },
  //计算scroll-view容器高度
  scrollHeight(){
    let _this=this;
    let window = wx.getSystemInfoSync();
    const query = wx.createSelectorQuery()
    query.select('#scroll-box').boundingClientRect()
    query.selectViewport().scrollOffset()
    query.exec(function(res){
      _this.setData({
        scroll_height:window.windowHeight-res[0].top 
      })
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      name:options.name,
      names:options.names
    })
    this.nowDate();
    this.scrollHeight();
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