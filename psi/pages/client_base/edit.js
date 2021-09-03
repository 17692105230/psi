// pages/supplier/edit.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: { 
    client_id: 0, //客户id
    client_code: '', //客户编号
    client_name: '', //客户名称
    client_discount: 100, //默认折扣
    client_phone: '', // 客户电话
    account_money: 0.00, //期初余额
    client_email: '', //电子邮箱
    client_address: '', //客户地址
    type: '', //储存页面类型
    lock_version: 0,
    client_story: '', //客户描述
    client_status: true, //客户状态（-1禁用，1启用）
    page_name: '编辑',
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad: function (options) {

    if (options.type == 'add') {
      this.setData({
        page_name: '添加',
        type: options.type
      })
    } else {
      this.loadClientInfo(options.client_id, options.lock_version);
    }
  },

  //按钮
  switchChange() {
    this.setData({
      client_status: !this.data.client_status
    })
  },
  //加载修改页面数据
  loadClientInfo(id, lock) {
    let _this = this;
    app._get('web/client/clientDetail', {
      client_id: id,
      lock_version: lock
    }, function (res) {
      if (res.errcode == 0) {
        let client_status;
        if (res.data.client_status == 1) {
          client_status = true;
        } else {
          client_status = false;
        }
        _this.setData({
          client_name: res.data.client_name,
          client_code: res.data.client_code,
          client_discount: res.data.client_discount,
          account_money: res.data.account.account_money,
          client_status: client_status,
          client_phone: res.data.client_phone,
          client_email: res.data.client_email,
          client_address: res.data.client_address,
          client_story: res.data.client_story,
          lock_version: res.data.lock_version,
          client_id: res.data.client_id
        })
      }
    })
  },
  // 保存提交
  submit() {
    console.log(this.data.client_id)
    //验证邮箱正则
    let emilStr = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    //验证手机号正则
    let mobilStr = /^1[3456789][0-9]{9}$/;
    if (this.data.client_name.length <= 0) {
      app.showToast("请输入客户名称");
      return false;
    }
    if (this.data.client_code.length <= 0) {
      app.showToast("请输入客户编号");
      return false;
    }
    if (this.data.client_phone.length <= 0) {
      app.showToast("请输入联系电话");
      return false;
    }
    if (!mobilStr.test(this.data.client_phone)) {
      app.showToast("请输入正确的联系电话");
      return false;
    }
    if (this.data.client_email.length <= 0 || !emilStr.test(this.data.client_email)) {
      app.showToast("请输入正确的电子邮箱")
      return false;
    }
    if (this.data.client_address.length <= 0) {
      app.showToast("请输入客户地址");
      return false;
    }
    if (this.data.client_story.length <= 0) {
      app.showToast("请输入客户描述");
      return false;
    }
    let client_status;
    if (this.data.client_status == false) {
      client_status = -1;
    } else {
      client_status = 1;
    }
    let data = {
      client_name: this.data.client_name,
      client_code: this.data.client_code,
      client_phone: this.data.client_phone,
      client_status: client_status,
      lock_version: this.data.lock_version,
      client_discount: this.data.client_discount,
      account_money: this.data.account_money,
      client_id: this.data.client_id
    }
    if (this.data.client_address.length > 0) {
      data.client_address = this.data.client_address
    }
    if (this.data.client_email.length > 0) {
      data.client_email = this.data.client_email
    }
    if (this.data.client_story.length > 0) {
      data.client_story = this.data.client_story
    }
    app._post_form("web/client/addClient",
      data,
      (res) => {
        if (res.errcode != 0) {
          return wx.showToast({
            title:res.errmsg,
            icon: 'error',
            duration: 1500
          })
        }
        wx.showToast({
          title: '保存成功',
          icon: 'success',
          duration: 1500
        })
        setTimeout(function(){
          wx.navigateBack({
            delta: 1
           })
        },800)         
      }
    )
  }
})