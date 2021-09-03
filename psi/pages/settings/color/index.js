const app = getApp();

Page({
  data: {
    CustomBar: app.globalData.CustomBar,
    isSizeShow: false,
    list: [],
    delSizeId:0,//删除色码 id
    editSizeId:0,//编辑色码 id
    idEdit:0,//是否编辑
    sizeName:'',//编辑器中色码名称
    sizeCode:'',//编辑器中色码代码
    lock_version:'',//编辑器中色码版本
    maxNum:10,//色码最大数量


    tipShow:false,//提示框显示
    tips:"",//提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName:'tipItemClick',//提示框点击确定调用的方法名
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad: function(options) {
    this.loadColorList();
          // 0.新增 1.编辑 2.删除
          let qxList = app.getSomeNodeQx([ '色码新增',  '色码编辑',  '色码删除'])
          this.setData({
            qxList
          })
  },
  // 获取颜色列表
  loadColorList(){
    let _this=this;
    app._get('web/color/loadColorList','',function(res){
      console.log(res)
      if(res.errcode==0){
        for(let i=0;i<res.data.length;i++){
          _this.setData({
            ['list['+i+'].id']:res.data[i].color_id,
            ['list['+i+'].name']:res.data[i].color_name,
            ['list['+i+'].code']:res.data[i].color_code,
            ['list['+i+'].lock_version']:res.data[i].lock_version
          })
        }
      }
    })
  },
  /**
   * 添加色码
   */
  onShowSizeAdd(e) {
    this.setData({
      isSizeShow: true,
      idEdit:0
    });
  },
  /**
   * 编辑色码
   * @param {*} e 
   */
  onShowSizeEdit(e){
    if(!this.data.qxList[1].haveQx){
      app.toast('暂无权限')
      return
    }
    let _this = this;
    _this.setData({
      isSizeShow: true,
      idEdit:1,
      editSizeId:e.currentTarget.dataset.id,
      sizeName:_this.data.list[e.currentTarget.dataset.index].name,
      sizeCode:_this.data.list[e.currentTarget.dataset.index].code,
      lock_version:_this.data.list[e.currentTarget.dataset.index].lock_version,
    });
  },
  //编辑/添加 确定
  onHideSizeAdd(e) {
    let _this = this;
    if(!_this.validateEdit()){
      return false;
    }
    if(_this.data.idEdit==1){
      //编辑色码
      _this.request('web/color/editColor');
    }else{
      // console.log(_this.data.list)
      //新增色码
     _this.request('web/color/saveColor');
    }
    _this.onHideSizeCancel();
  },
  //编辑/添加 取消
  onHideSizeCancel(e) {
    this.setData({
      isSizeShow: false,
      sizeCode:'',
      sizeName:''
    });
  },
  //删除色码 提示
  delSizeTip:function(e){
    let _this = this;
    _this.setData({
      editSizeId:e.currentTarget.dataset.id,
      lock_version:e.currentTarget.dataset.lock_version,
    });
    _this.showTip("确认删除这个色码?","delSize");
  },
  //删除色码
  delSize:function(){
    let _this = this;
    _this.request('web/color/delColor');
    _this.setData({
      tipShow:false
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
  showTip:function(tip,funName,color='#9a9a9a',iscancel=true,btnlist=[{color:'#e53a37',text:'确定'}]){
    let _this = this;
    _this.setData({
      tipShow:true,
      tips:tip,
      tipItemList:btnlist,
      tipItemClickName:funName,
      tipColor:color,
      tipIsCancel:iscancel
    });
  },
  /**
   * zll 隐藏提示框
   */
  tipClose:function(){
    this.setData({
      tipShow:false,
    });
  },
  //zll 检查内容合法性
  validateEdit:function(){
    let _this = this;
    if(_this.data.sizeName.length<=0){
      wx.showToast({
        title: '色码名称必填',
        icon:'none'
      });
      return false;
    }
    if(_this.data.sizeCode.length<=0){
      wx.showToast({
        title: '色码编码必填',
        icon:'none'
      });
      return false;
    }
    return true;
  },
  //新增，编辑，删除，请求方法
  request(url){
    let _this=this;
    let data ={
      color_name:_this.data.sizeName,
      lock_version:_this.data.lock_version,
      color_id:_this.data.editSizeId,
      color_code:_this.data.sizeCode
    }
    app._post_form(url,data,function(res){
      console.log(res)
      if(res.errcode==0){
        _this.setData({
          ['list']:[]
        })
        _this.loadColorList();
      }
    })
  }
})
