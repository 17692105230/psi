const app = getApp();
const util = require("../../utils/util.js");
Page({

  /**
   * 页面的初始数据
   */
  data: {
    chche_name: 'PurchaseOrderCache', //缓存单名称
    orders_status: '',
    list: [], //商品列表
    sumprice: 0, //总价
    sumcount: 0, //总量
    mSelectIndex: 0,
    mSelectType: true,
    words: {
      submit: '完成',
      clear: 'Clear',
      style_code: '号型',
      size_name: '尺码',
      number_name: '存量'
    },

    // scroll-view的高度
    mScrollViewHeight: 0,
    showHeader: '',
    // 测试商品数量
    inputNumber: 0,
    // 查询商品窗口
    glModal: false,
    // 搜索数据
    searchData: {
      show: false,
      text: ''
    },
    // 下拉刷新
    triggered: false,
    isRefresher: false,
    // 设置折扣对话框
    showDiscountSetting: false,
    dropShow: false, //zll 显示"更多信息"信息框
    scrollTop: 0, //zll 滚动距离
    editGoodsIndex: 0, //zll 编辑商品列表的下标
    isGoodsSearchShow: false, //zll 搜索商品列表显示
    goodsScrollTop: 0, //zll scrollview滚到哪个商品处
    editOrScan: 0, //zll 标记是扫码还是编辑商品(编辑商品是直接覆盖商品;扫码的商品如果重复,数量将累加);0扫码,1编辑
    supplierList: [], //供应商列表
    supplierList_index: -1, //供应商下标
    supplierList_value: 0, //供应商id
    supplier_money: 0.00, //供应商账户金额
    organizationList: [], //仓库列表
    organizationList_index: -1, //仓库下标
    organizationList_value: 0, //仓库id
    searchGoodsName: "", //搜索商品条件  商品名称
    searchGoodsList: [], //搜索商品结果
    startDate: '', //最小日期
    date: '', //日期
    moreInfoHeight: 0, //更多信息 顶部距离
    otherMoneyListIndex: 0, //其他费用 下标
    otherMoneyList: [], //其他费用 类型
    otherMoneyListId: 0, //其他费用 id
    otherMoney: 0, //其他费用 金额
    lock_version: 0, //锁版本
    hrnum: {
      show: false,
      value: '',
      numberArr: [],
      pwdArr: ["", "", "", "", "", ""],
      temp: ["", "", "", "", "", ""]
    }, //金额键盘
    hrnumTarget: '', //金额键盘针对哪个变量修改
    moveFload: 0, //抹零
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
    //垫付方式
    advancePaymentIndex: 0,
    advancePaymentId: 0,
    advancePayment: [{
        type: 0,
        name: '我方垫付'
      },
      {
        type: 1,
        name: '对方垫付'
      },
      {
        type: 2,
        name: '我方自付'
      },
    ],
    //附件名称
    fileName: '',
    //附件临时路径
    filePath: '',
    //备注
    remark: '',
    trueMoney: 0, //实付金额
    is_first: 1, //记录是第一次进来
    fold: false, //更多信息图标
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },
  /**
   * 显示隐藏订单表单
   */
  onShowHeader(e) {
    let _this = this;
    builderPageHeight(this, function (data) {
      let showHeader = _this.data.showHeader == '' ? 'none' : '';
      let scrollViewHeight = showHeader == '' ? (data.windowHeight - data.navbarHeight - data.headerHeight - data.tabBarHeight) : (data.windowHeight - data.navbarHeight - data.mainBarHeight - data.tabBarHeight)
      // 算出来之后存到data对象里面
      _this.setData({
        showHeader: showHeader,
        mScrollViewHeight: scrollViewHeight,
      });
    });
  },
  /**
   * 显示查询商品
   */
  onShowSearchGoods(e) {
    this.getGoodsByWhere();
    this.setData({
      isGoodsSearchShow: true
    });
  },
  /**
   * 隐藏查询商品
   */
  onHideSearchGoods(e) {
    this.setData({
      isGoodsSearchShow: false,
      searchGoodsName: ''
    });
  },
  /**
   * zll 根据条件请求商品列表
   * 当扫码时没有款号,只有颜色或尺码时或者两者都有或两者都没有
   */
  getGoodsByWhere(where) {
    //默认先显示所有商品
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
      // org_id:_this.data.organizationList_value  //仓库中没有商品时,不会返回  有商品但库存为0的才会返回  所以采购单暂时没发根据仓库id选择商品
    }, (res) => {
      let list = res.data
      for (let i of list) {
        let retailPrice = i.goods_rprice //零售价
        let purchase = i.goods_pprice //采购价
        i.goods_rprice = purchase //在采购单中 统一使用采购价格
        i.type = 'caigoudan' //采购单标识
        i.cg_rprice = retailPrice //真正的零售价

      }
      _this.setData({
        searchGoodsList: list,
        searchGoodsListOk: true
      });
    });
  },
  /**
   * zll 模糊查询商品
   */
  searchGoods: function () {
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
        // org_id:_this.data.organizationList_value  //仓库中没有商品时,不会返回  有商品但库存为0的才会返回  所以采购单暂时没发根据仓库id选择商品

        goods_name: _this.data.searchGoodsName,
      },
      (res) => {
        let list = res.data
        for (let i of list) {
          let retailPrice = i.goods_rprice //零售价
          let purchase = i.goods_pprice //采购价
          i.goods_rprice = purchase //在采购单中 统一使用采购价格
          i.type = 'caigoudan' //采购单标识
          i.cg_rprice = retailPrice //真正的零售价


        }
        _this.setData({
          searchGoodsList: list,
          searchGoodsName: '',
          searchGoodsListOk: true
        });
      }, '', '', '', true);
  },
  /**
   * zll
   * 选择搜索框中商品
   */
  onSelectGoods(e) {
    let _this = this;
    let goods = _this.data.searchGoodsList[e.currentTarget.dataset.index];
    // console.log(goods);
    _this.setData({
      isGoodsSearchShow: false
    });
    _this.editGoodsFor(goods);
  },
  //zll 检索商品是否重复,然后打开商品编辑器
  editGoodsFor: function (goods) {
    console.log("即将打开编辑器");
    console.log(goods);
    let _this = this;
    //检索选择的商品是否已经被选过,如果被选过,使用已经选过的商品,因为可能已经选了数量
    try {
      _this.data.list.forEach((item) => {
        if (item.goods_code == goods.goods_code) {
          goods = item;
          throw new Error("有相同商品,使用已选商品");
        }
      });
    } catch (err) {
      console.log(err.message);
    }

    _this.setData({
      mSelectIndex: 0,
      goods: goods,
      mSelectType: true,
      listGoods: goods.list,
      mIsDisplay: true
    });
  },
  /**
   * 扫码
   */
  onScanGoods(e) {
    let _this = this;
    _this.setData({
      editOrScan: 0,
    });
    wx.scanCode({
      success(res) {
        let codeObj = JSON.parse(res.result);
        console.log(codeObj, 184181)
        app._get("web/goods/loadPurchaseGoods", {
            goods_name: codeObj,
            // org_id:_this.data.organizationList_value  //仓库中没有商品时,不会返回  有商品但库存为0的才会返回  所以采购单暂时没发根据仓库id选择商品

          },
          (res) => {
            if (res.errcode != 0) {
              wx.showToast({
                title: res.errmsg,
                icon: 'none'
              })
              return
            }
            _this.setData({
              isGoodsSearchShow: true,
              searchGoodsName: codeObj,
              searchGoodsList: res.data,
              searchGoodsListOk: true
            });
          });
      }
    });
  },
  /**
   * zll 商品列表改变后,组件调用此方法
   */
  onListChange: function (e) {
    let _this = this;
    //如果订单已完成,不使用此方法计算实收金额;
    if (_this.data.orders_status === 9) {
      return
    }
    //如果是草稿,第一次进入时不使用此方法计算实收金额 
    if (_this.data.orders_status === 0 && _this.data.is_first === 1) {
      _this.setData({
        is_first: 0
      });
      return
    }

    this.setData({
      sumprice: e.detail.sumprice,
      trueMoney: e.detail.sumprice,
      sumcount: e.detail.sumcount
    });


  },
  /**
   * zll 供应商编辑
   */
  pickerchange: function (e) {
    this.setData({
      picker_index: e.detail.value
    });
  },
  /**
   * zll 添加商品
   */
  addGoods: function (goods) {
    console.log(goods);
    let isRepeat = 0;
    let _this = this;
    let goodsListNow = _this.data.list;
    goodsListNow.forEach((element, index) => {
      //名称
      if (element.goods_code == goods.goods_code) {
        element.list.forEach((item, item_index) => {
          goods.list.forEach((gitem, gitem_index) => {
            //颜色相同
            if (item.color_name == gitem.color_name) {
              item.sizeList.forEach((sizeItem, sizeItem_index) => {
                gitem.sizeList.forEach((sizeGItem, sizeGItem_index) => {
                  //尺寸相同
                  if (sizeItem.size_name == sizeGItem.size_name) {
                    if (false) { // _this.data.editOrScan == 0) { //扫码时数量累加
                      element.inputQuantity += sizeGItem.inputQuantity;
                      item.inputQuantity += sizeGItem.inputQuantity;
                      sizeItem.inputQuantity += sizeGItem.inputQuantity;
                    } else { //编辑时数量覆盖
                      element.inputQuantity = goods.inputQuantity;
                      item.inputQuantity = gitem.inputQuantity;
                      sizeItem.inputQuantity = sizeGItem.inputQuantity;
                    }
                    isRepeat = 1;
                  }
                });
              });
            }
          });
        });
      }
    });
    if (isRepeat) {
      _this.setData({
        list: goodsListNow
      });
    } else {
      let goodsList = [..._this.data.list, goods];
      _this.setData({
        list: goodsList
      });
    }
    _this.savePurchaseOrderCache();
    if (_this.data.editOrScan == 0) {
      //商品列表滑动到底部
      // _this.setData({
      //   goodsScrollTop:_this.data.list.length * 75
      // });
    }
  },
  onLoad: function (o) {
    if (o.orders_code) {
      this.setData({
        haveOrdersMsg:true
      })
    }
    wx.showLoading({
      title: '加载中',
    })
    let _this = this;
    app.checkNullData()
    _this.getSupplierList().then(res => {
      return _this.getOrganization();
    }).then(res => {
      return new Promise((resolve) => {
        let nowDate = _this.getNowDate();
        _this.setData({
          date: nowDate,
          startDate: '2000-1-1'
        }, () => {
          console.log("时间完毕");
          resolve("ok");
        });
      })
    }).then((res) => {
      _this.getOtherMoneyList()
    }).then((res) => {
      if (o.orders_code) {
        _this.getOrdersDetails(o.orders_code)
      } else {
        _this.getPurchaseOrderCache();
      }
    });
  },
  getDate(date) {
    //date是传过来的时间戳，注意需为13位，10位需*1000
    //也可以不传,获取的就是当前时间
    console.log(date, 666666666)
    var time = new Date(parseInt(date * 1000));
    var year = time.getFullYear() //年
    var month = ("0" + (time.getMonth() + 1)).slice(-2); //月
    var day = ("0" + time.getDate()).slice(-2); //日
    var mydate = year + "-" + month + "-" + day;
    return mydate
  },
  //获取详情
  getOrdersDetails: function (orders_code) {
    app._get('web/purchaseOrders/getOrdersInfo', {
      orders_code: orders_code
    }, (res) => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        });
      }
      let _this = this;
      let data = _this.data

      data.otherMoneyList.forEach((item, index) => {
        if (res.data.other_type == item.dict_id) {
          _this.setData({
            otherMoneyListIndex: index,
            otherMoneyListId: res.data.other_type,
          })
        }
      });
      _this.setData({
        otherMoneyListId: res.data.other_type,
        otherMoneyListIndex: _this.data.otherMoneyList.findIndex(i => i.dict_id == res.data.other_type),
        orders_code: res.data.orders_code,
        orders_status: res.data.orders_status,
        lock_version: res.data.lock_version,
        supplierList_value: res.data.supplier_id, // //设置供应商id
        supplierList_index: _this.data.supplierList.findIndex(i => i.supplier_id == res.data.supplier_id),
        supplier_money: _this.data.supplierList[_this.data.supplierList.findIndex(i => i.supplier_id == res.data.supplier_id)].supplier_money,
        organizationList_value: res.data.warehouse_id, // //仓库id
        organizationList_index: _this.data.organizationList.findIndex(i => i.org_id == res.data.warehouse_id),
        trueMoney: res.data.orders_rmoney, // //设置实付金额
        date: _this.getDate(res.data.orders_date), // //设置日期
        moveFload: res.data.erase_money, // //设置抹零
        otherMoney: res.data.other_money, // //其他费用金额
        remark: res.data.orders_remark, // //备注信息
        list: res.data.details, // //商品列表
        sumprice: res.data.orders_pmoney, // //总价
        sumcount: res.data.goods_number, // //总量
        fileName: res.data.attach ? res.data.attach.name : '', //附件name
        downlod_url: res.data.attach ? res.data.attach.downlod_url : '', //附件下载
        loadOk: true,
      })
      wx.hideLoading()

    }, '', '', '', true);
  },
  //下载附件
  downLod() {
    return false
    let url = this.data.downlod_url
    if (url) {
      wx.downloadFile({
        url,
        success(res) {
          if (res.statusCode === 200) {
            console.log(res, 'ok')
            wx.saveFile({
              tempFilePath: res.tempFilePath,
              success(res) {
                const savedFilePath = res.savedFilePath
                console.log('保持成功，路径为：', res, savedFilePath)
                wx.showToast({
                  icon: 'none',
                  title: '下载成功',
                })
              },
              fail() {
                wx.showToast({
                  icon: 'none',
                  title: '下载失败',
                })
              }
            })
          }
        },
        fail(err) {
          console.log(err)
          wx.showToast({
            icon: 'none',
            title: '下载失败',
          })
        }
      })
    }
  },
  onShow: function () {
    let _this = this;
    builderPageHeight(this, function (data) {
      console.log(data);
      // 算出来之后存到data对象里面
      _this.setData({
        mScrollViewHeight: data.windowHeight - data.navbarHeight - data.headerHeight - data.tabBarHeight,
        moreInfoHeight: data.headerHeight + data.navbarHeight - data.mainBarHeight
      });
      _this.setData({
        isRefresher: true
      });
    });
  },
  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /** 
   * 编辑商品
   */
  onEditGoods(e) {
    console.log("提交编辑器数据", e);
    let _this = this;
    this.hideGoodsListModal(e);

    let eGoodsInfo = e.detail.goodsInfo;
    console.log(eGoodsInfo.list);
    //计算商品总量和总价
    let sumNum = 0;
    // eGoodsInfo.colorList.forEach((item)=>{
    //   // console.log(item);
    //   sumNum += item.inputQuantity;
    // });
    for (let item in eGoodsInfo.colorList) {
      sumNum += eGoodsInfo.colorList[item].inputQuantity;
    }

    _this.setData({
      editOrScan: 1,
      mSelectIndex: 0,
      goods: eGoodsInfo,
      listGoods: eGoodsInfo.list,
      mIsDisplay: true,
      editGoodsIndex: e.detail.index
    });
  },
  /**
   * zll goods组件"完成"事件
   */
  onReturnGoodsData: function (e) {
    console.log("编辑器返回数据", e);
    let _this = this;
    let detail = e.detail;
    _this.addGoods(detail);
    // let linshi_list = _this.data.list;

    // linshi_list[_this.data.editGoodsIndex] = detail;
    // _this.setData({
    //   list:linshi_list
    // });
  },
  /**
   * zll goods组件点击 加减号 触发
   */
  onReturnRealTimeData: function (e) {
    console.log(e);
  },
  /**
   * 搜索商品
   */
  onSearch(e) {
    let _this = this;

  },

  /**
   * 隐藏查询商品窗口
   */
  hideGoodsListModal(e) {
    this.setData({
      glModal: false
    });
  },

  onShowDiscountSetting(e) {
    this.setData({
      showDiscountSetting: true
    });
  },

  onHideDiscountSetting(e) {
    this.setData({
      showDiscountSetting: false
    });
  },

  onPulling(e) {
    //console.log('onPulling:', e)
  },

  onRefresh() {
    if (this._freshing) return
    this._freshing = true
    setTimeout(() => {
      this.setData({
        triggered: false,
      })
      this._freshing = false;
      console.log('开始刷新');
    }, 500)
  },

  onRestore(e) {
    //console.log('onRestore:', e)
  },

  onAbort(e) {
    //console.log('onAbort', e)
  },
  onMore(e) {
    //console.log('onMore', e);
    console.log('加载更多');
  },
  //zll 点击"更多信息"按钮
  onShowMore: function (e) {
    let _this = this;
    _this.setData({
      dropShow: !_this.data.dropShow,
      fold:!_this.data.fold
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //zll 关闭"更多信息"框
  btnCloseDrop() {
    this.setData({
      scrollTop: 0,
      dropShow: false,
      fold:!this.data.fold
    })
  },
  //zll 获取供应商列表
  getSupplierList: function () {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("web/supplier/getSupplierList", {}, (res) => {
        if(_this.data.haveOrdersMsg){//如果有单据信息 不设置默认
          _this.setData({
            supplierList: res.data,
          }, () => {
            console.log("供应商列表完毕");
            resolve("ok");
          });
        }else{
          _this.setData({
            supplierList: res.data,
            supplierList_value: res.data[0].supplier_id,
            supplier_money: res.data[0].supplier_money
          }, () => {
            console.log("供应商列表完毕");
            resolve("ok");
          });
        }
        
      }, '', '', '', true);
    });

  },
  //zll 选择供应商
  supplierListchange: function (e) {
    let _this = this;
    _this.setData({
      supplierList_index: e.detail.value,
      supplierList_value: _this.data.supplierList[e.detail.value].supplier_id,
      supplier_money: _this.data.supplierList[e.detail.value].supplier_money
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取仓库列表
  getOrganization: function () {
    console.log(this.data.haveOrdersMsg,'有单据信息~')
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("web/organization/getList", {}, (res) => {
        if(_this.data.haveOrdersMsg){//如果有单据信息 不设置默认
          _this.setData({
            organizationList: res.data,
          }, () => {
            console.log("仓库列表完毕");
            resolve("ok");
          });
        }else{
          _this.setData({
            organizationList: res.data,
            organizationList_value: res.data[0].org_id
          }, () => {
            console.log("仓库列表完毕");
            resolve("ok");
          });
        }
        
      }, '', '', '', true);
    });

  },
  //zll 选择仓库
  organizationListchange: function (e) {
    let _this = this;
    _this.setData({
      organizationList_index: e.detail.value,
      organizationList_value: _this.data.organizationList[e.detail.value].org_id
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取商品列表
  getGoodsList: function () {
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
      // org_id:_this.data.organizationList_value  //仓库中没有商品时,不会返回  有商品但库存为0的才会返回  所以采购单暂时没发根据仓库id选择商品

    }, (res) => {
      let list = res.data
      for (let i of list) {
        let retailPrice = i.goods_rprice //零售价
        let purchase = i.goods_pprice //采购价
        i.goods_rprice = purchase //在采购单中 统一使用采购价格
        i.type = 'caigoudan' //采购单标识
        i.cg_rprice = retailPrice //真正的零售价

      }
      _this.setData({
        list
      });
    }, '', '', '', true);
  },
  //zll 选择日期
  DateChange(e) {
    let _this = this;
    _this.setData({
      date: e.detail.value
    }, () => {
      _this.savePurchaseOrderCache();
    })
  },
  //zll 获取当前年月日
  getNowDate() {
    return util.getNowDate();
  },
  //zll 修改其他费用 类型
  OtherMoneyChange(e) {
    let _this = this;
    _this.setData({
      otherMoneyListIndex: e.detail.value,
      otherMoneyListId: _this.data.otherMoneyList[e.detail.value].dict_id
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 显示金额键盘,target 针对哪个变量进行修改
  showHrNumber(e) {
    if (this.data.orders_status == 9) {
      return
    }
    let _this = this;
    _this.setData({
      'hrnum.show': true,
      'hrnum.value': _this.data[e.currentTarget.dataset.target],
      hrnumTarget: e.currentTarget.dataset.target
    });
  },
  //zll 金额键盘 确定
  onNumberConfirm(e) {
    let _this = this;
    _this.setData({
      [_this.data.hrnumTarget]: e.detail > 0 ? e.detail : 0
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取其他费用列表
  getOtherMoneyList() {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("/web/dict/loadAccountListY", {}, (res) => {
        _this.setData({
          otherMoneyList: res.data,
          otherMoneyListId: res.data[0].dict_id
        }, () => {
          console.log("其他费用 完毕");
          resolve("ok");
        });
      }, '', '', '', true);
    });

  },
  saveTip() {
    let _this = this
    if (_this.data.list.length <= 0 || _this.data.sumprice <= 0) {
      wx.showToast({
        title: '请选择商品',
        icon: 'none'
      });
      return false;
    }
    if (_this.data.trueMoney <= 0) {
      wx.showToast({
        title: '请输入实付金额',
        icon: 'none'
      });
      return false;
    }
    this.showTip("请选择保存方式", 'saveOrder');
  },
  //保存单据
  saveOrder(e) {
    let _this = this;
    let status = 0; //草稿或正式
    if (e.detail.index == 0) { //保存草稿
      console.log("草稿");
    } else if (e.detail.index == 1) { //保存正式
      console.log("正式");
      status = 9;
    }
    _this.tipCloseActionSheet();
    _this.postOrderInfo(status);
  },
  //zll 选择文件 
  chooseFile(e) {
    if (this.data.orders_status == 9) {
      return
    }
    let _this = this;
    console.log("选择文件");
    wx.chooseMessageFile({
      count: 1,
      type: 'all',
      success(res) {
        console.log(res.tempFiles)
        if (res.tempFiles[0].size > (1024 * 1024 * 5)) {
          wx.showToast({
            title: '附件不能超过5M',
            icon: 'none'
          })
          return
        }
        if (res.tempFiles.length > 0) {
          _this.setData({
            fileName: res.tempFiles[0].name,
            filePath: res.tempFiles[0].path
          });
        }
      }
    })
  },
  //提交采购单数据到接口
  postOrderInfo(status) {
    let _this = this;
    let data = {
      supplier_id: _this.data.supplierList_value || _this.data.supplierList[_this.data.supplierList_index].supplier_id, //供应商id
      warehouse_id: _this.data.organizationList_value || _this.data.organizationList[_this.data.organizationList_index].org_id, //仓库id
      settlement_id: 0, //结算账户id
      orders_date: _this.data.date, //单据日期
      lock_version: _this.data.lock_version,
      orders_rmoney: _this.data.trueMoney, //实付金额
      orders_remark: _this.data.remark, //备注
      other_type: _this.data.otherMoneyList[_this.data.otherMoneyListIndex].dict_id, //其他费用类型
      other_money: _this.data.otherMoney, //其他费用
      erase_money: _this.data.moveFload, //抹零金额
      goods_number: _this.data.sumcount, //商品数量
      orders_pmoney: _this.data.sumprice, //应付金额
      // orders_code:0,//单号
      orders_status: status,
      details: JSON.stringify(_this.goodsListToGoods(status)), //商品列表
      orders_remark: _this.data.remark,
    };
    _this.data.orders_code ? data.orders_code = _this.data.orders_code : ''
    _this.data.lock_version ? data.lock_version = _this.data.lock_version : ''

    if(data.supplier_id===0){
        wx.showToast({
          title: '供应商id错误',
          icon: 'error',
          duration: 1500
        });
        return
    }
    if(data.warehouse_id===0){
      wx.showToast({
        title: '仓库id错误',
        icon: 'error',
        duration: 1500
      });
      return
  }

    //如果有附件,上传附件
    if (_this.data.filePath) {
      console.log('上传附件')
      wx.uploadFile({
        url: app.api_root + 'web/upload/single_upload',
        filePath: _this.data.filePath,
        name: 'file',
        formData: {
          sort: _this.data.filePath,
          filePath: _this.data.filePath,
          file_type: "attachment",
          token: wx.getStorageSync('token'),
        },
        success(res) {
          let data2 = JSON.parse(res.data);
          if (data2.errcode == 0) {
            data.attachment = JSON.stringify(data2.data)
            console.log('上传附件完成,提交采购单')
            submit()
          } else {
            app.showError(data2.errmsg);
          }
        }
      })
    } else {
      console.log('直接提交采购单')
      submit()
    }

    function submit() {
      app._post_form(
        "/web/PurchaseOrders/savePurchaseOrders",
        data,
        (res) => {
          if (res.errcode != 0) {
            _this.setData({
              orders_status: 0
            })
            //为调用失败
            return wx.showToast({
              title: res.errmsg,
              icon: 'error',
              duration: 1500,
            });
          }
          //接口调用成功的函数
          wx.showToast({
            title: res.errmsg,
            icon: 'success',
            duration: 1500
          });
          wx.showToast({
            title: '提交成功',
          });
          setTimeout(function () {
            wx.navigateBack({
              delta: 1,
              success(res) {
                //清除缓存 
                wx.removeStorage({
                  key: _this.data.chche_name,
                })
              }
            });
          }, 800)
        }
      );
    }


  },
  /**
   * zll 显示提示框
   */
  showTip: function (tip, funName, itemList = [{
    color: '#8799A3',
    text: '保存为草稿'
  }, {
    color: '#39B54A',
    text: '保存为采购单'
  }, ]) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: itemList,
      tipItemClickName: funName
    });
  },
  /**
   * zll 点击提示框确认按钮
   */
  tipItemClick: function (e) {
    let _this = this;
    _this.setData({
      shopId: _this.data.check_shopId,
      shopIndex: _this.data.check_shopIndex,
      tipShow: false,
      shopListHeight: 0
    });
    _this.getShopListHeight();
  },
  /**
   * zll 点击提示框取消
   */
  tipCloseActionSheet: function (e) {
    let _this = this;
    _this.setData({
      tipShow: false
    });
  },
  //zll 垫付方式 选择
  advancePaymentChange(e) {
    let _this = this;
    _this.setData({
      advancePaymentIndex: e.detail.value,
      advancePaymentId: this.data.advancePayment[e.detail.value].type
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 拆解商品列表层级,转换成每一个色码尺码货号就是一个商品
  goodsListToGoods(status) { //status为草稿时,把没选择的商品也传过去
    let _this = this;
    let goodsList = _this.data.list;
    //存储遍历后的商品列表
    let goodsListPart = [];
    for (let i = 0; i < goodsList.length; i++) {
      let color_list = goodsList[i].list;
      for (let j = 0; j < color_list.length; j++) {
        let size_list = color_list[j].sizeList;
        for (let k = 0; k < size_list.length; k++) {
          let size = size_list[k];
          if (size.inputQuantity > 0 || status == 0) {
            let linshiGoods = {
              goods_id: goodsList[i].goods_id,
              goods_code: goodsList[i].goods_code,
              color_id: color_list[j].color_id,
              size_id: size.size_id,
              goods_number: size.inputQuantity,
              goods_price: goodsList[i].goods_pprice,
              goods_tmoney: goodsList[i].goods_pprice * size.inputQuantity,
            };
            goodsListPart.push(linshiGoods);
          }
        }
      }
    }
    return goodsListPart;
  },
  //zll 删除商品提示
  onDelGoodsTip() {
    this.showTip("确认删除商品吗?", 'onDelGoods', [{
      color: '#E54D42',
      text: '确认'
    }]);
  },
  //zll 删除商品操作
  onDelGoods(e) {
    let goodsIndex = e.detail.index;
    let _this = this;
    let goodsList = _this.data.list;
    goodsList.splice(goodsIndex, 1);
    _this.setData({
      list: goodsList
    }, () => {
      _this.savePurchaseOrderCache();
    });
    this.tipCloseActionSheet();
  },

  //zll 存储采购单缓存
  savePurchaseOrderCache() {
    return
    let _this = this;
    let cache = {};
    //供应商id
    cache.supplierList_value = _this.data.supplierList_value;
    //仓库id
    cache.organizationList_value = _this.data.organizationList_value;
    //实付金额
    cache.trueMoney = _this.data.trueMoney;
    //日期选择
    cache.date = _this.data.date;
    //抹零
    cache.moveFload = _this.data.moveFload;
    //其他费用
    cache.otherMoneyListId = _this.data.otherMoneyListId;
    cache.otherMoneyListIndex = _this.data.otherMoneyListIndex;
    //费用金额
    cache.otherMoney = _this.data.otherMoney;
    //垫付方式id
    // cache.advancePaymentId = _this.data.advancePaymentId;
    //备注信息
    cache.remark = _this.data.remark;
    //商品列表
    cache.list = _this.data.list;
    //总价
    cache.sumprice = _this.data.sumprice;
    //总量
    cache.sumcount = _this.data.sumcount;

    wx.setStorageSync(_this.data.chche_name, cache);
  },
  //zll 获取采购单缓存信息
  getPurchaseOrderCache() {
    let _this = this;
    let cache = wx.getStorageSync(_this.data.chche_name);
    let data = _this.data;
    if (cache) {
      //供应商id
      data.supplierList_value = cache.supplierList_value;
      //供应商下标 账户余额
      data.supplierList.forEach((item, index) => {
        if (item.supplier_id == cache.supplierList_value) {
          data.supplierList_index = index;
          data.supplier_money = _this.data.supplierList[index].supplier_money
        }
      });
      //仓库id
      data.organizationList_value = cache.organizationList_value;
      //仓库下标

      data.organizationList.forEach((item, index) => {
        if (item.org_id == cache.organizationList_value) {
          data.organizationList_index = index;
        }
      });
      //实付金额
      data.trueMoney = cache.trueMoney;
      //日期选择
      data.date = cache.date;
      //抹零
      data.moveFload = cache.moveFload;
      //其他费用 id
      data.otherMoneyListId = cache.otherMoneyListId;
      data.otherMoneyList.forEach((item, index) => {
        if (item.dict_id == cache.otherMoneyListId) {
          data.otherMoneyListIndex = index;
        }
      });


      //费用金额
      data.otherMoney = cache.otherMoney;

      //备注信息
      data.remark = cache.remark;
      //商品列表
      data.list = cache.list;
      //总价
      data.sumprice = cache.sumprice;
      //总量
      data.sumcount = cache.sumcount;

    }else{//如果没有缓存 设置默认的供应商 供应商账户 仓库
      _this.setData({
        organizationList_index:0,
        organizationList_value: _this.data.organizationList[0].org_id,
        supplierList_index:0,
        supplierList_value:  _this.data.supplierList[0].supplier_id,
        supplier_money:  _this.data.supplierList[0].supplier_money,
      })

    }
    _this.setData(data);
    _this.setData({
      loadOk: true
    })
    wx.hideLoading()

  }
});

/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('example4_height');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    // 页面总高度将会放在这里
    windowHeight: 0,
    // navbar的高度
    navbarHeight: 0,
    // header的高度
    headerHeight: 0,
    // order form 的高度
    orderViewHeight: 0,
    // order button 的高度
    btnOrderHeight: 0,
    // 操作条的高度
    mainBarHeight: 0,
    // 底部操作条的高度
    tabBarHeight: 0,
    // scroll-view 的高度
    scrollViewHeight: 0
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  // 先创建一个 SelectorQuery 对象实例
  let query = wx.createSelectorQuery().in(_this);
  // 然后逐个取出navbar、header、order、btnForm的节点信息
  // 选择器的语法与jQuery语法相同
  query.select('#navbar').boundingClientRect();
  query.select('#header').boundingClientRect();
  query.select('#order').boundingClientRect();
  query.select('#btnOrder').boundingClientRect();
  query.select('#mainBar').boundingClientRect();
  query.select('#tabBar').boundingClientRect();
  // 执行上面所指定的请求，结果会按照顺序存放于一个数组中，在callback的第一个参数中返回
  query.exec((res) => {
    //console.log(res);
    data.navbarHeight = res[0].height;
    data.headerHeight = res[1].height;
    data.orderViewHeight = res[2].height;
    data.btnOrderHeight = res[3].height;
    data.mainBarHeight = res[4].height;
    data.tabBarHeight = res[5].height;

    wx.setStorageSync('example4_height', data);
    _fun && _fun(data);
  });
};