const app = getApp();
Page({
  data: {

    chche_name: 'SaleCheckOrderCache', //缓存单名称
    list: [], //商品列表
    listAll: [], //所有子单据的商品列表
    sumprice: 0, //总价
    sumcount: 0, //总量
    smallMoney: 0, //抹零
    mSelectIndex: 0,
    mSelectType: true,
    disabledOrgChange: false, //是否禁止切换仓库
    // scroll-view的高度
    mScrollViewHeight: 0,
    showHeader: '',
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
    organizationList: [], //仓库列表
    organizationList_index: -1, //仓库下标
    organizationList_value: 0, //仓库id
    //当前子单
    orderList: [{
      id: 1,
      name: '单据1'
    }, ],
    orderList_index: 0,
    orderListId: 1, //子单据id，从零递增
    despatchIndex: 0,
    despatchValue: 0,
    searchGoodsName: "", //搜索商品条件  商品名称
    searchGoodsList: [], //搜索商品结果
    startDate: '', //最小日期
    date: '', //日期
    moreInfoHeight: 0, //更多信息 顶部距离
    hrnum: {
      show: false,
      value: '',
      numberArr: [],
      pwdArr: ["", "", "", "", "", ""],
      temp: ["", "", "", "", "", ""]
    }, //金额键盘
    hrnumTarget: '', //金额键盘针对哪个变量修改
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
    //备注
    remark: '',
    trueMoney: 0, //实收金额
    fold:false,//更多信息
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },
  //新增或删除子单据
  editOrderList(e) {
    let _this = this
    let type = e.currentTarget.dataset.type
    let arr = this.data.orderList
    let len = this.data.orderList.length
    let id = this.data.orderListId
    let i = this.data.orderList_index //之前的单据索引
    if (type == 'add') {
      if (this.data.list.length == 0) {
        wx.showToast({
          title: '当前单据为空',
          icon: 'none'
        })
        return
      }
      arr.push({
        id: id + 1,
        name: `单据${id+1}`
      })
      this.setData({
        orderList: arr,
        orderListId: id + 1,
        orderList_index: arr.findIndex(i => i.id == id + 1)
      })
      _this.setListAll(i, this.data.orderList_index) //设置所有子单的商品数据

    }
    if (type == 'del') {
      wx.showModal({
        title: '提示',
        content: '确定删除吗？',
        success(res) {
          if (res.confirm) {
            arr.splice(i, 1)
            _this.setData({
              orderList: arr,
              orderList_index: i - 0 ? i - 1 : i
            })
            _this.setListAll(i, _this.data.orderList_index) //设置所有子单的商品数据
          }
        }
      })
    }
  },
  //切换子单
  changeOrders(e) {
    let oi = this.data.orderList_index //之前的子单索引
    if (this.data.list.length == 0) {
      wx.showToast({
        title: '当前单据为空,请新增数据或删除单据',
        icon: 'none'
      })
      this.setData({
        orderList_index: oi
      })
      return
    }
    let ni = e.detail.value //新的子单索引
    this.setData({
      orderList_index: ni
    })
    this.setListAll(oi, ni) //设置所有子单的商品数据

  },
  //1.当前子单变化/提交表单/缓存时,设置新的所有商品数据 //2.遍历所有子单,删除无效数据
  setListAll(oi, ni) {
    let d = this.data
    let orderList = d.orderList
    let list = d.list
    let listAll = d.listAll
    let children_name
    //1把当前list 放入对应的listAll===================================================================
    if (orderList[oi] && orderList[oi].name) {
      children_name = orderList[oi].name
      //判断listAll中是否已有对应的list
      let push_index = listAll.findIndex(i => i.children_name == children_name)
      if (push_index != -1) { //有点话,先删除
        listAll.splice(push_index, 1)
      }
      //再push
      listAll.push({
        user_id: 1,
        children_name,
        com_id: 1,
        list
      })
      this.setData({
        listAll
      })
    }

    //2.从listAll中取出当前子单的list=======================================================================
    if (orderList[ni] && orderList[ni].name) {
      children_name = orderList[ni].name
      //判断listAll中是否已有对应的list
      let set_index = listAll.findIndex(i => i.children_name == children_name)
      if (set_index != -1) { //有点话,赋值
        this.setData({
          list: listAll[set_index].list
        })
      } else { //没有的话,置空
        this.setData({
          list: []
        })
      }
    }

    //3. 删除无效数据 
    {
      let listAllFinal = []
      let list = this.data.list
      let listAll = this.data.listAll
      let orderlist = this.data.orderList
      //根据子单列表去所有列表中筛选
      for (let i = 0; i < orderlist.length; i++) {
        for (let j = 0; j < listAll.length; j++) {
          console.log(listAll[j].children_name, orderlist[i].name, 1561546)
          if (listAll[j].children_name == orderlist[i].name) {
            if (listAll[j].list.length > 0) {
              listAllFinal.push(listAll[j])
              continue
            }
          }
        }
      }
      this.setData({
        listAll: listAllFinal
      })
    }
  },
  onLoad(o) {
    let _this = this;
    let id = o.id; //订单id 编辑
    app.checkNullData()
    if (id) {
      this.setData({
        orders_id: id,
        disabledOrgChange: true, //编辑状态禁用仓库切换
        haveOrdersMsg: true,
      })
    }
    _this.setData({
      startDate: '2000-1-1'
    })
    app.util.getAllList(['organization']).then(r => {
      if (_this.data.haveOrdersMsg) { //如果有订单信息 不设置默认
        _this.setData({
          organizationList: r.organization,
        })

      } else {
        _this.setData({
          organizationList: r.organization,
          organizationList_value: r.organization[0].id,
        })

      }

    }).then(_ => id ? _this.getEditData(id) : _this.getPurchaseOrderCache())

  },
  onShow() {
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
  onUnload() {
    //页面销毁时 设置缓存
    //当新增或编辑成功之后 则不需要设置缓存   The noCache is set at location postOrderInfo function callback
    if (!this.data.noCache) {
      this.savePurchaseOrderCache();
    }
  },
  //zll 点击"更多信息"按钮
  onShowMore(e) {
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
    this.setData({
      scrollTop: 0,
      dropShow: false,
      fold:!this.data.fold
    })
  },
  //商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---商品列表操作---

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
      // _this.savePurchaseOrderCache();
    });
    this.tipCloseActionSheet();
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

  //搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----搜索商品弹窗----
  //获取商品列表
  getGoodsList() {
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
      org_id: _this.data.organizationList_value
    }, (res) => {
      // app._get("web/goods/loadInventoryGoods", {}, (res) => {
      _this.setData({
        list: res.data
      });
    });
  },
  //搜索商品按钮 搜索选择商品弹窗
  onShowSearchGoods(e) {
    this.getGoodsByWhere();
    this.setData({
      isGoodsSearchShow: true
    });
  },
  //隐藏搜索选择商品选择弹窗
  onHideSearchGoods(e) {
    this.setData({
      isGoodsSearchShow: false,
      searchGoodsName: ''
    });
  },
  //zll 根据条件请求商品列表
  //当扫码时没有款号,只有颜色或尺码时或者两者都有或两者都没有
  getGoodsByWhere(where) {
    //默认先显示所有商品
    let _this = this;
    // app._get("web/goods/loadInventoryGoods", {}, (res) => {
    app._get("web/goods/loadPurchaseGoods", {
      org_id: _this.data.organizationList_value

    }, (res) => {
      _this.setData({
        searchGoodsList: res.data,
        searchGoodsListOk: true
      });
    });
  },
  //zll 模糊查询商品 
  searchGoods() {
    let _this = this;
    app._get("web/goods/loadPurchaseGoods", {
        // app._get("web/goods/loadInventoryGoods", {
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
  //选择搜索框中商品  
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
  editGoodsFor(goods) {
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
            org_id: _this.data.organizationList_value
          },
          (res) => {
            if (res.errcode != 0) {
              wx.showToast({
                title: res.errmsg,
                icon: 'none',
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
  //zll 商品列表改变后,组件调用此方法
  onListChange(e) {
    this.setData({
      sumprice: e.detail.sumprice,
      trueMoney: e.detail.sumprice,
      sumcount: e.detail.sumcount
    });
  },


  //商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----商品弹窗组件----
  // 编辑商品
  onEditGoods(e) {
    let _this = this;
    this.hideGoodsListModal(e);
    let eGoodsInfo = e.detail.goodsInfo;
    console.log(eGoodsInfo.list);
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
  //goods组件"完成"事件 选择商品完成 添加商品完成
  onReturnGoodsData(e) {
    let _this = this;
    let detail = e.detail;
    _this.addGoods(detail);
  },
  //zll 色码/尺码弹窗 完成按钮  添加商品
  addGoods(goods) {
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
    // _this.savePurchaseOrderCache();

  },
  // zll goods组件点击 加减号 触发
  onReturnRealTimeData(e) {
    console.log(e);
  },
  //搜索商品
  onSearch(e) {
    let _this = this;

  },
  //隐藏查询商品窗口  
  hideGoodsListModal(e) {
    this.setData({
      glModal: false
    });
  },

  //其它---------------------------------------------------------------------------------------------------------------
  //显示设置折扣
  onShowDiscountSetting(e) {
    this.setData({
      showDiscountSetting: true
    });
  },
  //隐藏设置折扣
  onHideDiscountSetting(e) {
    this.setData({
      showDiscountSetting: false
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
  //通用下拉选择
  change(e) {
    console.log(456)
    let _this = this;
    let i = e.detail.value
    let org = e.currentTarget.dataset.type ? e.currentTarget.dataset.type : ''
    if (org == 'org') { //切换仓库提醒
      if (_this.data.listAll.length || _this.data.list.length) {
        wx.showModal({
          title: '提示',
          content: '切换仓库将清空已保存的单据信息,是否继续?',
          success(res) {
            if (res.confirm) {
              _this.setData({
                listAll: [],
                list: [],
                orderList: [{
                  id: 1,
                  name: '单据1'
                }, ],
                orderList_index: 0,
                orderListId: 1,
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
    }

    let data = e.currentTarget.dataset.data_list
    let index = e.currentTarget.dataset.data_index
    let value = e.currentTarget.dataset.data_value
    //设置下拉框选中的value
    if (data) {
      _this.setData({
        [value]: _this.data[data][i].id,
      });
    }
    //下拉框设置选中的索引 日期框设置日期
    _this.setData({
      [index]: e.detail.value,
    });
    //设置缓存
    // _this.savePurchaseOrderCache();
  },
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
      // _this.savePurchaseOrderCache();
    });
  },


  //**   * zll 显示提示框   */
  showTip(tip, funName, itemList = [{
    color: '#8799A3',
    text: '保存为草稿'
  }, {
    color: '#39B54A',
    text: '保存为盘点单'
  }, ]) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: itemList,
      tipItemClickName: funName
    });
  },
  //zll 点击提示框确认按钮
  tipItemClick(e) {
    let _this = this;
    _this.setData({
      shopId: _this.data.check_shopId,
      shopIndex: _this.data.check_shopIndex,
      tipShow: false,
      shopListHeight: 0
    });
    _this.getShopListHeight();
  },
  //zll 点击提示框取消
  tipCloseActionSheet(e) {
    let _this = this;
    _this.setData({
      tipShow: false
    });
  },
  //选择保存方式 草稿或正式
  saveTip() {
    if (this.data.list.length <= 0) {
      wx.showToast({
        icon: 'none',
        title: '当前单据为空',
      })
      return
    }
    this.showTip("请选择保存方式", 'saveOrder');
  },
  //
  saveOrder(e) {
    let _this = this;
    this.setData({
      orders_status: e.detail.index ? 9 : 1 //1 草稿  9 正式
    })
    _this.tipCloseActionSheet();
    _this.postOrderInfo()
  },

  //获取详情  缓存  提交操作 
  //zll 存储缓存 设置缓存
  savePurchaseOrderCache() {
    return
    let i = this.data.orderList_index
    this.setListAll(i, i) //设置缓存
    let _this = this;
    let cache = {};
    //仓库id
    cache.organizationList_value = _this.data.organizationList_value;
    //实付金额
    // cache.trueMoney = _this.data.trueMoney;
    //日期选择
    cache.date = _this.data.date;
    //备注信息
    cache.remark = _this.data.remark;
    //商品列表
    cache.list = _this.data.list;
    //总价
    cache.sumprice = _this.data.sumprice;
    //总量
    cache.sumcount = _this.data.sumcount;
    //抹零
    cache.smallMoney = _this.data.smallMoney

    //所有子单商品
    cache.listAll = _this.data.listAll
    //子单数组
    cache.orderList = _this.data.orderList
    cache.orderList_index = _this.data.orderList_index
    cache.orderListId = _this.data.orderListId
    cache.despatchIndex = _this.data.despatchIndex
    cache.despatchValue = _this.data.despatchValue

    wx.setStorageSync(_this.data.chche_name, cache);
  },
  //zll 获取缓存信息
  getPurchaseOrderCache() {
    let _this = this;
    let cache = wx.getStorageSync(_this.data.chche_name);
    let data = _this.data;
    if (cache) {
      //仓库id
      data.organizationList_value = cache.organizationList_value;
      //仓库下标
      (data.organizationList_index = data.organizationList.findIndex(i => i.id == cache.organizationList_value)) == -1 ? data.organizationList_index = 0 : ''
      //实付金额
      data.trueMoney = cache.trueMoney;
      //日期选择
      data.date = cache.date;
      //备注信息
      data.remark = cache.remark;
      //商品列表
      data.list = cache.list;
      //总价
      data.sumprice = cache.sumprice;
      //总量
      data.sumcount = cache.sumcount;

      //所有子单商品
      data.listAll = cache.listAll
      //子单数组
      data.orderList = cache.orderList
      data.orderList_index = cache.orderList_index

      data.orderListId = cache.orderListId
      data.despatchIndex = cache.despatchIndex
      data.despatchValue = cache.despatchValue

      _this.setData(data);
    } else {
      console.log(app.util.getNowDate(),741741741)
      _this.setData({
        date: app.util.getNowDate(),
        organizationList_index: 0,
        organizationList_value: _this.data.organizationList[0].id,

      })
    }
    _this.setData({
      loadOk: true
    })
  },
  //cpf 获取编辑数据
  getEditData(id) {
    let _this = this;
    let data = _this.data;
    app._get('web/Inventory/get', {
      orders_id: id
    }, r => {
      if (r.errmsg == 'success') {
        let d = r.data
        let resDate = new Date(d.orders_date * 1000)
        let date = {
          y: resDate.getFullYear(),
          m: resDate.getMonth() + 1,
          d: resDate.getDate(),
        }
        this.setData({
          date: `${date.y}-${date.m}-${date.d}`, //订单日期
          organizationList_value: d.warehouse_id, //仓库id
          organizationList_index: this.data.organizationList.findIndex(i => i.id == d.warehouse_id),
          remark: d.orders_remark, //订单备注
          orders_status: d.orders_status, //订单状态
          rders_code: d.orders_code ? d.orders_code : '', //订单编号
          lock_version: d.lock_version, //锁版本
          listAll: d.children, //所有子单数据
          com_id: d.com_id,
          loadOk: true
        })

        //当前子单
        // list: d.details, //当前子单数据
        // sumcount: d.goods_number, //商品数量
        // orderList: [],
        // orderListId: 1, //子单据id，从零递增
        //list 
        let orderList = []
        let listAll = d.children
        let orderListId = 0
        for (let i of listAll) {
          orderList.push({
            id: ++orderListId,
            name: i.children_name
          })
        }
        this.setData({
          orderList,
          orderListId,
          list: listAll[0].list,
          sumcount: listAll[0].list[0].inputQuantity,
        })
      } else {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        });
      }
    })
  },
  //盘点单的拆解
  goodsListToGoods() {
    let data = JSON.stringify(this.data.listAll)
    data = JSON.parse(data)
    for (let i of data) {
      i.list = this.reduceList(i.list)
    }
    return data
    console.log(data, '拆解over')
  },
  reduceList(list) {
    let _this = this;
    let goodsList = list;
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
            goods_status: goodsList[i].goods_status,
            goods_type: goodsList[i].goods_type || 2,
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
    return goodsListPart;
  },
  // 提交单据
  postOrderInfo() {
    let _this = this
    let i = this.data.orderList_index
    this.setListAll(i, i) //设置当前list到listAll
    let d = this.data
    let listAll = _this.goodsListToGoods()
    let listAllNumber = 0
    for (let i of listAll) {
      for (let j of i.list) {
        listAllNumber += j.goods_number
      }
    }
    let data = {
      warehouse_id: d.organizationList_value, //仓库id
      orders_date: d.date, //订单日期
      orders_remark: d.remark, //订单备注
      orders_status: d.orders_status, //订单状态
      children: JSON.stringify(listAll), //子单详情
      lock_version: d.lock_version ? d.lock_version : 0, //锁版本
      // com_id: _this.data.com_id ? _this.data.com_id : 2,


      goods_number: listAllNumber, //商品数量~~~~~~~~~~~~~~~~~
      goods_plnumber: 1, //盈亏数量 先随便写一个000000000000000
      user_id: 1, //用户id 0000000000000
      // goods_status:1,//商品状态000000000000
    }
    //判断仓库id是否存在
    if(data.warehouse_id===0){
      let warehouse_id = d.organizationList[d.organizationList_index].id
      if(warehouse_id!==0){
        data.warehouse_id = warehouse_id
      }else{
        wx.showToast({
          title: '仓库id错误',
          icon: 'error',
          duration: 1500
        });
        return
      }
    }
    _this.data.orders_id ? data.orders_id = _this.data.orders_id : ''
    app._post_form(
      'web/inventory/submit',
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
              _this.setData({
                noCache: true
              })
              wx.removeStorage({
                key: _this.data.chche_name,
              })
            }
          })
        }, 800)

      });
  },
});

//构造页面高度 
const builderPageHeight = (_this, _fun) => {
  console.log('销售退货单构造页面高度')
  let data = wx.getStorageSync('sale_check_order_height');
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
    success(res) {
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
    // data.mainBarHeight = res[4].height;
    data.tabBarHeight = res[5].height;

    wx.setStorageSync('sale_check_order_height', data);
    _fun && _fun(data);
  });
};