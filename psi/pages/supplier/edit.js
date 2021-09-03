// pages/supplier/edit.js
var app=getApp();
Page({
  data: {
    // supplier: {
    //   id: 11,
    //   name: "北京心海佳丽服装服饰有限公司",
    //   manager: '孟非',
    //   mobile: '17737876877',
    //   discount: 95,
    //   isDefault: false,
    //   detailedAddress: '北京市丰台区马家堡东路106号远洋地产自然新天地13A',
    // },
    supplier_name:'',
    supplier_director:'',
    supplier_mphone:'',
    supplier_discount:'',
    supplier_status:false,
    supplier_address:'',
    supplier_money:'0.00',
    type:'',//储存页面类型
    lock_version:'',
    supplier_id:'',
    page_name:'编辑'
  },
  clearV(e){
    let clearkey = e.currentTarget.dataset.clearkey
    this.setData({
      [clearkey]:''
    })
  },
  onLoad: function (options) {
    console.log(options)
    this.setData({
      type:options.type,
      lock_version:options.lock_version,
      supplier_id:options.supplier_id
    })
    if(options.type=='add'){
      this.setData({
        page_name:'添加'
      })
    }else{
      this.loadSupperInfo(options.supplier_id,options.lock_version);
    }
  },
  //按钮
  switchChange(){
    this.setData({
      supplier_status:!this.data.supplier_status
    })
  },
  //加载修改页面数据
  loadSupperInfo(id,lock){
    let _this=this;
    app._get('web/supplier/loadSupplierInfo',{supplier_id:id,lock_version:lock},function(res){
      if (res.errcode==0) {
        let supplier_status
        if (res.data.supplier_status==1) {
          supplier_status=true;
        }else{
          supplier_status=false;
        }
        _this.setData({
          supplier_name:res.data.supplier_name,
          supplier_director:res.data.supplier_director,
          supplier_mphone:res.data.supplier_mphone,
          supplier_discount:res.data.supplier_discount,
          supplier_status:supplier_status,
          supplier_address:res.data.supplier_address,
          supplier_money:res.data.supplier_money
        })
      }
    })
  },
  // 保存提交
  submit(){
    if(this.data.supplier_name.length<=0){
      app.showToast("请输入供应商名称");
      return false;
    }
    if(this.data.supplier_director.length<=0){
      app.showToast("请输入负责人名称");
      return false;
    }
    if(this.data.supplier_mphone.length<=0){
      app.showToast("请输入联系电话");
      return false;
    }
    let supplier_status
    if(this.data.supplier_status==false){
      supplier_status=0;
    }else{
      supplier_status=1;
    }
    let data={
      supplier_name:this.data.supplier_name,
      supplier_director:this.data.supplier_director,
      supplier_mphone:this.data.supplier_mphone,
      supplier_discount:this.data.supplier_discount,
      supplier_status:supplier_status,
      supplier_address:this.data.supplier_address,
      supplier_money:this.data.supplier_money,
      supplier_id:this.data.supplier_id,
      lock_version:this.data.lock_version
    }
    if(this.data.type=='add'){
      request('web/supplier/saveSupplier',data)
    }else{
      request('web/supplier/editSupplier',data)
    }
    function request(url,data){
      app._post_form(url,data,function(res){
        if(res.errcode==0){
          app.showSuccess('保存成功',function(){
            wx.navigateBack({
              delta: 1
            })
          })
        }
      })
    }
  }
})