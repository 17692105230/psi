const app = getApp();
Page({
  data: {
    tipShow:false,//提示框显示
    tips:"",//提示内容
    tipMaskClosable: true,
    tipItemList: [],
    tipColor: "#9a9a9a",
    tipSize: 26,
    tipIsCancel: true,
    tipItemClickName:'tipItemClick',//提示框点击确定调用的方法名
    typelist:[],
    typeindex:0,
    typeid:0,
    typename:'',
    isTypeShow:false,//编辑框显示
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad(){
    this.loadCategory();
          let qxList = app.getSomeNodeQx(['分类新增', '分类编辑', '分类删除'])
          this.setData({
            qxList
          })
  },
  // 加载列表数据请求
  loadCategory(){
    let _this=this;
    app._get('web/Category/loadCategory','',function(res){
      if(res.errcode==0){
        for(let i=0;i<res.data.length;i++){
          _this.setData({
            ['typelist['+i+'].id']:res.data[i].category_id,
            ['typelist['+i+'].name']:res.data[i].category_name,
            ['typelist['+i+'].lock_version']:res.data[i].lock_version,
          })
        }
      }
    })
  },
  /**
   * zll 显示提示框
   */
  showTip:function(tip,funName){
    let _this = this;
    _this.setData({
      tipShow:true,
      tips:tip,
      tipItemList:[{color:'',text:'编辑'},{color:'#e53a37',text:'删除'}],
      tipItemClickName:funName
    });
  },
  /**
   * zll 点击提示框确认按钮
   */
  tipItemClick:function(e){
    let _this = this;
    console.log(e);
    let type = e.detail.index;
    switch (type) {
      case 0:
        if(!this.data.qxList[1].haveQx){
          app.toast('暂无权限')
          return
        }
        _this.setData({
          isTypeShow:true,addType:false,
        });
        break;
      case 1:
        if(!this.data.qxList[2].haveQx){
          app.toast('暂无权限')
          return
        }
        wx.showModal({
          title:"提示",
          content:"确定删除吗?",
          success:function(res){
            if(res.confirm){
              _this.delItem();
            }
          }
        });
        
        break;
      default:
        
        break;
    }
    
    _this.tipCloseActionSheet();
  },
  /**
   * zll 点击提示框取消
   */
  tipCloseActionSheet:function(e){
    console.log(12345)
    let _this = this;
    _this.setData({
      tipShow:false
    });
  },
  //zll 点击分类项目
  clickType:function(e){
    let _this = this;
    _this.setData({
      typeindex:e.currentTarget.dataset.index,
      typeid:e.currentTarget.dataset.id,
      typename:e.currentTarget.dataset.name,
      lock_version:e.currentTarget.dataset.lock_version
    });
    _this.showTip("","tipItemClick");
  },
  //zll 删除分类
  delItem:function(){
    let _this = this;
    _this.request('web/Category/delCategory');
  },
  //zll 隐藏编辑框
  onHideTypeCancel:function(){
    // console
    this.setData({
      isTypeShow:false,
    });
  },
  //zll 编辑框 确定
  onHideTypeAdd:function(){
    
    let _this = this;
    console.log(this.data.typename)
    if(_this.data.typename.length<=0){
      wx.showToast({
        icon:'none',
        title: '分类名无效',
      });
      return false;
    }
    if(_this.data.typeid==0){
      // 新增数据
      _this.request('web/Category/saveCategory');
    }else{
      // 编辑数据
      _this.request('web/Category/editCategory');
    }
    _this.onHideTypeCancel();
  },
  //zll 新增分类
  addType:function(){
    let _this = this;
    _this.setData({
      typename:"",
      typeid:0,
      typeindex:-1,
      isTypeShow:true,
      addType:true
    });
  },
  //新增，编辑，删除，请求方法
  request(url){
    let _this=this;
    let data ={
      category_name:_this.data.typename,
      lock_version:_this.data.lock_version,
      category_id:_this.data.typeid
    }
    app._post_form(url,data,function(res){
      console.log(res)
      if(res.errcode==0){
        _this.setData({
          ['typelist']:[]
        })
        _this.loadCategory();
      }
    })
  },
});
