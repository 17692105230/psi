let app = getApp()
Page({
  data: {
    last_page: 1, //总页数
    now_page: 1, //当前页
    data_list: [],
  },
  onLoad(o) {
    this.setData({
      id:o.id
    })
    this.getList(true)
  },

  //获取会员消费数据
  getList(isRefresh){
    let  _this = this
    app._get('web/client/sale_orders',{member_id:this.data.id, page: this.data.now_page,},res=>{
      if (res.errcode != 0) {
        _this.setData({
          loadOk:true
        })
        return wx.showToast({
          title: res.errmsg,
          icon: 'error',
          duration: 1500
        })
      }
      let data = res.data
      let last_page = 1
      if(data.rows){
       data = data.rows
       last_page = res.data.total % 20 ? parseInt(res.data.total / 20) + 1 : parseInt(res.data.total / 20)
      }else{
        data = []
      }
      _this.setData({
        data_list:isRefresh? data: [..._this.data.data_list,...data],  
        last_page,
        now_page: ++_this.data.now_page,
      });
      if(isRefresh){
        _this.setData({
          base:res.data.base,
          account:res.data.account,
        })
      }
      _this.setData({
        loadOk:true
      })
    })
  },
  //触底新增
  onReachBottom: function () {
    if (this.data.now_page <= this.data.last_page) {
      this.getList(false)
    } else {
      this.setData({
        nomore: true,
      })
    }

  },
to(e){
  let id = e.currentTarget.dataset.id;
  let orders_type = e.currentTarget.dataset.type;
  wx.navigateTo({
    url: `/pages/sale/sale_order/index?id=${id}&orders_type=${orders_type}`
  })
}
})