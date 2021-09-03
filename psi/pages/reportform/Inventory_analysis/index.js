const app = getApp();
Page({
  data: {
    tabList: ['店仓分析', '商品分析'],
    TabCur: 0,
    list: [],
    navList: [],
    storeNavList: [{
        name: '店仓名称',
        type: 0
      },
      {
        name: '库存数量',
        type: 0
      },
      {
        name: '库存金额(元)',
        type: 0
      },
    ],
    goodsNavList: [{
        name: '分类名称',
        type: 0
      },
      {
        name: '库存数量',
        type: 0
      },
      {
        name: '库存金额(元)',
        type: 0
      },
    ],
    scroll_height: '',
    all_num: 0,
    all_money: 0.00
  },
  // 导航栏切换
  tabChange(e) {
    let index = e.currentTarget.dataset.id;
    this.setData({
      TabCur: index
    })
    if (index == 0) {
      this.setData({
        navList: this.data.storeNavList,
      })
    } else {
      this.setData({
        navList: this.data.goodsNavList,
      })
    }
    this.getData();
  },
  //列表首行点击排序方式
  navTab(e) {
    let index = e.currentTarget.dataset.id;
    let type;
    if (this.data.navList[index].type == 0) {
      type = 1;
    } else if (this.data.navList[index].type == 1) {
      type = 2;
    } else if (this.data.navList[index].type == 2) {
      type = 1;
    }
    this.setData({
      ['navList[' + index + '].type']: type
    })
    let rankArr = this.data.list //排序的数组
    // let rankArrName = tabCur ? 'goods_list' : 'store_list' //排序的数组的键名 
    let rankArgument = ['name', 'num', 'money'][index] //排序的字段
    let rankType = this.data.navList[index].type == 1 ? 1 : 0 //1升序 0降序
    rankArr.sort(function (a, b) {
      if(rankArgument=='name'){
        return a[rankArgument].localeCompare(b[rankArgument])
      }
      return a[rankArgument]-b[rankArgument]
    })
    rankType?rankArr:rankArr.reverse()
    this.setData({
      list: rankArr
    })
  },
  getData: function () {
    let _this = this;
    app._get("web/statistics/stockAnalysis",
      {
        category:_this.data.TabCur
      },
      (res) =>{
        if(res.errcode == 0){
          //设置列表显示值
          _this.setData({
            list:res.data
          })
          let data = res.data;
          let all_num = 0;
          let all_money = 0.00;
          data.forEach(element => {
            all_num += parseInt(element.num);
            all_money += parseFloat(element.money);
          });
          _this.setData({
            all_money: all_money,
            all_num: all_num
          })
        }
      })
  },
  //计算scroll-view容器高度
  scrollHeight() {
    let _this = this;
    let window = wx.getSystemInfoSync();
    const query = wx.createSelectorQuery()
    query.select('#scroll-box').boundingClientRect()
    query.selectViewport().scrollOffset()
    query.exec(function (res) {
      console.log(res)
      _this.setData({
        scroll_height: window.windowHeight - res[0].top - res[0].left - 45
      })
    })
  },
  onLoad: function (options) {
    this.setData({
      navList: this.data.storeNavList,
      // list: this.data.store_list
    })
    this.scrollHeight();
    this.getData();
  },

})