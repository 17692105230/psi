const app = getApp();
const util = require("../../../utils/util.js");
Page({
  data: {
    chche_name: 'SaleOrderCache', //缓存单名称
    list: [], //商品列表
    sumprice: 0, //总价
    sumcount: 0, //总量
    discount: 100, //折扣
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
    clientList: [], //客户列表
    clientList_index: -1, //客户下标  
    clientList_value: 0, //客户id
    userList: [], //销售员列表
    userList_index: -1, //销售员下标
    userList_value: 0, //销售员id
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
    trueMoney: 0, //实收金额
    deliveryListIndex: 0, //发货方式 下标
    deliveryList: [], //发货方式 列表
    deliveryListId: 0, //其他费用 id
    settlementListIndex: 0, //结算账户 下标
    settlementList: [], //结算账户 列表
    settlementListId: 0, //结算账户 id
    orders_code: '', //单号
    lock_version: 0, //锁版本
    is_first: 1, //记录是第一次进来
    fold: false, //更多信息图标
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },
  //显示隐藏订单表单
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
  //显示查询商品
  onShowSearchGoods(e) {
    this.getGoodsByWhere();
    this.setData({
      isGoodsSearchShow: true
    });
  },
  //隐藏查询商品
  onHideSearchGoods(e) {
    this.setData({
      isGoodsSearchShow: false,
      searchGoodsName: '',
    });
  },
  // zll 根据条件请求商品列表   * 当扫码时没有款号,只有颜色或尺码时或者两者都有或两者都没有   */
  getGoodsByWhere(where) {
    //默认先显示所有商品
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
      org_id: _this.data.organizationList_value
    }, (res) => {
      _this.setData({
        searchGoodsList: res.data,
        searchGoodsListOk: true
      });
    });
  },
  // zll 模糊查询商品   */
  searchGoods: function () {
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
        goods_name: _this.data.searchGoodsName,
        org_id: _this.data.organizationList_value

      },
      (res) => {
        _this.setData({
          searchGoodsList: res.data,
          searchGoodsName: '',
          searchGoodsListOk: true
        });
      });
  },
  //     * 选择搜索框中商品   */
  onSelectGoods(e) {
    let _this = this;
    let goods = _this.data.searchGoodsList[e.currentTarget.dataset.index];
    _this.setData({
      isGoodsSearchShow: false
    });
    _this.editGoodsFor(goods);
  },
  //zll 检索商品是否重复,然后打开商品编辑器
  editGoodsFor: function (goods) {
    let _this = this;
    //检索选择的商品是否已经被选过,如果被选过,使用已经选过的商品,因为可能已经选了数量
    try {
      _this.data.list.forEach((item) => {
        if (item.goods_code == goods.goods_code) {
          goods = item;
          throw new Error("有相同商品,使用已选商品");
        }
      });
    } catch (err) {}
    _this.setData({
      mSelectIndex: 0,
      goods: goods,
      mSelectType: true,
      listGoods: goods.list,
      mIsDisplay: true
    });
  },
  // 扫码   */
  onScanGoods(e) {
    let _this = this;
    _this.setData({
      editOrScan: 0,
    });
    wx.scanCode({
      success(res) {
        let codeObj = JSON.parse(res.result);
        app._get("web/goods/loadPurchaseGoods", {
            org_id: _this.data.organizationList_value,
            goods_name: codeObj,
          },
          (res) => {
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
  /**   * zll 商品列表改变后,组件调用此方法   */
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
  /**   * zll 供应商编辑   */
  pickerchange: function (e) {
    this.setData({
      picker_index: e.detail.value
    });
  },

  /**   * zll 添加商品   */
  addGoods: function (goods) {
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
                    if (false) { //_this.data.editOrScan == 0) { //扫码时数量累加
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
  onLoad: function (options) {
    if (options.id) {
      this.setData({
        haveOrdersMsg: true
      })
    }
    if (options.orders_type == 1) {
      this.setData({
        isRetail: true
      })
    }
    wx.showLoading({
      title: '加载中',
    })
    let _this = this;
    app.checkNullData()
    _this.getClientList().then(res => {
      return _this.getUserList();
    }).then(res => {
      return _this.getOrganization();
    }).then(res => {
      return new Promise((resolve) => {
        let nowDate = _this.getNowDate();
        _this.setData({
          date: nowDate,
          startDate: '2000-1-1'
        }, () => {
          resolve("ok");
        });
      })
    }).then((res) => {
      return _this.getSettlementList();
    }).then((res) => {
      return _this.getOtherMoneyList()
    }).then((res) => {
      return _this.getDeliveryList();
    }).then((res) => {
      if (options.id) {
        _this.getSaleOrderInfo(options.id)
      } else {
        _this.getPurchaseOrderCache();
      }
    });

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
  //编辑商品
  onEditGoods(e) {
    let _this = this;
    this.hideGoodsListModal(e);

    let eGoodsInfo = e.detail.goodsInfo;
    //计算商品总量和总价
    let sumNum = 0;
    for (let item in eGoodsInfo.colorList) {
      sumNum += eGoodsInfo.colorList[item].inputQuantity;
    }
    let arr = eGoodsInfo.list
    let flag = 0
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].inputQuantity) {
        arr[i].checked = true
        flag = i
        break
      } else {
        arr[i].checked = false
      }
    }
    if (flag == 0) {
      arr[0].checked = true
    }
    _this.setData({
      editOrScan: 1,
      mSelectIndex: flag,
      goods: eGoodsInfo,
      listGoods: eGoodsInfo.list,
      mIsDisplay: true,
      editGoodsIndex: e.detail.index
    });
  },
  //goods组件"完成"事件
  onReturnGoodsData: function (e) {
    let _this = this;
    let detail = e.detail;
    _this.addGoods(detail);
  },


  // 隐藏查询商品窗口
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
      showDiscountSetting: false,
      discount: 100
    });
  },
  setDiscount() {
    this.setData({
      showDiscountSetting: false
    });
  },

  onRefresh() {
    if (this._freshing) return
    this._freshing = true
    setTimeout(() => {
      this.setData({
        triggered: false,
      })
      this._freshing = false;
    }, 500)
  },

  //zll 点击"更多信息"按钮
  onShowMore: function (e) {
    let _this = this;
    _this.setData({
      dropShow: !_this.data.dropShow,
      fold: !_this.data.fold
    }, () => {
      this.setData({
        scrollTop: 0
      })
    })
  },
  //zll 关闭"更多信息"框
  btnCloseDrop() {
    console.log(11111)
    this.setData({
      scrollTop: 0,
      dropShow: false,
      fold: !this.data.fold
    })
  },
  //zll 获取客户列表
  getClientList: function () {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("web/client/getList", {}, (res) => {
        if (_this.data.haveOrdersMsg) { //如果有订单信息 不设置默认
          _this.setData({
            clientList: res.data,
          }, () => {
            resolve("ok");
          });
        } else {
          _this.setData({
            clientList: res.data,
            clientList_value: res.data[0].client_id,
          }, () => {
            resolve("ok");
          });
        }

      }, '', '', '', true);
    });

  },
  //zll 选择客户
  clientListchange: function (e) {
    let _this = this;
    _this.setData({
      clientList_index: e.detail.value,
      clientList_value: _this.data.clientList[e.detail.value].client_id,
      // supplier_money:_this.data.supplierList[e.detail.value].supplier_money
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取销售员列表
  getUserList: function () {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("web/user/getList", {}, (res) => {

        if (_this.data.haveOrdersMsg) { //如果有订单信息 不设置默认
          _this.setData({
            userList: res.data,
          }, () => {
            resolve("ok");
          });

        } else {

          _this.setData({
            userList: res.data,
            userList_value: res.data[0].user_id,
          }, () => {
            resolve("ok");
          });
        }

      }, '', '', '', true);
    });

  },
  //zll 选择销售员
  userListchange: function (e) {
    let _this = this;
    _this.setData({
      userList_index: e.detail.value,
      userList_value: _this.data.userList[e.detail.value].user_id,
      // supplier_money:_this.data.supplierList[e.detail.value].supplier_money
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取仓库列表
  getOrganization: function () {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("web/organization/getList", {}, (res) => {
        if (_this.data.haveOrdersMsg) { //如果有订单信息 不设置默认
          _this.setData({
            organizationList: res.data,
          }, () => {
            resolve("ok");
          });

        } else {
          _this.setData({
            organizationList: res.data,
            organizationList_value: res.data[0].org_id
          }, () => {
            resolve("ok");
          });

        }

      }, '', '', '', true);
    });

  },

  //仓库点击
  orgChange() {
    let orgValue = this.data.organizationList_value
    let orgIndex = this.data.organizationList_index
    this.setData({
      orgValue,
      orgIndex
    })

  },
  //zll 选择仓库
  organizationListchange: function (e) {

    let _this = this;
    if (_this.data.list.length) {
      wx.showModal({
        title: '提示',
        content: '切换仓库将清空已保存的单据信息,是否继续?',
        success(res) {
          if (res.confirm) {
            _this.setData({
              list: [],
            })
          } else if (res.cancel) {
            _this.setData({
              organizationList_value: _this.data.orgValue,
              organizationList_index: _this.data.orgIndex,
            })
            return
          }
        }
      })
    }
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
      org_id: _this.data.organizationList_value

    }, (res) => {
      _this.setData({
        list: res.data
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
          resolve("ok");
        });
      }, '', '', '', true);
    });
  },
  saveTip() {
    let _this = this;
    if (_this.data.list.length <= 0 || _this.data.sumprice <= 0) {
      wx.showToast({
        title: '请选择商品',
        icon: 'none'
      });
      return false;
    }
    if (_this.data.trueMoney <= 0) {
      wx.showToast({
        title: '请输入实收金额',
        icon: 'none'
      });
      return false;
    }
    this.showTip("请选择保存方式", 'saveOrder');
  },
  saveOrder(e) {
    let _this = this;
    let status = 0;
    if (e.detail.index == 0) { //保存草稿
    } else if (e.detail.index == 1) { //保存正式
      status = 9;
    }
    _this.tipCloseActionSheet();

    _this.goodsListToGoods();
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
  //提交单据
  postOrderInfo(status) {
    let _this = this;
    let d = _this.data
    let data = {
      client_id: _this.data.clientList_value || d.clientList[d.clientList_index].client_id, //客户id
      salesman_id: _this.data.userList_value || d.userList[d.userList_index].user_id, //销售员id
      warehouse_id: _this.data.organizationList_value || d.organizationList[d.organizationList_index].org_id, //仓库id
      orders_date: _this.data.date, //日期
      erase_money: _this.data.moveFload, //抹零
      other_type: _this.data.otherMoneyListId || d.otherMoneyList[d.otherMoneyListIndex].dict_id, //其他费用id
      other_money: _this.data.otherMoney, //其他费用 金额
      delivery_id: _this.data.deliveryListId || d.deliveryList[d.deliveryListIndex].dict_id, //发货方式 
      orders_status: status, // 需要提交的 订单状态
      goods_number: _this.data.sumcount, //商品总数
      discount: _this.data.discount, //折扣
      orders_pmoney: _this.data.sumprice, //应收金额
      orders_rmoney: _this.data.trueMoney, //实收金额
      lock_version: _this.data.lock_version, //锁版本
      settlement_id: _this.data.settlementListId || d.settlementList[d.settlementListIndex].settlement_id, //结算账户id
      details: JSON.stringify(_this.goodsListToGoods())
    };
    if (_this.data.orders_code.length > 0) {
      data.orders_code = _this.data.orders_code
    }
    if (data.client_id === 0) {
      wx.showToast({
        title: '客户id错误',
        icon: 'error',
        duration: 1500
      });
      return
    }
    if (data.salesman_id === 0) {
      wx.showToast({
        title: '销售员id错误',
        icon: 'error',
        duration: 1500
      });
      return
    }
    if (data.warehouse_id === 0) {
      wx.showToast({
        title: '仓库id错误',
        icon: 'error',
        duration: 1500
      });
      return
    }
    if (data.other_type === 0) {
      wx.showToast({
        title: '其他费用id错误',
        icon: 'error',
        duration: 1500
      });
      return
    }
    if (data.delivery_id === 0) {
      wx.showToast({
        title: '发货方式id错误',
        icon: 'error',
        duration: 1500
      });
      return
    }
    if (data.settlement_id === 0) {
      wx.showToast({
        title: '结算账户id错误',
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
          token: wx.getStorageSync('token')
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
        'web/sale/saveSaleOrders',
        data,
        (res) => {
          if (res.errcode != 0) {
            _this.setData({
              orders_status: 0
            })
            //调用失败
            return wx.showToast({
              title: res.errmsg,
              icon: 'error',
              duration: 1500
            });

          }
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
            })
          }, 800)

        });
    }

  },
  // 显示提示框
  showTip: function (tip, funName, itemList = [{
    color: '#8799A3',
    text: '保存为草稿'
  }, {
    color: '#39B54A',
    text: '保存为销售单'
  }, ]) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: itemList,
      tipItemClickName: funName
    });
  },
  //点击提示框确认按钮
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
  //点击提示框取消
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
  goodsListToGoods() {
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
          let linshiGoods = {
            goods_id: goodsList[i].goods_id,
            goods_code: goodsList[i].goods_code,
            color_id: color_list[j].color_id,
            size_id: size.size_id,
            goods_number: size.inputQuantity,
            goods_price: goodsList[i].goods_rprice,
            goods_tmoney: goodsList[i].goods_rprice * size.inputQuantity,
          };
          goodsListPart.push(linshiGoods);
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

  //zll 获取发货方式列表
  getDeliveryList() {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("/web/dict/getDeliveryList", {}, (res) => {
        _this.setData({
          deliveryList: res.data,
          deliveryListId: res.data[0].dict_id
        }, () => {
          resolve("ok");
        });
      }, '', '', '', true);
    });
  },
  //zll 发货方式 选择
  deliveryChange(e) {
    let _this = this;
    _this.setData({
      deliveryListIndex: e.detail.value,
      deliveryListId: this.data.deliveryList[e.detail.value].dict_id
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 获取结算账户列表
  getSettlementList() {
    let _this = this;
    return new Promise((resolve, reject) => {
      app._get("/web/settlement/getList", {}, (res) => {
        _this.setData({
          settlementList: res.data,
          settlementListId: res.data[0].settlement_id
        }, () => {
          resolve("ok");
        });
      }, '', '', '', true);
    });
  },
  //zll 结算账户 选择
  settlementChange(e) {
    let _this = this;
    _this.setData({
      settlementListIndex: e.detail.value,
      settlementListId: this.data.settlementList[e.detail.value].settlement_id
    }, () => {
      _this.savePurchaseOrderCache();
    });
  },
  //zll 存储采购单缓存
  savePurchaseOrderCache() {
    return
    let _this = this;
    let cache = {};
    //客户id
    cache.clientList_value = _this.data.clientList_value;
    cache.clientList_index = _this.data.clientList_index;
    //销售员id
    cache.userList_value = _this.data.userList_value;
    cache.userList_index = _this.data.userList_index;
    //仓库id
    cache.organizationList_value = _this.data.organizationList_value;
    cache.organizationList_index = _this.data.organizationList_index;
    //其他费用 id
    cache.otherMoneyListIndex = _this.data.otherMoneyListIndex;
    cache.otherMoneyListId = _this.data.otherMoneyListId;
    //发货方式 id
    cache.deliveryListId = _this.data.deliveryListId;
    cache.deliveryListIndex = _this.data.deliveryListIndex;
    //结算账户 id
    cache.settlementListId = _this.data.settlementListId;
    cache.settlementListIndex = _this.data.settlementListIndex;
    //实付金额
    cache.trueMoney = _this.data.trueMoney;
    //日期选择
    cache.date = _this.data.date;
    //抹零
    cache.moveFload = _this.data.moveFload;
    //费用金额
    cache.otherMoney = _this.data.otherMoney;
    //备注信息
    cache.remark = _this.data.remark;
    //商品列表
    cache.list = _this.data.list;
    //总价
    cache.sumprice = _this.data.sumprice;
    //总量
    cache.sumcount = _this.data.sumcount;
    //折扣
    cache.discount = _this.data.discount;
    wx.setStorageSync(_this.data.chche_name, cache);
  },
  //zll 获取采购单缓存信息
  getPurchaseOrderCache() {
    let _this = this;
    let cache = wx.getStorageSync(_this.data.chche_name);
    let data = _this.data;
    if (cache) {
      //客户id
      data.clientList_value = cache.clientList_value;
      data.clientList_index = _this.data.clientList.findIndex(i => i.client_id == cache.clientList_value)

      //客户id
      data.userList_value = cache.userList_value;
      data.userList_index = _this.data.userList.findIndex(i => i.user_id == cache.userList_value),

        //仓库id
        data.organizationList_value = cache.organizationList_value;
      data.organizationList_index = _this.data.organizationList.findIndex(i => i.org_id == cache.organizationList_value)

      //发货方式 id
      data.deliveryListId = cache.deliveryListId;
      data.deliveryListIndex = _this.data.deliveryList.findIndex(i => i.dict_id == cache.deliveryListId)


      //结算账户 id
      data.settlementListId = cache.settlementListId;
      data.settlementListIndex = _this.data.settlementList.findIndex(i => i.settlement_id == cache.settlementListId)
      //其他费用 id
      data.otherMoneyListId = cache.otherMoneyListId;
      data.otherMoneyListIndex = _this.data.otherMoneyList.findIndex(i => i.dict_id == cache.otherMoneyListId)

      //实付金额
      data.trueMoney = cache.trueMoney;
      //日期选择
      data.date = cache.date;
      //抹零
      data.moveFload = cache.moveFload;



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
      data.discount = cache.discount;

      _this.setData(data);
    } else { //没有缓存信息 则设置默认 客户 销售员 仓库 
      _this.setData({

        clientList_value: _this.data.clientList[0].client_id,
        clientList_index: 0,
        userList_value: _this.data.userList[0].user_id,
        userList_index: 0,
        organizationList_value: _this.data.organizationList[0].org_id,
        organizationList_index: 0,

      })
    }
    _this.setData({
      loadOk: true
    })
    wx.hideLoading()

  },
  //获取销售单详情
  getSaleOrderInfo(orders_id) {
    app._get('web/sale/getSaleOrdersInfo', {
        orders_id: orders_id,
      },
      (res) => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          });
        }
        let _this = this;
        let data = res.data;
        if (data.orders_type == 1) { //收银台产生的单据,获取会员姓名
          app.util.getAllList(['member']).then(res => {
            let members = res.member
            let index = members.findIndex(i => i.member_id == data.member_id)
            _this.setData({
              member_name: members[index].member_name,
              isRetail: true
            })
          })
        }

        _this.setData({
          chche_name: data.orders_code,
          orders_code: data.orders_code,
          orders_status: data.orders_status,
          lock_version: data.lock_version,
          clientList_value: data.client_id,
          clientList_index: _this.data.clientList.findIndex(i => i.client_id == data.client_id),

          userList_value: data.salesman_id,
          userList_index: _this.data.userList.findIndex(i => i.user_id == data.salesman_id),

          organizationList_value: data.warehouse_id,
          organizationList_index: _this.data.organizationList.findIndex(i => i.org_id == data.warehouse_id),

          trueMoney: data.orders_rmoney, //实收金额
          date: _this.getDate(data.orders_date), //日期 
          moveFload: data.erase_money, //抹零
          otherMoneyListId: data.other_type, //其他费用
          otherMoneyListIndex: _this.data.otherMoneyList.findIndex(i => i.dict_id == data.other_type),


          deliveryListId: data.delivery_id, //发货方式id
          deliveryListIndex: _this.data.deliveryList.findIndex(i => i.dict_id == data.delivery_id),


          settlementListId: data.settlement_id, //结算账户    
          settlementListIndex: _this.data.settlementList.findIndex(i => i.settlement_id == data.settlement_id),


          otherMoney: data.other_money, //费用金额  
          remark: data.orders_remark, //备注信息       
          list: data.details, //商品列表   
          sumprice: data.orders_pmoney, //总价     
          sumcount: data.goods_number, //总量
          discount: data.discount,
          fileName: data.attach ? data.attach.name : '', //附件name
          loadOk: true,
        })
        wx.hideLoading()
      }
    )
  },
  getDate(date) {
    var time = new Date(parseInt(date * 1000));
    var year = time.getFullYear() //年
    var month = ("0" + (time.getMonth() + 1)).slice(-2); //月
    var day = ("0" + time.getDate()).slice(-2); //日
    var mydate = year + "-" + month + "-" + day;
    return mydate
  },
});

//构造页面高度
const builderPageHeight = (_this, _fun) => {
  console.log('销售单构造页面高度')

  let data = wx.getStorageSync('sale_order_height');
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
    data.navbarHeight = res[0].height;
    data.headerHeight = res[1].height;
    data.orderViewHeight = res[2].height;
    data.btnOrderHeight = res[3].height;
    data.mainBarHeight = res[4].height;
    data.tabBarHeight = res[5].height;

    wx.setStorageSync('sale_order_height', data);
    _fun && _fun(data);
  });

};