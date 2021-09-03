let app = getApp()

Page({
  data: {
    title: ['权限管理', '组织机构', '角色', '用户'],
    titleIndex: 0,
    //导航栏数据
    tabList: [{
        id: 0,
        name: '组织机构',
      },
      {
        id: 1,
        name: '角色',
      },
      {
        id: 2,
        name: '用户',
      },
    ],
    //当前nav索引
    TabCur: 0,
    //是否显示list
    showList: true,
    //组织机构上级
    org_list: [],
    org_pid_index: 0,
    //组织结构类型
    org_type_list: ['内部机构', '外部门店', '仓库'],
    org_type: 0,
    //用户新增多选
    checkData: {
      role: {
        text: []
      },
      org: {
        text: []
      }
    },
    select_index: [],
    user_story: '',
    noAdmin: false, //不是管理员
    user_status: 1, //1 开启 -1关闭

  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  
  onLoad: function (o) {
    if (o.TabCur) {
      this.setData({
        TabCur: o.TabCur
      })
      if (o.TabCur == 0) { //组织机构
        this.getOrgList()
      }
      if (o.TabCur == 1) { //角色
        this.getRoleList()
      }
      if (o.TabCur == 2) { //用户
        this.getUserList()
      }
    } else {
      this.getOrgList() //获取组织机构列表
    }
    let _this = this
    builderPageHeight(this, function (data) {
      _this.setData({
        saleScrollViewHeight: data.windowHeight - data.navBarHeight - data.footHeight - app.globalData.CustomBar
      });
    });
    app.util.getAllList(['node_list']).then(res => {
      this.setData({
        node_list: res.node_list
      })
    })
  },
  //tab切换
  tabSelect(e) {
    let id = e.currentTarget.dataset.id
    this.setData({
      TabCur: id
    })
    let tabCur = id

    //组织机构
    if (tabCur == 0) {
      this.getOrgList()
    }

    //角色
    if (tabCur == 1) {
      this.getRoleList()
    }
    //用户
    if (tabCur == 2) {
      this.getUserList()
    }
  },
  //删除
  del(e) {
    let _this = this
    let tabCur = this.data.TabCur
    //组织机构
    if (tabCur == 0) {
      let org_id = e.currentTarget.dataset.org_id
      _del('web/organization/delete', {
        org_id
      }, _this.getOrgList)
    }
    //角色
    if (tabCur == 1) {
      let role_id = e.currentTarget.dataset.role_id
      _del('web/power/delete_role', {
        role_id
      }, _this.getRoleList)
    }
    //用户
    if (tabCur == 2) {
      let user_id = e.currentTarget.dataset.user_id
      _del('web/user/delete_user', {
        user_id
      }, _this.getUserList)
    }

    function _del(url, data, cb) {
      app._post_form(url, data, res => {
        if (res.errcode != 0) {
          return wx.showToast({
            title: res.errmsg,
            icon: 'error',
            duration: 1500
          })
        }
        app.toast('删除成功')
        setTimeout(function () {
          cb()
        }, 800)
      })
    }

  },
  //编辑
  edit(e) {
    let _this = this

    let tabCur = this.data.TabCur
    //显示编辑页面
    this.setData({
      titleIndex: tabCur + 1,
      showList: false,
      BackPage: `/pages/authority_management/index?TabCur=${this.data.TabCur}`,
      isEdit: true,
    })

    //组织机构编辑
    if (tabCur == 0) {
      let org_id = e.currentTarget.dataset.org_id
      this.setData({
        org_id,
      })
      this.getOrgDetail(org_id)
    }
    //角色编辑
    if (tabCur == 1) {
      let role_id = e.currentTarget.dataset.role_id
      this.setData({
        role_id,
      })
      this.getRoleDetail(role_id)
    }
    //用户编辑
    if (tabCur == 2) {
      let user_id = e.currentTarget.dataset.user_id
      this.setData({
        user_id,
      })
      //回调函数处理异步   获取角色列表   组织机构列表    用户详情
      _this.getRoleList(_this.getOrgList,_this.getUserDetail)
    }

  },
  //新增
  add() {
    let tabCur = this.data.TabCur
    this.setData({
      titleIndex: tabCur + 1,
      showList: false,
      BackPage: `/pages/authority_management/index?TabCur=${this.data.TabCur}`
    })
    if (tabCur == 0) { //获取组织机构列表
      this.getOrgList()
    }
    if (tabCur == 2) { //获取角色和组织机构列表
      this.getRoleList() //roleList
      this.getOrgList() //orgList
      this.setData({
        noAdmin: !this.data.noAdmin
      })
    }

  },

  //权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-权限配置-

  //展开一级权限
  tiggerr_yiji(e) {
    let id = e.currentTarget.dataset.id
    let index = e.currentTarget.dataset.index
    let checked = e.currentTarget.dataset.checked
    let children = this.data.node_list[index].children
    //如果有子级则展开 如果有checked说明是点击图标时的调用事件 则不展开
    if (children && !checked) {
      this.data.node_list[index].unfold = !this.data.node_list[index].unfold
    } else { 
      //如果没有子级或点击的是选择图标,则选中
      //切换当前项的选择状态   
      if(this.data.disabled_role){
        //如果是不可编辑的角色 不修改状态
        return
      }  
      this.data.node_list[index].checked = !this.data.node_list[index].checked
      let checked = this.data.node_list[index].checked
      //切换子级的状态
      if (children) {
        for (let i of children) {
          i.checked = checked
          if (i.children) {
            for (let j of i.children) {
              j.checked = checked
            }
          }
        }
      }
    }
    let node_list = this.data.node_list
    this.setData({
      node_list
    })
  },
  //展开二级权限
  tiggerr_erji(e) {
    let id = e.currentTarget.dataset.id
    let index = e.currentTarget.dataset.index
    let index2 = e.currentTarget.dataset.index2
    let checked = e.currentTarget.dataset.checked
    let children = this.data.node_list[index].children[index2].children
    //如果有子级则展开 
    //如果有checked说明是点击图标时的调用事件 则不展开
    if (children && !checked) {
      this.data.node_list[index].children[index2].unfold = !this.data.node_list[index].children[index2].unfold
    } else {
      if(this.data.disabled_role){
        //如果是不可编辑的角色 不修改状态
        return
      } 
      //切换当前项的选择状态      
      this.data.node_list[index].children[index2].checked = !this.data.node_list[index].children[index2].checked
      let nowChecked = this.data.node_list[index].children[index2].checked

      if (children) { //如果有子级,切换子级的状态
        for (let i of children) {
          i.checked = nowChecked
        }
      }
      // 遍历当前级别,确定父级的状态,有选中,则父级也选中
      let nowNode = this.data.node_list[index].children
      let havChecked = false
      for (let i of nowNode) {
        if (i.checked) {
          havChecked = true
          break
        }
      }
      this.data.node_list[index].checked = havChecked //父级状态
      if (!children) {
        this.data.node_list[index].children[0].checked = havChecked //没有子级时,列表的状态
      }
    }
    let node_list = this.data.node_list
    this.setData({
      node_list
    })
  },
  //点击三级
  tiggerr_sanji(e) {
    if(this.data.disabled_role){
      //如果是不可编辑的角色 不修改状态
      return
    } 
    let id = e.currentTarget.dataset.id
    let index = e.currentTarget.dataset.index
    let index2 = e.currentTarget.dataset.index2
    let index3 = e.currentTarget.dataset.index3
    console.log(id, index, index2, index3, this.data.node_list[index].children[index2].children[index3])
    this.data.node_list[index].children[index2].children[index3].checked = !this.data.node_list[index].children[index2].children[index3].checked

    let checked = false
    let data = this.data.node_list[index].children[index2].children
    for (let i of data) {
      if (i.checked) {
        checked = true
        break
      }
    }
    //如果子级有选中,则勾选列表和父级,
    this.data.node_list[index].children[index2].children[0].checked = checked
    this.data.node_list[index].children[index2].checked = checked
    this.data.node_list[index].checked = checked



    let node_list = this.data.node_list
    this.setData({
      node_list
    })

  },
  //新增角色/编辑
  submit_role() {
    let isEdit = this.data.isEdit
    let role_id = this.data.role_id
    let _this = this
    let role_name = this.data.role_name
    let role_remark = this.data.role_remark
    let wx_power_node = []
    let nodes = this.data.node_list
    for (var i of nodes) {
      if (i.checked) {
        wx_power_node.push(i.id)
      }
      console.log(111)
      if (i.children) {
        console.log(222)

        for (var j of i.children) {
          if (j.checked) {
            wx_power_node.push(j.id)
          }
          if (j.children) {
            console.log(333)

            for (var k of j.children) {
              if (k.checked) {
                wx_power_node.push(k.id)
              }
            }
          }
        }
      }
    }
    console.log(!role_name)

    if (!role_name) {
      app.toast('请输入角色名称')
      return
    }

    let url = isEdit ? 'web/power/edit_role' : 'web/power/add_role'
    let data = {
      role_name,
      role_remark,
      wx_power_node: JSON.stringify(wx_power_node)
    }
    if (isEdit) {
      data.role_id = role_id
    }

    app._post_form(url, data, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      app.toast('保存成功')
      setTimeout(function () {
        wx.redirectTo({
          url: _this.data.BackPage,
        })
      }, 800)

    })



  },
  //获取角色列表
  getRoleList(cb,cb2) {
    let _this = this
    app._get('web/power/role_list', {}, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        list: res.data,
        roleList: res.data,
        loadOk: true
      })
      if(cb){
        cb(cb2)
      }
    })
  },
  //获取角色详情
  getRoleDetail(role_id) {
    app._get('web/power/role_detail', {
      role_id
    }, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let data = res.data

      this.setData({
        role_name: data.role_name,
        role_remark: data.role_remark,
        disabled_role:data.disabled
      })
      let nodes = Object.keys(data.wx_power_node).map(key => {
        return data.wx_power_node[key]
      })
      let node_list = this.data.node_list
      if (nodes.length == 1 && nodes[0] == -1) { //全部权限
        for (var i of node_list) {
          i.checked = true
          if (i.children) {
            for (var j of i.children) {
              j.checked = true
              if (j.children) {
                for (var k of j.children) {
                  k.checked = true
                }
              }
            }
          }
        }
      } else if (nodes.length != 0) {
        //遍历权限,给有权限的数据勾上checked
        for (let _i of nodes) {
          for (var i of node_list) {
            if (i.id == _i) {
              i.checked = true
            }
            if (i.children) {
              for (var j of i.children) {
                if (j.id == _i) {
                  j.checked = true
                }
                if (j.children) {
                  for (var k of j.children) {
                    if (k.id == _i) {
                      k.checked = true
                    }
                  }
                }
              }
            }
          }
        }
      }
      console.log(node_list, 999988)

      //设置权限数据
      this.setData({
        node_list,
      })
    })
  },

  //权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配置end-权限配
  //组织机构管理

  //获取组织机构列表
  getOrgList(cb) {
    let _this = this
    app._get('web/organization/list', {}, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let org_list = JSON.parse(JSON.stringify(res.data))
      if (org_list.length > 0) {
        org_list.unshift({
          org_id: '',
          org_name: '无父级'
        })
      }
      _this.setData({
        org_list,
        list: res.data,
        orgList: res.data,
        loadOk: true
      })
      if(cb){
        cb()
      }
    })
  },
  //获取详情
  getOrgDetail(org_id) {
    let _this = this
    app._get("web/organization/detail", {
      org_id
    }, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let pid = res.data.org.org_pid //父id
      let org_list = res.data.select //可选的父id
      org_list.unshift({
        org_id: '',
        org_name: '无父级'
      })
      let org_pid_index = org_list.findIndex(i => i.org_id == pid)
      console.log(org_list, pid, org_pid_index, org_list[0].org_id, 8418554)

      _this.setData({
        org_list,
        org_pid_index
      })
      _this.setData({
        org_name: res.data.org.org_name,
        org_head: res.data.org.org_head,
        org_phone: res.data.org.org_phone,
        org_type: res.data.org.org_type,
      })
    })
  },
  pidChange(e) {
    let index = e.detal.value
  },
  typeChange(e) {
    let index = e.detal.value

  },
  submit_org() {
    let _this = this
    let org_name = this.data.org_name
    let org_head = this.data.org_head
    let org_phone = this.data.org_phone
    let org_pid = ''
    if (this.data.org_list.length > 0) {
      org_pid = this.data.org_list[this.data.org_pid_index].org_id
    }
    let org_type = this.data.org_type
    if (!org_name) {
      app.toast('请输入组织机构名称')
      return
    }
    if (!org_head) {
      app.toast('请输入组织机构负责人')
      return
    }
    if (!org_phone) {
      app.toast('请输入联系电话')
      return
    }
    let mobilStr = /^1[3456789][0-9]{9}$/;
    if (!mobilStr.test(org_phone)) {
      app.toast("请输入正确的联系电话");
      return false;
    }
    let isEdit = this.data.isEdit
    let url = isEdit ? 'web/organization/edit' : 'web/organization/add'
    let data = {
      org_name,
      org_head,
      org_phone,
      org_pid,
      org_type
    }
    if (isEdit) {
      data.org_id = this.data.org_id
    }
    app._post_form(url, data, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      app.toast('保存成功')
      setTimeout(function () {
        wx.redirectTo({
          url: _this.data.BackPage,
        })
      }, 800)
    })

  },

  //组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-组织机构管理end-
  //用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-用户管理-
  //选择角色
  selectRole() {
    this.setData({
      singleshow: true,
      select_list: this.data.roleList,
      select_name: '选择角色',
      select_type: 'role',
      select_index: this.data.selectRoleData
    })
  },
  //选择组织机构
  selectorOrg(e) {
    console.log(1111111,3333333,e)
    this.setData({
      singleshow: true,
      select_list: this.data.orgList,
      select_name: '选择组织机构',
      select_type: 'org',
      select_index: this.data.selectOrgData
    })
  },
  //隐藏多选窗口
  hidePanel() {
    // console.log(this.data.select_type,this.data.select_index,this.data.select_list,this.data.select_type == 'role',this.data.select_type == 'org',9998888666)
    this.setData({
      singleshow: false,
    })
    let type = this.data.select_type
    if (type == 'role') {
      this.setData({
        selectRoleData: this.data.select_index
      })
    } 
    if(type == 'org') {
      this.setData({
        selectOrgData: this.data.select_index
      })
    }
  },
  //选择事件
  selectChange(e) {
    let select_index = e.detail.value
    this.setData({
      select_index
    })
    let text = []
    let type = this.data.select_type
    let id = `${type}_id`
    for (let i of this.data.select_list) {
      i.checked = false
      for (let j of select_index) {
        if (j == i[id]) {
          text.push(i[`${type}_name`])
          i.checked = true
        }
      }
    }
    let setText = `checkData.${type}.text`
    this.setData({
      [setText]: text
    })
  },
  //获取用户列表
  getUserList() {
    let _this = this
    app._get('web/user/user_list', {}, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      _this.setData({
        org_list: res.data,
        list: res.data,
        loadOk: true
      })
    })
  },
  //获取用户详情
  getUserDetail() {
    let user_id = this.data.user_id
    app._get('web/user/user_detail', {
      user_id
    }, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      //是否可修改角色 组织机构 用户状态
        this.setData({
          noAdmin:res.data.is_modify
        })
      let data = res.data
      let selectRoleData = Object.keys(data.user_role_ids).map(i=> data.user_role_ids[i]),
        selectOrgData = Object.keys(data.org_id).map(i=> data.org_id[i])
      this.setData({
        user_name: data.user_name,
        password: '',
        user_phone: data.user_phone,
        idcode: data.user_idcode,
        user_story: data.user_story,
        user_status: data.user_status == 1 ? true : false,
        selectRoleData,
        selectOrgData,
      })
      //设置角色和组织机构显示
      let roleText = []
      let orgText = []
      let roleList = this.data.roleList
      let orgList = this.data.orgList
      for (let i of roleList) {
        console.log(roleList,'遍历1')
        i.checked = false
        for (let j of selectRoleData) {
        console.log(selectRoleData,'遍历2')
          if (j == (i.role_id)) {
            roleText.push(i.role_name)
            i.checked = true
          }
        }
      }
      for (let i of orgList) {
        i.checked = false
        for (let j of selectOrgData) {
          if (j == (i.org_id)) {
            orgText.push(i.org_name)
            i.checked = true
          }
        }
      }
      let setRoleText = `checkData.role.text`
      let setOrgText = `checkData.org.text`
      this.setData({
        [setRoleText]:roleText,
        [setOrgText]:orgText,

      })
    })
  },
  //新增&编辑
  submit_user() {
    let _this = this
    let user_name = this.data.user_name
    let password = this.data.password
    let user_phone = this.data.user_phone
    let idcode = this.data.idcode
    let role_ids = this.data.selectRoleData
    let org_id = this.data.selectOrgData
    let user_status = this.data.user_status
    user_status = user_status ? 1 : -1
    let user_story = this.data.user_story
    if (!user_name) {
      app.toast('请输入用户名称')
      return
    }
    if (user_name.length < 3) {
      app.toast('用户名至少需要3位')
      return
    }
    if (!this.data.isEdit) {
      if (!password) {
        app.toast('请输入密码')
        return
      }
      if (password.length < 6) {
        app.toast('密码至少需要6位')
        return
      }
    }
    if (!user_phone) {
      app.toast('请输入联系方式')
      return
    }
    let mobilStr = /^1[3456789][0-9]{9}$/;
    if (!mobilStr.test(user_phone)) {
      app.toast("请输入正确的联系电话");
      return false;
    }
    if (!idcode) {
      app.toast('请输入身份证号')
      return
    }

    if(this.data.noAdmin){
      if (!role_ids || role_ids.length == 0) {
        app.toast('请选择角色')
        return
      }
      if (!org_id || org_id.length == 0) {
        app.toast('请选择组织机构')
        return
      }
    }
    let isEdit = this.data.isEdit
    let url = isEdit ? 'web/user/edit_user' : 'web/user/add_user'
    let data = {
      user_name,
      password,
      user_phone,
      idcode,
      role_ids: JSON.stringify(role_ids),
      org_id: JSON.stringify(org_id),
      user_story,
      user_status
    }
    if (isEdit) {
      data.user_id = this.data.user_id
    }
    app._post_form(url, data, res => {
      if (res.errcode != 0) {
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      app.toast('保存成功')
      setTimeout(function () {
        wx.redirectTo({
          url: _this.data.BackPage,
        })
      }, 800)
    })



  },
  //用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-用户管理end-
  //下拉刷新
  onRefresh() {
    this.setData({
      last_page: 1,
      now_page: 1,
      nomore: false,
      loadOk: false,
    })
    // this.getPurchaseOrdersList(true)
  },
  //触底刷新
  tolower() {
    if (this.data.now_page <= this.data.last_page) {
      // this.getPurchaseOrdersList()
    } else {
      this.setData({
        nomore: true,
      })
    }
  },


})


/**
 * 构造页面高度
 */
const builderPageHeight = (_this, _fun) => {
  let data = wx.getStorageSync('authority_management_height');
  if (data) {
    _fun && _fun(data);
    return;
  }
  data = {
    windowHeight: 0,
    navBarHeight: 0,
  };

  // 先取出页面高度 windowHeight
  wx.getSystemInfo({
    success: function (res) {
      data.windowHeight = res.windowHeight;
    }
  });
  let query = wx.createSelectorQuery().in(_this);
  query.select('#navBar').boundingClientRect();
  query.select('#foot').boundingClientRect();

  query.exec((res) => {
    data.navBarHeight = res[0].height;
    data.footHeight = res[1].height;
    wx.setStorageSync('authority_management_height', data);
    _fun && _fun(data);
  });
};