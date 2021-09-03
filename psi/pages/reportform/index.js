let app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(o) {
    //设置权限
    let qxList = app.getSomeNodeQx([  '进货分析',   '销售日报',   '库存分析',   '库存查询'])
    this.setData({
      qxList,
      list: [{
          qx: qxList[0].haveQx,
          title: '进货',
          items: [{
            icon: 'cuIcon-home',
            name: '进货分析',
            url: '/pages/reportform/purchase_analysis/index',
            introduce: '从实际,商品,供应商等纬度分析进货情况',
            arrow: true,
            qx: qxList[0].haveQx,
          }, ]
        },
        {
          qx: qxList[1].haveQx,

          title: '销售',
          items: [{
            icon: 'cuIcon-home',
            name: '销售日报',
            url: '/pages/reportform/sales_daily/index',
            introduce: '从不同时间维度,查询企业销售流水',
            arrow: true,
            qx: qxList[1].haveQx,

          }, ]
        },
        {
          qx:qxList[2].haveQx || qxList[3].haveQx,

          title: '库存',
          items: [{
              icon: 'cuIcon-home',
              name: '库存分析',
              url: '/pages/reportform/Inventory_analysis/index',
              introduce: '按店仓,属性等纬度,分析商品库存分布情况',
              arrow: true,
              qx: qxList[2].haveQx,

            },
            {
              icon: 'cuIcon-home',
              name: '库存查询',
              url: '/pages/reportform/Inventory_query/index',
              introduce: '查询商品库存数量和金额',
              arrow: true,
              qx: qxList[3].haveQx,

            }
          ]
        },
      ],
    })

  },
  //跳转页面
  goUrl: function (e) {
    wx.navigateTo({
      url: e.currentTarget.dataset.url,
    })
  }
})