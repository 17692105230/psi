import * as echarts from '../../../components/ec-canvas/echarts';
const app = getApp();
let varline_time = [];
let varline_data_num = [];
let varline_data_pmoney = [];
let goodsChartData = [];
Page({
  /**
   * 页面的初始数据
   */
  data: {
    StatusBar: app.globalData.StatusBar,
    CustomBar: app.globalData.CustomBar,
    Custom: app.globalData.Custom,
    tabsIndex:0,
    sum_pmoney:0, //进货走势，合计进货量
    sum_num:0, //进货走势，合计进货额
    sum_dept_money: 0, // 应付欠款总额
    ecComponent: null,
    more: false,
    tabs:[
      "进货走势","供应商","商品"
    ],
    tabDatesIndex:0,
    tabDates:[
      "日","周","月","季","年"
    ],
    //进货走势 列表
    dataList:[],
    dropShow:[false,false,false],//zll 下拉框 是否显示 分别: 进货走势 供应商 商品
    scrollTop:0,//zll 进货走势 全部时间 下拉框 滚动距离
    date_index:[0,0,0],// 时间筛选 下表,分别: 进货走势 供应商 商品
    // 进货走势 时间筛选 列表
    date_list:["全部时间","昨日","今日","本周","本月","本季","本年","自定义"],
    start_date:'2020-12-29',// 自定义日期 开始日期,分别: 进货走势 供应商 商品
    end_date:'2020-12-29',// 自定义日期 结束日期,分别: 进货走势 供应商 商品
    warehouseList:[],//仓库列表
    warehouseShow:[],//仓库 是否显示
    warehouseData:[],//选择的仓库列表
    //商品 筛选类型 显示
    goodsTypeShow:false,
    //商品 筛选类型 下标
    goodsTypeIndex:0,
    //商品 筛选类型
    goodsType:["分类","商品"]
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
      if (this.data.tabsIndex == 0) {
        setOption1(chart);
      } else {
        setOption2(chart);
      }
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
    _this.getPurchaseAnalysis();
  },

  onReady:function() {
    this.ecComponent = this.selectComponent('#mychart-dom-line');
  },

  //zll 点击tab
  tabSelect:function(e){
    let _this = this;
    let index = e.currentTarget.dataset.id;
    _this.setData({
      tabsIndex: index,
      //关闭所有下拉框
      warehouseShow:[false,false,false],
      goodsTypeShow:false,
      dropShow:[false,false,false],
      dataList: {},
    })
    _this.countSize();
    if (index == 0) {
      // 进货走势
      this.ecComponent = this.selectComponent('#mychart-dom-line');
      _this.getPurchaseAnalysis();
    } else if (index == 1) {
      _this.getHouseList();
    } else {
      this.ecComponent = this.selectComponent('#mychart-dom-line');
      _this.getHouseList();
    }
  },

  //获取仓库列表
  getHouseList:function(){
    let _this = this;
    app._get("web/statistics/getHouseList",{},
      (res) =>{
        if(res.errcode == 0){
          let data = res.data;
          let wareHouseList = [];
          data.forEach(element => {
            let item = {
              'id' : element.org_id,
              'name' : element.org_name,
              'active' : false,
            };
            wareHouseList.push(item)
          });
          _this.setData({
            warehouseList:wareHouseList,
          })
          if (_this.data.tabsIndex == 1) {
            // 供应商
            _this.getSupplierData();
          } else {
            // 商品
            _this.getGoodsData();
          }
         
        }
      })
  },



  //zll 点击时间tab
  tabDtaeSelect:function(e){
    this.setData({
      tabDatesIndex: e.currentTarget.dataset.id
    })
    this.getPurchaseAnalysis();
  },
  //重新处理页面尺寸计算
  countSize:function() {
    let _this = this;
    builderPageHeight(this, function(data) {
      // 算出来之后存到data对象里面
      let windowHeight = data.windowHeight,
      headerHeight = data.headerHeight,
      tabBarHeight = data.tabBarHeight,
      topTabBarHeight = data.topTabBarHeight,
      topBarHeight = data.topBarHeight;
      if (_this.data.tabsIndex == 1) {
        headerHeight = 0;
      } else if (_this.data.tabsIndex == 2) {
        headerHeight = 214;
      }
      _this.setData({
        saleScrollViewHeight: windowHeight - headerHeight - tabBarHeight - topTabBarHeight - topBarHeight,
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
    dropShow[_this.data.tabsIndex] = !dropShow[_this.data.tabsIndex];
    _this.setData({
      dropShow: dropShow
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //zll 进货走势 全部时间 下拉框 关闭
  btnCloseDrop() {
    let _this = this;
    //如果是自定义日期,判断时间有效性
    if(_this.data.date_index[_this.data.tabsIndex]==7){
      let starttime = new Date(_this.data.start_date).getTime();
      let endtime = new Date(_this.data.end_date).getTime();
      if(starttime>endtime){
        wx.showToast({
          title: '开始时间不能大于结束时间',
          icon: 'none'
        });
        return false;
      }
      let startYear, startMonth, startDay, endYear, endMonth, endDay;
      if (!_this.data.start_date) {
        let dateObj = new Date();
         startYear = dateObj.getFullYear();
         startMonth = dateObj.getMonth();
         startDay = dateObj.getDay();
      } else {
        let dateObj = new Date(_this.data.start_date);
         startYear = dateObj.getFullYear();
         startMonth = dateObj.getMonth();
         startDay = dateObj.getDay();
      }
      if (!_this.data.end_date) {
        let enddateObj = new Date();
         endYear = enddateObj.getFullYear();
         endMonth = enddateObj.getMonth();
         endDay = enddateObj.getDay();
      } else {
         let enddateObj = new Date(_this.data.end_date);
         endYear = enddateObj.getFullYear();
         endMonth = enddateObj.getMonth();
         endDay = enddateObj.getDay();
      }
      _this.setData({
        start_date:startYear + "-" + startMonth + "-" + startDay,
        end_date:endYear + "-" + endMonth + "-" + endDay,
      })
    }
    let dropShow = _this.data.dropShow;
    dropShow[_this.data.tabsIndex] = !dropShow[_this.data.tabsIndex];
    _this.setData({
      scrollTop: 0,
      dropShow: dropShow
    });
    //请求接口查询数据..
    if (_this.data.tabsIndex == 0) {
        // 进货走势
        this.ecComponent = this.selectComponent('#mychart-dom-line');
        _this.getPurchaseAnalysis();
    } else if (_this.data.tabsIndex == 1) {
        _this.getSupplierData();
    } else {
      this.ecComponent = this.selectComponent('#mychart-dom-line');
      _this.getGoodsData();
    }
  },

  // 商品数据
  getGoodsData: function(e) {
    let _this = this;
    //进货走势全部时间
    let date_selection = _this.data.date_index[_this.data.tabsIndex];
    let custom = date_selection == 7 ?  1 : 0;  // 是否自定义日期
    let relation = ['all', 'yesterday', 'today', 'week', 'month', 'season', 'year'];
    let scope = relation[date_selection];  // 非自定义参数
    let orgIds = [];
    let allOrgIds = [];
    _this.data.warehouseList.forEach(element => {
      if (element.active) {
        orgIds.push(element.id);
      }
      allOrgIds.push(element.id);
    })
    app._get("web/statistics/goods",
      {
        org_id:orgIds.length > 0 ? JSON.stringify(orgIds) : JSON.stringify(allOrgIds),
        start:_this.data.start_date,
        end:_this.data.end_date,
        custom: custom,
        scope: scope,
        category: _this.data.goodsTypeIndex,
      },
      (res) =>{
        if(res.errcode == 0){
          // 统计
          let sum_num = 0;
          let sum_pmoney = 0;
          let sum_dept_money = 0;
          // console.log(res.data.house);
          for(let k in res.data.house) {
            let element = res.data.house[k];
            sum_num += element.goods_number;
            sum_pmoney += element.orders_rmoney;
          }
          goodsChartData = res.data.chart;
          sum_dept_money = sum_pmoney / sum_num;
          //设置列表显示值
          _this.setData({
            dataList:res.data.house,
            sum_num: sum_num,
            sum_pmoney: sum_pmoney,
            sum_dept_money: sum_dept_money,  
            more: sum_num > 0 ? true : false
          })
          _this.init();
        }
      })
  },

  // 供应商数据
  getSupplierData: function(e) {
    let _this = this;
    //进货走势全部时间
    let date_selection = _this.data.date_index[_this.data.tabsIndex];
    let custom = date_selection == 7 ?  1 : 0;  // 是否自定义日期
    let relation = ['all', 'yesterday', 'today', 'week', 'month', 'season', 'year'];
    let scope = relation[date_selection];  // 非自定义参数
    let orgIds = [];
    let allOrgIds = [];
    _this.data.warehouseList.forEach(element => {
      if (element.active) {
        orgIds.push(element.id);
      }
      allOrgIds.push(element.id);
    })
    app._get("web/statistics/supplier",
      {
        org_id:orgIds.length > 0 ? JSON.stringify(orgIds) : JSON.stringify(allOrgIds),
        start:_this.data.start_date,
        end:_this.data.end_date,
        custom: custom,
        scope: scope,
      },
      (res) =>{
        if(res.errcode == 0){
          // 统计
          let sum_num = 0;
          let sum_pmoney = 0;
          let sum_dept_money = 0;
          for(let k in res.data) {
            let element = res.data[k];
            sum_num += element.goods_number;
            sum_pmoney += element.orders_rmoney;
            sum_dept_money += element.debt_money;
          }
          //设置列表显示值
          _this.setData({
            dataList:res.data,
            sum_num: sum_num,
            sum_pmoney: sum_pmoney,
            sum_dept_money: sum_dept_money,
            more: sum_num > 0 ? true : false
          })
        }
      })
  },

  //zll 点击 时间按钮
  change_purchase_date:function(e){
    let _this = this;
    let index = e.currentTarget.dataset.index;
    let dateindex = e.currentTarget.dataset.dateindex;
    let date_index = _this.data.date_index;
    date_index[dateindex] = e.currentTarget.dataset.index;
    _this.setData({
      date_index:date_index
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
  //zll 仓库 下拉框 打开
  onShowWarehouse: function (e) {
    let _this = this;
    let warehouseShow = _this.data.warehouseShow;
    warehouseShow[_this.data.tabsIndex] = !warehouseShow[_this.data.tabsIndex];
    _this.setData({
      warehouseShow: warehouseShow
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //zll 仓库 下拉框 关闭
  onCloseWarehouse:function(e){
    let _this = this;

    let warehouseShow = _this.data.warehouseShow;
    warehouseShow[_this.data.tabsIndex] = !warehouseShow[_this.data.tabsIndex];
    _this.setData({
      scrollTop: 0,
      warehouseShow: warehouseShow
    });
    if (_this.data.tabsIndex == 1) {
      _this.getSupplierData();
    } else {
      this.ecComponent = this.selectComponent('#mychart-dom-line');
      _this.getGoodsData();
    }
   
  },
  //zll 选择 仓库
  change_warehouse:function(e){
    let _this = this;
    let warehouseId = e.currentTarget.dataset.id;
    let warehouseList = [];
    _this.data.warehouseList.forEach(function(v,k){
      let item =  {
        'id' : v.id,
        'name' : v.name,
        'active' : v.active,
      };
      if (v.id == warehouseId) {
        item.active = !v.active;
      }
      warehouseList.push(item);
    });
    _this.setData({
      warehouseList:warehouseList
    });
  },
  //zll 显示 选择 商品 筛选类型
  onShowGoodsType:function(){
    this.setData({
      goodsTypeShow:true
    });
  },
  //zll 点击 商品 筛选类型
  change_goodstype:function(e){
    this.setData({
      goodsTypeIndex:e.currentTarget.dataset.index,
    });
    this.close_goodstype();
    this.ecComponent = this.selectComponent('#mychart-dom-line');
    this.getGoodsData();
  },
  //zll 关闭 商品 筛选类型
  close_goodstype:function(){
    this.setData({
      goodsTypeShow:false
    });
  },
  //mll 进货走势接口
  getPurchaseAnalysis:function(){
    let _this = this;
    //进货走势全部时间
    let date_selection = _this.data.date_index[_this.data.tabsIndex];
    //日周月季年选择
    let time_selection = _this.data.tabDatesIndex;

    app._get("web/purchaseOrders/purchaseAnalysis",
      {type:date_selection,time:time_selection,start_time:_this.data.start_date,end_time:_this.data.end_date},
      (res) =>{
        if(res.errcode == 0){
          //设置列表显示值
          _this.setData({
            dataList:res.data
          })
          let data = res.data;

          //设置折线图
          //获取所有时间
          let line_time = [];
          let line_data_num = [];
          let line_data_pmoney = [];
          var sum_num = 0;
          var sum_pmoney = 0.00;
          data.forEach(element => {
            line_time.push(element.date)
            line_data_num.push(element.goods_number);
            line_data_pmoney.push(element.orders_pmoney);
            sum_num += parseInt(element.goods_number);
            sum_pmoney += parseFloat(element.orders_pmoney);
          });
          _this.setData({
            sum_num:sum_num,
            sum_pmoney:sum_pmoney,
            more: data.length > 0 ? true : false
          })
          varline_data_num = line_data_num;
          varline_time = line_time;
          varline_data_pmoney = line_data_pmoney;
          this.init();
        }
      })
  }
})

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('purchase_analysis_height');
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
  query.select('#toptab').boundingClientRect();//zll 工具栏信息
  query.select('#navbar').boundingClientRect();//zll 获取分享按钮栏高度
  // 执行上面所指定的请求，结果会按照顺序存放于一个数组中，在callback的第一个参数中返回
  query.exec((res) => {
    data.headerHeight = res[0].height;
    data.tabBarHeight = res[1].height;
    data.topTabBarHeight = res[2].height; //zll 获取工具栏高度
    data.topBarHeight = res[3].height; //zll 获取分享按钮栏高度

    wx.setStorageSync('purchase_analysis_height', data);
    _fun && _fun(data);
  });
};
// 商品
function setOption2(chart) {
  let legend = [];
  let goodsList = [];
  for (let k in goodsChartData) {
    let v = goodsChartData[k];
    legend.push(v.name);
    goodsList.push(v)
  }
  let option = {
    tooltip: {
      trigger: 'item',
      formatter: '{b}: {c} ({d}%)'
    },
    legend: {
      orient: 'vertical',
      right: 10,
      type:'scroll',
      data: legend
    },
    series: [{
      name: '',
      type: 'pie',
      radius: ['50%', '70%'],
      avoidLabelOverlap: false,
      center:['35%','50%'],
      data: goodsList,
  }]
  };
  chart.setOption(option);
}
  // 进货走势 图标 配置
function setOption1(chart) {
  const option = {
    title: {
      text: '',
      left: 'center'
    },
    color: ["#FB8B05", "#1661AB"],
    legend: {
      data: ['实际进货金额', '实际进货量'],
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
      data: varline_time,
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
      axisLine:{
        lineStyle:{
          color:'#999'
        }
      }
    },
    series: [{
      name: '实际进货金额',
      type: 'line',
      smooth: true,
      data: varline_data_pmoney
    }, {
      name: '实际进货量',
      type: 'line',
      smooth: true,
      data: varline_data_num
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