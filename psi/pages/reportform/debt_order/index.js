// pages/reportform/debt_order/index.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    money:'-26895',
    navShow:true,
    lists:[],
    footName:'资产合计',
    workingFund:[
      {
        pid:1,
      name:'流动资产',
      money:'-296',
      list:[
        {id:1,name:'现金',money:'2256'},
        {id:2,name:'支付宝',money:'-2256'},
        {id:3,name:'微信支付',money:'0'},
        {id:4,name:'邮政银行',money:'0'},
        {id:5,name:'建设银行',money:'0'},
      ]
    },
    {
      pid:2,
      name:'非流动资产',
      money:'96',
      list:[
        {id:6,name:'库存商品',money:'96'},
        {id:7,name:'应用收款',money:'0'},
        {id:8,name:'预付款',money:'0'},
      ]
    }
   ],
   illiquidFunds:[
        {
          pid:3,
        name:'负债',
        money:'0',
        list:[
          {id:9,name:'应付账款',money:'0'},
          {id:10,name:'预收款',money:'0'},
          {id:11,name:'客户储值',money:'0'},
        ]
      },
      {
        pid:4,
        name:'所有者权益',
        money:'-20',
        list:[
          {name:'初期资本',money:'96'},
          {name:'利润',money:'0'},
        ]
      }
    ]
  },
  // 生成当前日期
  nowDate(){
    var nowDate = new Date();
      var year = nowDate.getFullYear(), month = nowDate.getMonth() + 1, day = nowDate.getDate();
      this.setData({
          "date": `${year}-${month}-${day}`
      })
  },
  // 日期选择
  bindDateChange(e){
    this.setData({
      date: e.detail.value
    })
  },
  // nav更换
  navChange(e){
    let navShow='';
    if(e.currentTarget.dataset.id==0){
      navShow=true;
      this.setData({
        lists:this.data.workingFund,
        footName:'资产合计'
      })
    }else{
      navShow=false;
      this.setData({
        lists:this.data.illiquidFunds,
        footName:'负债及所有者权益合计'
      })
    }
    this.setData({
      navShow:navShow
    })
  },
  // 跳转查看页面
  urlTo(e){
    let index=e.currentTarget.dataset.index;
    let ind=e.currentTarget.dataset.ind;
    let name=this.data.lists[index].name;
    let names=this.data.lists[index].list[ind].name;
    if(this.data.navShow==false){
      if(index==1){
        return false;
      }
    }
    wx.navigateTo({
      url: '/pages/reportform/debt_order/debt_order_ details/index?name='+name+'&&names='+names
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.nowDate();
    this.setData({
      lists:this.data.workingFund
    })
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