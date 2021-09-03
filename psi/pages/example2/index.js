const app = getApp();
Page({
  data: {
    CustomBar: app.globalData.CustomBar,
    goods_name: '',
    goods_code: '',
    goods_barcode: '',
    goods_pprice: '',
    goods_rprice: '',
    goods_srprice:'',
    goods_wprice:'',
    integral: 0,
    goods_status: 1,
    headerName: '编辑',
    //分类,颜色,尺码列表组合 
    lists: {
      'type': {
        title: '选择分类',
        list: []
      },
      'color': {
        title: '选择颜色',
        list: []
      },
      'size': {
        title: '选择尺码',
        list: []
      }
    },
    //商品选中的数据
    checkData: {
      'type': {
        ids: [],
        text: ''
      },
      'color': {
        ids: [],
        text: ''
      },
      'size': {
        ids: [],
        text: ''
      }
    },
    //当前展现的类型 type,color,size
    nowshow: 'type',
    //渲染数据
    listdata: {
      title: '',
      list: []
    },
    //是否显示单选
    singleshow: false,
    affirmList: [], //储存商品弹窗选中的按钮索引
    imgUrl: [], //储存新添加商品图片本地地址
    page_type: '',
    goods_id: '',
    lock_version: '',
    editImgList: [], //编辑商品时回传组件的图片地址
    edit_ord_img: [], //编辑商品时提交时剩余的原图
    del_images: [], //编辑商品时提交时用户删除的原图
    addModalShow: false, //添加弹窗显示状态
    addGoods: { //添加新增商品信息
      all_name: '',
      name_text: '',
      name_lenth: '',
      code_text: '',
      code_lenth: ''
    },
    addGoodsName: '', //添加新增商品信息名称
    addGoodsCode: '', //添加新增商品信息编号
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad: function (options) {
    wx.showLoading({
      title: '加载中',
    })
    this.getGoodsList();
    this.setData({
      page_type: options.type,
      goods_id: options.goods_id
    })
    if (options.type == 'save') {
      this.setData({
        headerName: '添加',
      })
      wx.hideLoading()
    } else {
      this.getGoodsDetail(options.goods_id);
    }
  },
  // 添加商品图片
  chooseImg(e) {
    let img = this.data.imgUrl;
    img.push(e.detail);
    this.setData({
      ['imgUrl']: img
    })
  },
  // 删除商品图片
  deleteImg(e) {
    console.log(e.detail)
    let img = [];
    for (let i = 0; i < this.data.imgUrl.length; i++) {
      if (this.data.imgUrl[i].id != e.detail) {
        img.push(this.data.imgUrl[i]);
      }
    }
    this.setData({
      ['imgUrl']: img
    })
    console.log(this.data.edit_ord_img)

    // 删除编辑商品的原图片
    let edit_ord_img = [];
    let del_images = this.data.del_images;
    for (let i = 0; i < this.data.edit_ord_img.length; i++) {
      if (this.data.edit_ord_img[i].assist_sort != e.detail) {
        edit_ord_img.push(this.data.edit_ord_img[i]);
      } else {
        del_images.push(this.data.edit_ord_img[i]);
      }
    }
    this.setData({
      ['edit_ord_img']: edit_ord_img,
      ['del_images']: del_images
    })
    console.log(this.data.edit_ord_img)

  },
  //提交数据请求
  submitData(images) {
    let assist_sort = [];
    let assist_url = [];
    for (let i = 0; i < this.data.imgUrl.length; i++) {
      assist_sort.push(this.data.imgUrl[i].id);
      assist_url.push(this.data.imgUrl[i].path);
    }
    let goods_status = '';
    if (this.data.goods_status == true) {
      goods_status = 1;
    } else {
      goods_status = 0;
    }
    let data = {
      category_id: this.data.checkData.type.ids,
      goods_name: this.data.goods_name,
      goods_code: this.data.goods_code,
      goods_barcode: this.data.goods_barcode,
      goods_pprice: this.data.goods_pprice,
      goods_wprice: this.data.goods_wprice,
      goods_rprice: this.data.goods_rprice,
      goods_srprice: this.data.goods_srprice,
      integral: this.data.integral,
      color_id: this.data.checkData.color.ids,
      size_id: this.data.checkData.size.ids,
      goods_status: goods_status,
      images: images,
      del_images: JSON.stringify(this.data.del_images),
      goods_id: this.data.goods_id,
      lock_version: this.data.lock_version
    }
    if (this.data.page_type == 'save') {
      post('web/goods/saveGoods');
    } else {
      post('web/goods/updateGoodsDetails');
    }

    function post(url) {
      app._post_form(url, data, function (res) {
        console.log(res)
        if (res.errcode == 0) {
          app.showSuccess('保存成功', function () {
            wx.navigateBack({
              delta: 1
            })
          })
        }
      })
    }
  },
  //初始化商品数据
  getGoodsList() {

    let _this = this;
    getGoods('web/category/loadCategory', 'type', 'category_id', 'category_name');
    getGoods('web/size/loadSizeList', 'size', 'size_id', 'size_name');
    getGoods('web/color/loadColorList', 'color', 'color_id', 'color_name');
    console.log(_this.data.lists, 669)

    function getGoods(url, type, id, name) {
      app._get(url, '', function (res) {
        for (let i = 0; i < res.data.length; i++) {
          _this.setData({
            ['lists.' + type + '.list[' + i + '].value']: res.data[i][id],
            ['lists.' + type + '.list[' + i + '].name']: res.data[i][name],
          })
        }
      }, '', '', '', true)
    }

  },

  //提交
  submit() {
    if (this.verify() == false) {
      return false;
    }
    let _this = this;
    let imgdata = [];
    //无图片时提交数据请求
    if (_this.data.imgUrl.length <= 0) {
      _this.submitData(imgdata);
    } else {
      wx.showLoading({
        title: "提交中"
      })
      //上传图片到服务器后调用提交数据请求
      for (let i = 0; i < _this.data.imgUrl.length; i++) {
        wx.uploadFile({
          url: app.api_root + 'web/upload/single_upload',
          filePath: _this.data.imgUrl[i].path,
          name: 'file',
          formData: {
            sort: _this.data.imgUrl[i].id,
            filePath: _this.data.imgUrl[i].path,
            file_type: "image",
            token:wx.getStorageSync('token')
          },
          success(res) {
            let data = JSON.parse(res.data);
            console.log(data)
            if (data.errcode == 0) {
              imgdata.push(data.data);
              if (imgdata.length == _this.data.imgUrl.length) {
                _this.submitData(JSON.stringify(imgdata));
              }
              wx.hideLoading()
            } else {
              app.showError(data.errmsg);
              wx.hideLoading()

            }

          }
        })
      }

    }

  },

  // 提交验证
  verify() {
    function showToast(img) {
      wx.showToast({
        title: img,
        icon: 'none',
        duration: 2000
      })
    }
    if (this.data.checkData.type.text.length <= 0) {
      showToast('请选择商品分类');
      return false;
    }
    if (this.data.goods_name.length <= 0) {
      showToast('请输入商品名称');
      return false;
    }
    if (this.data.goods_code.length <= 0) {
      showToast('请输入商品货号');
      return false;
    }
    if (this.data.goods_pprice <= 0) {
      showToast('请输入正确进货价');
      return false;
    }
    
    if (this.data.goods_rprice <= 0) {
      showToast('请输入正确销售价');
      return false;
    }
    if (this.data.goods_srprice <= 0) {
      showToast('请输入正确吊牌价');
      return false;
    }
    if (this.data.goods_wprice <= 0) {
      showToast('请输入正确批发价');
      return false;
    }
    if (this.data.checkData.color.text.length <= 0) {
      showToast('请选择商品颜色');
      return false;
    }
    if (this.data.checkData.size.text.length <= 0) {
      showToast('请选择商品尺码');
      return false;
    }
  },
  // 上架按钮
  goodsStatusChange(e) {
    this.setData({
      goods_status: !this.data.goods_status
    })
  },

  /**
   * 单选隐藏
   */
  onSingleHidePanel(e) {
    this.setData({
      singleshow: false
    });
  },
  onSingleShowPanel(e) {
    let _this = this;
    //获取目标数据数组
    let listdatalist = _this.data.lists[e.currentTarget.dataset.type].list;
    let affirmList = [];
    //从目标数据中获取原有选中的索引放入affirm
    listdatalist.forEach((element, index) => {
      if (element.checked) {
        affirmList.push(index);
      }
    });
    _this.setData({
      listdata: _this.data.lists[e.currentTarget.dataset.type],
      affirmList,
      nowshow: e.currentTarget.dataset.type,
      singleshow: true
    });
  },
  // 商品弹窗checkbox点击
  onSingleChange(e) {
    console.log(e,9999999999999)
    this.setData({
      ['affirmList']: e.detail.value,
    })
    if (this.data.nowshow == 'type') {

      if (e.detail.value.length <= 0) {
        this.setData({
          singleshow: false,
        });
        return false;
      }
      let index = e.detail.value[e.detail.value.length - 1]
      // console.log(e.detail)
      this.setData({
        ['affirmList']: index
      });
      this.forList('type', this.data.lists.type.list);
      this.setData({
        ['lists.type.list[' + index + '].checked']: true,
      });
    }
  },
  // 商品弹窗确认选择
  affirmChange(e) {
    let _this = this;
    if (_this.data.nowshow == 'color') {
      this.forList('color', _this.data.lists.color.list);
      listsFor('color');
    } else if (_this.data.nowshow == 'size') {
      this.forList('size', _this.data.lists.size.list);
      listsFor('size');
    }

    function listsFor(type) {
      for (var i = 0; i < _this.data.affirmList.length; i++) {
        let index = _this.data.affirmList[i]
        _this.setData({
          ['lists.' + type + '.list[' + index + '].checked']: true,
        });
      }
    }
    _this.setData({
      singleshow: false,
    });
  },
  // push选中的商品弹窗
  forList(type, data) {
    for (var i = 0; i < this.data.lists[type].list.length; i++) {
      this.setData({
        ['lists.' + type + '.list[' + i + '].checked']: false,
      });
    }
    let ids = [];
    let text = [];
    for (var i = 0; i < this.data.affirmList.length; i++) {
      ids.push(data[this.data.affirmList[i]].value);
      text.push(data[this.data.affirmList[i]].name);
    }
    this.setData({
      ['checkData.' + type + '.ids']: ids.toString(),
      ['checkData.' + type + '.text']: text.toString(),
    })
    this.setData({
      singleshow: false,
    });
  },

  /**
   * 扫码
   */
  onScanCode(e) {
    let _this = this;
    let field = e.currentTarget.dataset.name;
    wx.scanCode({
      success(res) {
        _this.setData({
          goods_code: res.result
        });
      }
    });
  },
  // 条码扫码
  onScanCode2(e) {
    let _this = this;
    let field = e.currentTarget.dataset.name;
    wx.scanCode({
      success(res) {
        _this.setData({
          goods_barcode: res.result
        });
      }
    });
  },

  //编辑商品初始化数据
  getGoodsDetail(e) {
    let _this = this;

    app._get('web/goods/getGoodsDetail', {
      goods_id: e
    }, function (res) {

      let data = res.data.data[0]
      _this.setData({
        ['editImgList']: data.images,
        ['edit_ord_img']: data.images,
        goods_name: data.goods_name,
        goods_status: data.goods_status,
        goods_code: data.goods_code,
        goods_barcode: data.goods_barcode,
        goods_pprice: data.goods_pprice,
        goods_srprice: data.goods_srprice,
        goods_wprice: data.goods_wprice,
        goods_rprice: data.goods_rprice,
        integral: data.integral,
        lock_version: data.lock_version
      })
      // 商品分类选中数据
      for (var i = 0; i < _this.data.lists.type.list.length; i++) {
        if (_this.data.lists.type.list[i].value == data.category_id) {
          _this.setData({
            ['lists.type.list[' + i + '].checked']: true,
            ['checkData.type.ids']: _this.data.lists.type.list[i].value,
            ['checkData.type.text']: _this.data.lists.type.list[i].name,
          });
        }
      }
      let color = [];
      let size = [];
      let color_id = [];
      let size_id = [];
      //商品颜色选中数据
      idFor(color, color_id, 'color_id', 'color');
      //商品尺寸选中数据
      idFor(size, size_id, 'size_id', 'size');

      function idFor(type, type_id, data_id, list_type) {
        for (var i = 0; i < data.detail.length; i++) {
          type.push(data.detail[i][data_id]);
        }
        for (var i = 0; i < type.length; i++) {
          if (type_id.indexOf(type[i]) < 0) {
            type_id.push(type[i])
          }
        }
        let ids = [];
        let text = [];
        for (var j = 0; j < type_id.length; j++) {
          for (var i = 0; i < _this.data.lists[list_type].list.length; i++) {
            if (_this.data.lists[list_type].list[i].value == type_id[j]) {
              ids.push(_this.data.lists[list_type].list[i].value);
              text.push(_this.data.lists[list_type].list[i].name);
              _this.setData({
                ['lists.' + list_type + '.list[' + i + '].checked']: true,
                ['lists.' + list_type + '.list[' + i + '].disabled']: true,//已选择的色码和尺码就不能取消了
              });
            }
          }
        }
        _this.setData({
          ['checkData.' + list_type + '.ids']: ids.toString(),
          ['checkData.' + list_type + '.text']: text.toString(),
        });
        wx.hideLoading()
      }
    }, '', '', '', true)
  },
  // addGoods:{
  //   name:'',
  //   code:'',
  //   all_name:'',
  //   name_text:'',
  //   name_lenth:'',
  //   code_text:'',
  //   code_lenth:''
  // }
  // 添加弹窗显示
  addGoodsManageModal() {
    console.log(this.data.nowshow)
    this.setData({
      singleshow: false,
      addModalShow: true,
      addGoodsCode: '',
      addGoodsName: ''
    })
    if (this.data.nowshow == 'type') {
      this.setData({
        ['addGoods.all_name']: '添加商品分类',
        ['addGoods.name_text']: '分类名称',
        ['addGoods.name_lenth']: 5
      })
    } else if (this.data.nowshow == 'color') {
      this.setData({
        ['addGoods.all_name']: '添加商品颜色',
        ['addGoods.name_text']: '颜色名称',
        ['addGoods.name_lenth']: 8,
        ['addGoods.code_text']: '颜色编号',
        ['addGoods.code_lenth']: 8,
      })
    } else if (this.data.nowshow == 'size') {
      this.setData({
        ['addGoods.all_name']: '添加商品尺码',
        ['addGoods.name_text']: '尺码名称',
        ['addGoods.name_lenth']: 8,
        ['addGoods.code_text']: '尺码编号',
        ['addGoods.code_lenth']: 8,
      })
    }

  },
  // 添加弹窗关闭
  onHideAddGoodsManageModal() {
    this.setData({
      addModalShow: false
    })
  },
  // 添加弹窗确认按钮
  confirmAddGoodsManage() {
    let _this = this;
    if (_this.data.addGoodsName.length <= 0) {
      app.showToast('请输入名称');
      return false;
    }
    if (_this.data.nowshow != 'type') {
      if (_this.data.addGoodsCode.length <= 0) {
        app.showToast('请输入编号');
        return false;
      }
    }
    let data = {};
    if (_this.data.nowshow == 'type') {
      data = {
        category_name: _this.data.addGoodsName
      };
      request('web/Category/saveCategory', data);
    } else if (_this.data.nowshow == 'color') {
      data = {
        color_name: _this.data.addGoodsName,
        color_code: _this.data.addGoodsCode
      };
      request('web/color/saveColor', data);
    } else if (_this.data.nowshow == 'size') {
      data = {
        size_name: _this.data.addGoodsName,
        size_code: _this.data.addGoodsCode
      };
      request('web/size/saveSize', data);
    }
    //新增请求方法
    function request(url, data) {
      app._post_form(url, data, function (res) {
        console.log(res)
        if (res.errcode == 0) {
          app.showSuccess('添加成功', function () {
            _this.getGoodsList();
            _this.onHideAddGoodsManageModal();
          })

        }
      })
    }
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
});