// pages/count_ account/index.js
const app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    formData: {
      settlement_name: '',
      settlement_status: '',
      settlement_code: '',
      account_holder: '',
      settlement_money: 0.00,
      settlement_remark: ''
    },

    hrnum: {
      show: false,
      value: '',
      numberArr: [],
      pwdArr: ["", "", "", "", "", ""],
      temp: ["", "", "", "", "", ""]
    },
    list: [], //列表数据
    delDataId: 0, //用于编辑和删除时的主键
    lock_version: 0, //版本锁
    //提示框相关
    tipShow: false, //提示框显示
    tips: "", //提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName: 'tipItemClick', //提示框点击确定调用的方法名
  },

  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    console.log(e, 9090980)
    this.setData({
      [clearkey]: ''
    })
  },
  onLoad: function (options) {
    let _this = this;
    _this.getData();
    let qxList = app.getSomeNodeQx(['结算账户新增', '结算账户编辑', '结算账户删除'])
    this.setData({
      qxList
    })
  },
  onPullDownRefresh: function () {
    let _this = this;
    _this.getData();
  },
  //请求结算账户列表
  getData: function () {
    let _this = this;
    app._get("web/settlement/loadSettlement", {}, (res) => {
      _this.setData({
        list: res.data
      });
      wx.stopPullDownRefresh();
    });
  },
  //根据id获取信息
  getDataById: function (e) {
    let _this = this;
    _this.setData({
      delDataId: e.currentTarget.dataset.id,
      lock_version: e.currentTarget.dataset.lockversion,
    });

    app._get("web/settlement/loadSettlementInfo", {
        settlement_id: _this.data.delDataId,
        lock_version: _this.data.lock_version
      },
      (res) => {
        if (res.errcode == 0) {
          _this.setData({
            formData: res.data
          });
          _this.showAdd();
        } else {
          app.showToast(res.errmsg);
        }
      });
  },
  showAdd: function () {
    this.setData({
      isSizeShow: true
    });
  },
  onNumberModalShow(e) {
    this.setData({
      'hrnum.show': true,
      'hrnum.value': ''
    });
  },
  onNumberConfirm(e) {
    console.log(e.detail);
    this.setData({
      'hrnum.value': e.detail,
      'formData.settlement_money': e.detail
    });
  },
  //状态修改
  statusChange: function (e) {
    console.log(e.detail.value, 8855)
    this.setData({
      "formData.settlement_status": e.detail.value ? 1 : 0
    });
  },
  //取消关闭窗口
  onHideCancel: function () {
    this.setData({
      isSizeShow: false,
      delDataId: 0,
    });
    setTimeout(_ => {
      let formData = {
        settlement_name: '',
        settlement_status: 0,
        settlement_code: '',
        account_holder: '',
        settlement_money: 0.00,
        settlement_remark: ''
      };
      this.setData({
        formData: formData
      })
    },300)

  },
  //提交窗口数据
  onHideAdd: function () {
    //进行数据判断
    let _this = this;
    let formData = _this.data.formData;
    console.log(formData);
    if (formData.settlement_code.length <= 0) {
      app.showToast("账号不能为空");
      return false;
    }
    if (formData.settlement_name.length <= 0) {
      app.showToast("账户名称不能为空");
      return false;
    }
    if (formData.account_holder.length <= 0) {
      app.showToast("开户姓名不能为空");
      return false;
    }

    let url = "web/settlement/saveSettlement";
    if (_this.data.delDataId > 0) {
      url = "web/settlement/editSettlement";
      _this.setData({
        ["formData.settlement_id"]: _this.data.delDataId
      });
    }

    app._post_form(url, _this.data.formData, (res) => {
      console.log(res);
      _this.getData();
      _this.onHideCancel();

      app.showToastCollback(res.errmsg, function () {
        console.log("回调2");
      });
    });
  },
  bindinput: function (e) {
    let _this = this;
    let name = e.currentTarget.dataset.name;
    let value = e.detail.value;
    let attrName = "formData." + name;
    _this.setData({
      [attrName]: value
    });
  },
  /**
   * zll 显示提示框
   * @param {*} tip 提示内容
   * @param {*} funName 点击非取消按钮,回调的方法名
   * @param {*} color 提示文字颜色 默认灰色
   * @param {*} iscancel 是否显示取消按钮
   * @param {*} btnlist 按钮对象 列表
   */
  showTip: function (tip, funName, color = '#9a9a9a', iscancel = true, btnlist = [{
    color: '#e53a37',
    text: '确定'
  }]) {
    let _this = this;
    _this.setData({
      tipShow: true,
      tips: tip,
      tipItemList: btnlist,
      tipItemClickName: funName,
      tipColor: color,
      tipIsCancel: iscancel
    });
  },
  /**
   * zll 隐藏提示框
   */
  tipClose: function () {
    this.setData({
      tipShow: false,
      delDataId: 0
    });
  },
  //删除提示
  delDataTip: function (e) {
    let _this = this;
    _this.setData({
      delDataId: e.currentTarget.dataset.id,
      lock_version: e.currentTarget.dataset.lockversion,
    });
    _this.showTip("确认删除吗?", "delData");
  },
  delData: function () {
    let _this = this;
    app._post_form("web/settlement/delSettlement", {
        settlement_id: _this.data.delDataId,
        lock_version: _this.data.lock_version
      },
      (res) => {
        _this.tipClose();
        if (res.errcode == 0) {
          app.showToastCollback(res.errmsg, () => {
            _this.getData();
          });
        }
      }
    );
  }
})