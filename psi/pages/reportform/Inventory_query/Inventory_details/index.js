// pages/reportform/Inventory_query/Inventory_details/index.js
let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    warehouse:[],
    warehouse_index:0,
    goods_name:'',
    goods_code:'',
    goods_unit:'',
    navList:['全部','本周','本月','自定义'],
    navListIndex:0,
    strDate:'开始日期',
    endDate:'结束日期',
    goods_id: 0,
    assist_url: '',
    inventory:[
      // {name:'期初库存',num:'5'},
      {name:'总入库数',num:'0'},
      {name:'总出库数',num:'0'},
      {name:'期末库存',num:'0'},
    ],
    list:[],
    scroll_height:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.scrollHeight();
    app.util.getAllList(['organization','category']).then(e => {
      this.setData({
        warehouse:e.organization,   
        goods_id: options.goods_id
      }),
      this.getData()
    })
  },
  // 仓库选择
  warehouseChange(e){
    this.setData({
      warehouse_index:e.detail.value
    })
    this.getData()
  },
  // 导航栏
  navListTab(e){
    let index=e.currentTarget.dataset.id;
    this.setData({
      navListIndex:index,
      strDate: '开始日期',
      endDate: '结束日期'
    })
    this.scrollHeight();
    this.getData();
  },

   //获取并设置数据
   getData() {
    let _this = this;
    let scope = "";
    if (_this.data.navListIndex == 1) {
      scope = 'week'
    } else if (_this.data.navListIndex == 2) {
      scope = 'month'
    }
    app._get("/web/statistics/goodsStockDetails",
      {
        goods_id: this.data.goods_id,
        scope: scope,
        start: this.data.strDate != '开始日期' ? this.data.strDate : '',
        end: this.data.endDate != '结束日期' ? this.data.endDate : '',
        warehouse_id: this.data.warehouse[this.data.warehouse_index].id
      },
      (res) =>{
        if(res.errcode == 0){
          //设置列表显示值
          let data = res.data;
          let inventory = [
            {name:'总入库数',num:data.total_enter},
            {name:'总出库数',num:data.total_out},
            {name:'期末库存',num:data.nowStock},
          ];
          _this.setData({
            list:data.list,
            goods_name: data.goods.goods_name,
            goods_code: data.goods.goods_code,
            goods_unit: data.dict ? data.dict.dict_name : '件',
            assist_url: data.assist.assist_url,
            inventory: inventory
          })
      
        }
      })
  },
  

   // 自定义开始日期选择
   strDateChange(e){
    this.setData({
      strDate:e.detail.value
    })
  },
  // 自定义结束日期选择
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