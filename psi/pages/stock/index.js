// pages/purchase/index.js
let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
  },
  toUrl: function (e) {
    let qx = e.currentTarget.dataset.qx
    let haveQx = qx.haveQx
    console.log(qx,haveQx,996)
    if(haveQx){
      wx.navigateTo({
        url: e.currentTarget.dataset.url,
      })
    }else{
      app.toast('暂无权限')
    }

  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let qxList = app.getSomeNodeQx(['盘点单列表','盘点单新增','调拨单列表','调拨单新增','库存分析','库存查询'])
    this.setData({
      //业务功能列表
      quickList: [{
          name: '盘点单',
          introduce: '盘点商品实际库存',
          icon: 'cuIcon-edit',
          listpage: `/pages/common/common_list/index?type=pdd`,
          addpage: '/pages/stock/check_order/index',
          isadd: true,
          qx:qxList[0],
          addqx:qxList[1]
        },
        {
          name: '调拨单',
          introduce: '把商品A从仓库W调到仓库E',
          icon: 'cuIcon-edit',
          listpage: `/pages/common/common_list/index?type=dbd`,
          addpage: '/pages/stock/allot_order/index',
          isadd: true,
          qx:qxList[2],
          addqx:qxList[3]
        },
      ],
      detailList: [{
        name: '库存分析',
        introduce: '查询分析商品库存数量和金额',
        icon: 'cuIcon-edit',
        listpage: '/pages/reportform/Inventory_analysis/index',
        addpage: '',
        isadd: false,
        qx: qxList[4]
      },
      {
        name: '库存查询',
        introduce: '查询每个商品的当前库存和均价',
        icon: 'cuIcon-edit',
        listpage: '/pages/reportform/Inventory_query/index',
        addpage: '',
        isadd: false,
        qx: qxList[5],
      }
    ],
    })
  },

})