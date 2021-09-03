// pages/supplier/edit.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    oid: 0, //会员卡主键id
    usercardcode: '', //会员卡编号
    member_name: '', //会员姓名
    member_phone: '', // 会员手机号
    member_birthday: '', //生日
    member_status: true, //会员卡状态（-1禁用，1启用）
    member_accumulative_integral: 0, //会员累计积分
    member_address: '', //会员地址
    type: '', //储存页面类型
    lock: 0,
    page_name: '编辑',
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      type: options.type,
      lock_version: options.lock_version,
      client_id: options.oid
    })
    if (options.type == 'add') {
      this.setData({
        page_name: '添加',
        type: options.type
      })
    } else {
      //加载详细信息
      this.loadCardInfo(options.oid, options.lock_version);
    }
    let date = new Date();
    let member_birthday = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
    this.setData({
      member_birthday: member_birthday
    })
  },

  //按钮
  switchChange() {
    this.setData({
      member_status: !this.data.member_status
    })
  },
  //加载修改页面数据
  loadCardInfo(id, lock) {
    let _this = this;
    app._get('web/MemberCard/loadCardInfo', {
      oid: id,
      lock_version: lock
    }, function (res) {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let member_status;
      if (res.data.member_status == 1) {
        member_status = true;
      } else {
        member_status = false;
      }
      let data = res.data;
      let member_birthday = _this.getDate(data.member_birthday)
      _this.setData({
        oid: data.oid,
        member_name: data.member_name,
        member_phone: data.member_phone,
        member_status: member_status,
        member_birthday: member_birthday,
        member_accumulative_integral: data.member_accumulative_integral,
        member_address: data.member_address,
        lock_version: data.lock_version
      })
    })
  },
  // 保存提交
  submit() {
    let _this = this;
    //验证邮箱正则
    // let emilStr = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    //验证手机号正则
    let mobilStr = /^1[3456789][0-9]{9}$/;
    if (_this.data.member_name.length <= 0) {
      app.showToast("请输入客户名称");
      return false;
    }
    if (_this.data.member_phone.length <= 0) {
      app.showToast("请输入手机号码");
      return false;
    }
    if (!mobilStr.test(_this.data.member_phone)) {
      app.showToast("请输入正确的手机号码");
      return false;
    }
    let member_status;
    if (_this.data.member_status == false) {
      member_status = -1;
    } else {
      member_status = 1;
    }
    let data = {
      member_name: _this.data.member_name,
      member_phone: _this.data.member_phone,
      member_status: member_status,
      lock_version: _this.data.lock,
      oid: _this.data.oid,
      member_birthday: _this.data.member_birthday,
    }
    if (_this.data.member_accumulative_integral.length > 0) {
      data.member_accumulative_integral = _this.data.member_accumulative_integral
    }
    if (_this.data.member_address.length > 0) {
      data.member_address = _this.data.member_address
    }
    app._post_form("web/MemberCard/addMemberCard",
      data,
      (res) => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          })
        }
        wx.showToast({
          title: res.errmsg,
          icon: 'success',
          duration: 1500
        })
        wx.redirectTo({
          url: '/pages/member_card/index'
        })
      }
    )
  },
  getDate(date) {
    //date是传过来的时间戳，注意需为13位，10位需*1000
    //也可以不传,获取的就是当前时间
    var time = new Date(parseInt(date * 1000));
    var year = time.getFullYear() //年
    var month = ("0" + (time.getMonth() + 1)).slice(-2); //月
    var day = ("0" + time.getDate()).slice(-2); //日
    var mydate = year + "-" + month + "-" + day;
    return mydate
  }
})