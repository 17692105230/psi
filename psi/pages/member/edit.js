// pages/member/edit.js
let app = getApp()
Page({
  data: {
    today: app.util.getNowDate(),
    member_birthday: app.util.getNowDate(),
    member_code: '',
    member_sex: 1,
    member_email: '',
    member_qq: '',
    member_wechat: '',
    member_age: '',
    member_height: '',
    member_address: '',
    member_story: '',
    member_status: 1,
  },
  clearV(e) {
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]: ''
    })
  },

  onLoad(o) {
    let _this = this
    let id = o.id
    if (id) {
      this.setData({
        edit: true,
        id
      })
      //获取详情
      app._get('/web/MemberBase/getDetail', {
        member_id: id
      }, res => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          })
        }
        let d = res.data
        console.log(d, 9999)
        _this.setData({
          member_birthday: d.member_birthday ? d.member_birthday : app.util.getNowDate(),
          member_sex: d.member_sex,
          member_email: d.member_email,
          member_qq: d.member_qq,
          member_wechat: d.member_wechat,
          member_age: d.member_age,
          member_height: d.member_height,
          member_address: d.member_address,
          member_story: d.member_story,
          member_status: d.member_status,
          member_name: d.member_name,
          member_phone: d.member_phone,
          member_idcode: d.member_idcode,
        })


      })


    }


  },

  sexChange(e) {
    this.setData({
      member_sex: e.detail.value
    })
  },
  submit() {
    let d = this.data
    let data = {}
    data.member_name = d.member_name
    data.member_phone = d.member_phone
    data.member_idcode = d.member_idcode
    data.member_birthday = d.member_birthday == d.today ? '' : d.member_birthday
    data.member_sex = d.member_sex
    data.member_email = d.member_email
    data.member_qq = d.member_qq
    data.member_wechat = d.member_wechat
    data.member_age = d.member_age
    data.member_height = d.member_height
    data.member_address = d.member_address
    data.member_story = d.member_story
    data.member_status = d.member_status
    data.user_code = ''
    data.category_code = ''
    data.city_code = ''
    if (this.data.edit) {
      data.member_id = this.data.id
    }
    if (!data.member_name) {
      app.toast("请输入会员名称")
      return
    }
    if (!data.member_phone) {
      app.toast("请输入会员电话")
      return
    }
    //验证手机号正则
    let mobilStr = /^1[3456789][0-9]{9}$/;

    if (!mobilStr.test(data.member_phone)) {
      app.showToast("请输入正确的联系电话");
      return
    }
    if (!data.member_idcode) {
      app.toast("请输入会员身份证号")
      return
    }

    //验证邮箱正则
    let emilStr = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    if (data.member_email && !emilStr.test(data.member_email)) {
      app.showToast("请输入正确的电子邮箱")
      return
    }

    app._post_form('web/MemberBase/addMember', data, res => {
      wx.hideLoading()
        if (res.errcode != 0) {
          wx.showToast({
            duration: 1500,
            title: res.errmsg,
            icon: 'none'
          })
          return
        }
        app.toast('保存成功')
        setTimeout(function () {
          // wx.redirectTo({
          //   url: '/pages/member/index',
          // })
          wx.navigateBack({
            delta: 1,
          })
        }, 800)
    });

  },
})


