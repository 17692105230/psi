// pages/example5/index.js
import util from '../../utils/util.js';
let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    settlementModal: false,
    date: util.getNowDate(),
    waiter: {
      index: 0,
      list: ['王二小', '郑小伟', '马韦东']
    },
    pay: {
      index: 0,
      list: []
    },
    real_income: "", //实收 zll
    remove_zero: "", //抹零 zll
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let _this = this;
    //获取结算账户
    app.util.getAllList(['settlement']).then(res => {
      _this.setData({
        ['pay.list']: res.settlement,
        payId: res.settlement[0].id
      })
    })
    // 监听 acceptDataFromOpenerPage 事件，获取上一页面通过eventChannel传送到当前页面的数据
    this.getOpenerEventChannel().on('acceptDataFromOpenerPage', function (data) {
      console.log(data.data.goodsCount, 12345)
      //zll 接收上页传来的信息
      _this.setData({
        real_income: data.data.goodsPrice,
        should_income: data.data.goodsPrice,
        goodsCount: data.data.goodsCount,
        member_id: data.data.member_id,
        member_name: data.data.member_name,
        org_id: data.data.org_id,
        goodsList: data.data.goodsList,
      });
    });
  },
  //zll 显示金额键盘,target 针对哪个变量进行修改
  showHrNumber(e) {

    let _this = this;
    _this.setData({
      'hrnum.content': e.currentTarget.dataset.title,
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
    });
  },

  onShowSettlementModal(e) {
    this.setData({
      settlementModal: true
    });
  },



  onDateChange(e) {
    this.setData({
      date: e.detail.value
    })
  },
  onWaiterChange(e) {
    this.setData({
      'waiter.index': e.detail.value
    })
  },
  onPayChange(e) {
    console.log(e, 999)
    this.setData({
      'pay.index': e.detail.value,
      payId: this.data.pay.list[e.detail.value].id //结算账户id
    })
  },

  // 结账确认
  onConfirm(e) {
    let _this = this
    if (this.data.real_income <= 0) {
      wx.showToast({
        title: '实收金额错误',
        icon: 'none'
      })
      return
    }
    if (!this.data.payId) {
      wx.showToast({
        title: '请选择结算账户',
        icon: 'none'
      })
      return
    }
    //提交数据
    app._post_form('web/cashier/submit',{
      client_id:_this.data.member_id,//客户
      warehouse_id:_this.data.org_id,//仓库
      settlement_id:_this.data.payId,//结算账户
      goods_number:_this.data.goodsCount,//数量
      orders_pmoney:_this.data.should_income,//应收
      orders_rmoney:_this.data.real_income,//实收
      erase_money:_this.data.remove_zero,//抹零
      details:JSON.stringify(_this.data.goodsList),//商品详情
    },res=>{
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let r = res.data.member_info.accounts[0]
      _this.setData({
        account_money:r.account_money,
        now_points:res.data.now_points,
        account_points:r.account_points,
      })
      payOk()
    })



    function payOk() {
      let eventChannel = _this.getOpenerEventChannel();
      if (Object.keys(eventChannel).length != 0) {
        eventChannel.emit('acceptDataFromOpenedPage', {
          data: 'acceptDataFromOpenedPage'
        });
        eventChannel.emit('someEvent', {
          data: 'someEvent'
        });
        //zll 支付完成,调用收银台页面的paysuccess
        eventChannel.emit('paySuccess', {
          data: 'pay finish'
        });
      }
      _this.onShowSettlementModal(e);
    }

  },

  //隐藏交易成功弹窗并返回上一页
  onHideSettlementModal(e) {
    this.setData({
      settlementModal: false
    }, function () {
      wx.navigateBack({
        delta: 1
      })
    });
  },

  onGoBack(){
    wx.navigateBack({
      delta: 1
    })
  }
})