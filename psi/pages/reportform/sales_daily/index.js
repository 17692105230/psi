import * as echarts from '../../../components/ec-canvas/echarts';
const app = getApp();
let chartDate = [];
let chartMoney = [];
let chartProfit = [];
Page({

  /**
   * 页面的初始数据
   */
  data: {
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    tabDatesIndex:0,
    custom: 0,
    totalGoodsNumber: 0,
    totalOrdersPmoney: 0.00,
    totalProfit: 0.00,
    tabDates:[
      "日","周","月","季","年"
    ],
    dataList:[],
    ecComponent: null,
    scrollTop:0,//zll 进货走势 全部时间 下拉框 滚动距离
    date_list:["全部时间","昨日","今日","本周","本月","本季","本年","自定义"],
    date_index:0,// 时间筛选 下表
    start_date:'2020-12-29',// 自定义日期 开始日期
    end_date:'2020-12-29',// 自定义日期 结束日期
    dropShow: false,
    more: false
  },

    //初始化进货走势 图标
    init: function () {
      this.ecComponent.init((canvas, width, height, dpr) => {
        // 获取组件的 canvas、width、height 后的回调函数
        // 在这里初始化图表
        const chart = echarts.init(canvas, null, {
          width: width,
          height: height,
          devicePixelRatio: dpr // new
        });
        setOption(chart);
        // 将图表实例绑定到 this 上，可以在其他成员函数（如 dispose）中访问
        this.chart = chart;
        // 注意这里一定要返回 chart 实例，否则会影响事件处理等
        return chart;
      });
    },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    _this.countSize();
    _this.getData();
  },

  getData: function () {
    let _this = this;
    this.ecComponent = this.selectComponent('#mychart-dom-line');
    let start = _this.data.start_date;
    let end = _this.data.end_date;
    let custom = _this.data.custom;
    let range = _this.data.tabDatesIndex + 1;
    let relation = ['all', 'yesterday', 'today', 'week', 'month', 'season', 'year'];
    let scope = relation[_this.data.date_index];  // 非自定义参数
    app._get("web/statistics/salesDaily",
      {
        start:start,
        end:end,
        custom:custom,
        scope:scope,
        range: range,
      },
      (res) =>{
        if(res.errcode == 0){
          //设置列表显示值
          _this.setData({
            dataList:res.data
          })
          let data = res.data;
          //设置折线图
          //获取所有时间
          var totalGoodsNumber = 0;
          var totalOrdersPmoney = 0.00;
          var totalProfit = 0.00;
          chartDate = [];
          chartMoney = [];
          chartProfit = [];
          data.forEach(element => {
            chartDate.push(element.date)
            chartMoney.push(element.orders_pmoney);
            chartProfit.push(element.profit);
            totalGoodsNumber += parseInt(element.goods_number);
            totalOrdersPmoney += parseFloat(element.orders_pmoney);
            totalProfit += parseFloat(element.profit);
          });
          _this.setData({
            totalGoodsNumber:totalGoodsNumber,
            totalOrdersPmoney:totalOrdersPmoney,
            totalProfit: totalProfit,
            more: data.length > 0 ? true : false
          })  
          _this.init();
        }
      })
  },

  //zll 点击时间tab
  tabDtaeSelect:function(e){
    this.setData({
      tabDatesIndex: e.currentTarget.dataset.id
    })
    this.getData();
  },
  //重新处理页面尺寸计算
  countSize:function() {
    let _this = this;
    builderPageHeight(this, function(data) {
      console.log(data);
      // 算出来之后存到data对象里面
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.headerHeight - data.tabBarHeight - data.topBarHeight,
        sizeData: data
      });
      _this.setData({
        isRefresher: true
      });
    });
  },
  //zll 进货走势 全部时间 下拉框 打开
  onShowMore: function (e) {
    let _this = this;
    let dropShow = _this.data.dropShow;
    dropShow = !dropShow;
    _this.setData({
      dropShow: dropShow
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  onReady:function() {
    this.ecComponent = this.selectComponent('#mychart-dom-line');
  },
  //zll 进货走势 全部时间 下拉框 关闭
  btnCloseDrop() {
    let _this = this;
    let custom = 0;
    //如果是自定义日期,判断时间有效性
    if(_this.data.date_index==7){
      let starttime = new Date(_this.data.start_date).getTime();
      let endtime = new Date(_this.data.end_date).getTime();
      custom = 1;
      if(starttime>endtime){
        wx.showToast({
          title: '开始时间不能大于结束时间',
          icon: 'none'
        });
        return false;
      }
    }
    let dropShow = _this.data.dropShow;
    dropShow = !dropShow;
    _this.setData({
      scrollTop: 0,
      dropShow: dropShow,
      custom: custom,
    });
    //请求接口查询数据...
    _this.getData();
  },
  //zll 点击 时间按钮
  change_purchase_date:function(e){
    let _this = this;
    let index = e.currentTarget.dataset.index;
    _this.setData({
      date_index:index
    });
    if(index<=6){
      _this.btnCloseDrop();
    }
  },
  //zll 进货走势 修改开始日期
  bindDateStart:function(e){
    let _this = this;
    let start_date = _this.data.start_date;
    start_date = e.detail.value;
    _this.setData({
      start_date:start_date
    });
  },
  //zll 进货走势 修改结束日期
  bindDateEnd:function(e){
    let _this = this;
    let end_date = _this.data.end_date;
    end_date = e.detail.value;
    _this.setData({
      end_date:end_date
    });
  },
})

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('sales_daily_height');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    // 页面总高度将会放在这里
    windowHeight: 0,
    // header的高度
    headerHeight: 0,
    // 工具栏的高度
    toolBarHeight: 0,
    // 底部工具栏的高度
    tabBarHeight: 0
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function(res) {
      data.windowHeight = res.windowHeight;
    }
  });
  // 先创建一个 SelectorQuery 对象实例
  let query = wx.createSelectorQuery().in(_this);
  // 然后逐个取出header、toolbar、tabBar的节点信息
  // 选择器的语法与jQuery语法相同
  query.select('#header').boundingClientRect();
  query.select('#tabBar').boundingClientRect();
  // query.select('#toptab').boundingClientRect();//zll 工具栏信息
  query.select('#navbar').boundingClientRect();//zll 获取分享按钮栏高度
  // 执行上面所指定的请求，结果会按照顺序存放于一个数组中，在callback的第一个参数中返回
  query.exec((res) => {
    console.log(res);
    data.headerHeight = res[0].height;
    data.tabBarHeight = res[1].height;
    data.topTabBarHeight = res[2].height; //zll 获取工具栏高度
    data.topBarHeight = res[2].height; //zll 获取分享按钮栏高度

    wx.setStorageSync('sales_daily_height', data);
    _fun && _fun(data);
  });
};
  // 图标 配置
  function setOption(chart) {
    var option = {
      title: {
        text: '',
        left: 'center'
      },
      color: ["#FB8B05", "#1661AB"],
      legend: {
        data: ['毛利', '实际销售额'],
        top: 5,
        left: 'center',
        backgroundColor: '',
        z: 100,
        icon:'roundRect'
      },
      grid: {
        containLabel: false,
        top:'20%',
        bottom:'15%'
      },
      tooltip: {
        show: true,
        trigger: 'axis'
      },
      xAxis: {
        type: 'category',
        boundaryGap: false,
        data:chartDate,
        axisLine:{
          lineStyle:{
            color:'#999'
          }
        }
      },
      yAxis: {
        x: 'center',
        type: 'value',
        axisLabel: {
          formatter: function(v) {
            return tranNumber(v);
          }
        },
      },
      series: [{
        name: '毛利',
        type: 'line',
        smooth: true,
        data: chartProfit
      }, {
        name: '实际销售额',
        type: 'line',
        smooth: true,
        data: chartMoney
      }]
    };
    chart.setOption(option);
  };

  function tranNumber(num){
    var numStr = num.toString();
    // 十万以内直接返回
    if (numStr.length <5 ) {
        return numStr;
    }
    //大于8位数是亿
    else if (numStr.length > 8) {
        var decimal = numStr.substring(numStr.length - 8, numStr.length - 8 );
        return parseFloat(parseInt(num / 100000000) + '.' + decimal) + '亿';
    }
    //大于6位数是十万 (以10W分割 10W以下全部显示)
    else if (numStr.length > 5) {
        var decimal = numStr.substring(numStr.length - 4, numStr.length - 4)
        return parseFloat(parseInt(num / 10000) + '.' + decimal) + '万';
    }else if(numStr.length == 5){
        var decimal = numStr.substring(numStr.length - 3, numStr.length - 4)
        return parseFloat(parseInt(num / 10000) + '.' + decimal) + '万';
    }
  };