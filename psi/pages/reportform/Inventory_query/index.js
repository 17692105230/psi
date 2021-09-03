let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    search: '',
    TabCur: -1,
    navList: [{
        name: '全部店仓',
        ico: 'cuIcon-triangledownfill'
      },
      {
        name: '全部分类',
        ico: 'cuIcon-triangledownfill'
      },
      {
        name: '排序',
        ico: 'cuIcon-triangledownfill'
      },
      {
        name: '筛选',
        ico: 'cuIcon-triangledownfill'
      }
    ],
    dropShow: false,
    more: false,
    translatey: '',
    //下拉框-店仓
    warehouse: [],//id name checked
    //下拉框-全部分类
    classify: [],//id name checked
    //下拉框-店仓
    sort: [
      // {
      //   name: '货号',
      //   checked: true,
      //   text: '（从高到低）'
      // },
      // {
      //   name: '货号',
      //   checked: false,
      //   text: '（从低到高）'
      // },
      // {
      //   name: '销量',
      //   checked: false,
      //   text: '（从高到低）'
      // },
      // {
      //   name: '销量',
      //   checked: false,
      //   text: '（从低到高）'
      // },
      {
        name: '库存数量',
        checked: false,
        text: '（从高到低）',
        val: 1
      },
      {
        name: '库存数量',
        checked: false,
        text: '（从低到高）',
        val: 2
      },
      // {
      //   name: '商品下单频次',
      //   checked: false,
      //   text: '（从高到低）'
      // },
      // {
      //   name: '商品下单频次',
      //   checked: false,
      //   text: '（从低到高）'
      // },
      {
        name: '创建商品时间',
        checked: false,
        text: '（从高到低）',
        val: 3
      },
      {
        name: '创建商品时间',
        checked: false,
        text: '（从低到高）',
        val: 4
      },
    ],
    //下拉框-筛选
    strDate: '开始时间',
    endDate: '结束时间',
    filtrate: [{
        name: '零库存商品',
        checked: true
      },
      {
        name: '负库存商品',
        checked: true
      },
      {
        name: '可用库存',
        checked: true
      },
      {
        name: '停用商品',
        checked: true
      },
    ],
    all_number: 0, // 库存总量
    all_money: 0.00, // 库存总金额
    list: [],
    insufficient_inventory: 0,
    inventory_backlog: 0,
    scroll_height: '',
    stock_enough: 0, // 库存积压
    stock_unenough: 0, // 库存不足
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    console.log(e,clearkey)
    this.setData({
      [clearkey]:''
    })
  },
  onLoad() {
    this.translatey();
    this.scrollHeight();
    app.util.getAllList(['organization','category']).then(e => {
      this.setData({
        warehouse:e.organization,
        classify:e.category,        
      })
    })
    this.getData()
  },
  //计算上拉框距容器顶部的高度
  translatey() {
    let _this = this;
    const query = wx.createSelectorQuery()
    query.select('#dropdown-top').boundingClientRect()
    query.selectViewport().scrollOffset()
    query.exec(function (res) {
      _this.setData({
        translatey: res[0].top - 3
      })
    })
  },
  // nav点击
  navChange(e) {
    let index = e.currentTarget.dataset.index;
    let navList = this.data.navList;
    let ico = navList[index].ico;
    if (ico == "cuIcon-triangledownfill") {
      navList[index].ico = "cuIcon-triangleupfill";
    } else {
      navList[index].ico = "cuIcon-triangledownfill";
    }
    this.setData({
      TabCur: index,
      dropShow: !this.data.dropShow,
      navList: navList
    })
  },
  // 关闭上拉框
  btnCloseDrop() {
    this.setData({
      dropShow: false
    })
  },
  // 下拉框-店仓-多选框按钮
  warehouseChecked(e) {
    let index = e.currentTarget.dataset.index;
    this.setData({
      ['warehouse[' + index + '].checked']: !this.data.warehouse[index].checked
    })
  },
  // 下拉框-店仓-确定
  warehouseConfirm() {
    console.log('确定')
    this.btnCloseDrop();
    this.getData()
  },
  // 下拉框-店仓-清除
  warehouseReset() {
    for (let i = 0; i < this.data.warehouse.length; i++) {
      this.setData({
        ['warehouse[' + i + '].checked']: false
      })
    }
    this.btnCloseDrop();
    this.getData();
  },
  // 下拉框-全部分类-按钮
  classifyChecked(e) {
    let index = e.currentTarget.dataset.index;
    this.setData({
      ['classify[' + index + '].checked']: !this.data.classify[index].checked
    })
  },
  // 下拉框-全部分类-确定
  classifyConfirm() {
    console.log('确定')
    this.btnCloseDrop();
    this.getData()

  },
  // 下拉框-全部分类-清除
  classifyReset() {
    for (let i = 0; i < this.data.classify.length; i++) {
      this.setData({
        ['classify[' + i + '].checked']: false
      })
    }
    this.btnCloseDrop();
    this.getData();
  },
  // 下拉框-排序-单选按钮
  sortChecked(e) {
    let index = e.currentTarget.dataset.index;
    for (let i = 0; i < this.data.sort.length; i++) {
      this.setData({
        ['sort[' + i + '].checked']: false
      })
    }
    this.setData({
      ['sort[' + index + '].checked']: true
    })
    this.btnCloseDrop();
    this.getData()
  },
  //下拉框-筛选-开始日期选择
  strDateChange(e) {
    this.setData({
      strDate: e.detail.value
    })
  },
  //下拉框-筛选-结束日期选择
  endDateChange(e) {
    this.setData({
      endDate: e.detail.value
    })
  },
  // 下拉框-筛选-按钮
  filtrateChecked(e) {
    let index = e.currentTarget.dataset.index;
    console.log(index)
    this.setData({
      ['filtrate[' + index + '].checked']: !this.data.filtrate[index].checked
    })
  },
  // 下拉框-筛选-重置
  filtrateReset() {
    this.setData({
      ['filtrate[0].checked']: true,
      ['filtrate[1].checked']: true,
      ['filtrate[2].checked']: true,
      ['filtrate[3].checked']: true,
    })
    this.btnCloseDrop();
    this.getData();
  },
  // 下拉框-筛选-确定
  filtrateConfirm() {
    this.btnCloseDrop();
    this.getData()
  },
  //计算scroll-view容器高度
  scrollHeight() {
    let _this = this;
    let window = wx.getSystemInfoSync();
    const query = wx.createSelectorQuery()
    query.select('#scroll-box').boundingClientRect()
    query.selectViewport().scrollOffset()
    query.exec(function (res) {
      _this.setData({
        scroll_height: window.windowHeight - res[0].top
      })
    })
  },
  //搜索框扫码
  scanCode() {
    let _this = this;
    wx.scanCode({
      success(res) {
        console.log(res.result)
        _this.setData({
          search: res.result
        })
      }
    })
    _this.getData()
  },
  // 列表点击跳转
  listTab(e) {
    let goodsId = e.currentTarget.dataset.id;
    let url = 'Inventory_details/index?goods_id=' + goodsId;
    wx.navigateTo({
      url: url,
    })
  },


  tabSelect(e) {
    this.setData({
      TabCur: e.currentTarget.dataset.id,
    })
  },
  //获取并设置数据
  getData() {
    let _this = this;
    // 获取选中仓库
    let warehouse = [];
    
    this.data.warehouse.forEach(function(v,k) {
      if (v.checked) {
        warehouse.push(v.id)
      }
    });
    // 获取选中商品
    let goods = [];
    this.data.classify.forEach(function(v,k) {
      if (v.checked) {
        goods.push(v.id)
      }
    })
    // 排序
    let sort = 0;
    this.data.sort.forEach(function(v,k) {
      if (v.checked) {
        sort = v.val
      }
    })
    // 筛选
    let zero = 0,below = 0,use = 0, stop = 0;
    let filtrate = this.data.filtrate;
    if (!filtrate[0].checked) {
      zero = -1; // 零库存商品 -1 不显示 1 显示
    }
    if (!filtrate[1].checked) {
      below = -1; // 负库存商品 -1 不显示 1 显示
    }
    if (!filtrate[2].checked) {
      use = -1; // 可用库存 -1不显示 1显示
    }
    if (!filtrate[3].checked) {
      stop = -1; // 停用商品 -1不显示 1显示
    }

    app._get("/web/statistics/stockQuery",
      {
        str:this.data.search,
        warehouse: warehouse,
        goods: goods,
        sort: sort,
        zero: zero,
        below: below,
        use: use,
        stop: stop,
      },
      (res) =>{
        if(res.errcode == 0){
          //设置列表显示值
          let data = res.data;  
          let all_number = 0, all_money = 0.00, stock_enouth = 0, stock_unenough = 0;
          data.forEach(function(v,k) {
            all_number += v.stock_number;
            all_money += parseFloat(v.goods_pprice * v.stock_number);
            if (v.goods_ulimit <= v.stock_number) {
              stock_enouth += 1;
            }
            if (v.goods_llimit >= v.stock_number) {
              stock_unenough += 1;
            }
          })
          _this.setData({
            list:data,
            all_number: all_number,
            all_money: all_money,
            stock_enough: stock_enouth,
            stock_unenough: stock_unenough,
            more: data.length > 0 ? true : false,
            search :''
          })
      
        }
      })
  }
})