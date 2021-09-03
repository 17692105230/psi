const App = getApp();
Component({
  options: {
    addGlobalClass: true
  },
  data: {
    sImgs: []
  },
  properties: {
    title: {
      type: String,
      value: '图片'
    },
    isDel: {
      type: String,
      value: 'block'
    },
    userId: {
      type: Number,
      value: 1
    },
    imgType: {
      type: Number,
      value: 1
    },
    imgList: {
      type: Array,
      value: [{
        code: "logo",
        must: true,
        name: "身份证正面",
        lpath: "",
        spath: "",
        percent: 0
      }, {
        code: "sfzzm",
        must: true,
        name: "身份证反面",
        lpath: "",
        spath: "",
        percent: 0
      }, {
        code: "sfzfm",
        must: true,
        name: "营业执照",
        lpath: "",
        //spath: "https://ossweb-img.qq.com/images/lol/web201310/skin/big84008.jpg",
        spath: '',
        percent: 0
      }, {
        code: "scsfzzm",
        must: true,
        name: "税务登记证",
        lpath: "",
        //spath: "https://ossweb-img.qq.com/images/lol/web201310/skin/big84009.jpg",
        spath: '',
        percent: 0
      }],
      observer: function(newVal, oldVal) {
        buildImgs(this);
      }
    }
  },
  methods: {
    // 选择图片
    chooseImg(e) {
      cImg(this, e.currentTarget.dataset.index);
    },
    // 删除图片
    deleteImg(e) {
      dImg(this, e.currentTarget.dataset.index);
    },
    // 预览图片
    previewImg(e) {
      pImg(e, this);
    }
  },
  ready: function() {}
});

// 选择图片
const cImg = (_this, index) => {
  if (_this.data.userId <= 0) {
    console.log(`%c up-img提醒您，userId 字段必须赋值。`, `color:#f00;font-weight:bold;`)
    return;
  }
  wx.chooseImage({
    count: 1,
    sizeType: ['compressed'],
    sourceType: ['album', 'camera'],
    success: function(res) {
      _this.data.imgList[index]['lpath'] = res.tempFiles[0]['path'];
      _this.data.imgList[index]['spath'] = '';
      _this.data.imgList[index]['percent'] = 0;

      upload_file_server(_this, _this.data.imgList, index);

      _this.setData({
        ['imgList[' + index + ']']: _this.data.imgList[index]
      });
    }
  });
};

// 上传文件
const upload_file_server = (_this, imgs, index) => {
  const upload_task = wx.uploadFile({
    // 上传地址
    url: App.api_root + '/test/assis.upload/credentialsImage',
    // 需要用HTTPS，同时在微信公众平台后台添加服务器地址
    filePath: imgs[index]['lpath'],
    // 上传的文件本地地址
    name: 'iFile',
    "Content-Type": "multipart/form-data",
    formData: {
      'user_id': _this.data.userId,
      'img_type': _this.data.imgType,
      'alias_name': imgs[index]['name'],
      'alias_code': imgs[index]['code'],
      'wxapp_id': App.getWxappId(),
      'token': wx.getStorageSync('token')
    },
    success(res) {
      let data = JSON.parse(res.data);
      let filename = data.data.img_path;
      _this.setData({
        ['imgList[' + index + '].spath']: filename
      });
      _this.triggerEvent('upImgData', imgList);
      buildImgs(_this);
    },
    fail(res) {
      console.log(res);
    },
    complete(res) {
      console.log(res);
    }
  });
  upload_task.onProgressUpdate((res) => {
    _this.setData({
      ['imgList[' + index + '].percent']: res.progress
    });
  });
};

// 删除图片
const dImg = (_this, index) => {
  _this.data.imgList[index]['lpath'] = '';
  _this.data.imgList[index]['spath'] = '';
  _this.data.imgList[index]['percent'] = 0;

  _this.setData({
    ['imgList[' + index + ']']: _this.data.imgList[index]
  });

  buildImgs(_this);

  _this.triggerEvent('upImgData', _this.data.imgList);
};

// 预览图片
const pImg = (e, _this) => {
  console.log(_this.data.imgList[e.currentTarget.dataset.index]);
  wx.previewImage({
    current: _this.data.sImgs[e.currentTarget.dataset.index],
    urls: _this.data.sImgs
  })
};

// 构造图片列表
const buildImgs = (_this) => {
  _this.data.sImgs = [];
  _this.data.imgList.forEach(function(item, i) {
    //if (item.percent == 100) {
    _this.data.sImgs.push(item.spath);
    //}
  });
  _this.setData({
    sImgs: _this.data.sImgs
  });
}