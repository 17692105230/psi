// pages/ceshi/ceshi.js
Component({
  options: {
    addGlobalClass: true
  },
  data: {},
  properties: {
    // 商品数据
    list: {
      type: Object,
      value: [],
    },
    // 
  },
  //数据变化监控
  observers: {
    'list': function (list) {
      
    }
  },
  methods: {
    //点击商品项 触发
    onTapGoods(e) {
      let _this = this;
      let detail = _this.properties.list[e.currentTarget.dataset.index];
      _this.triggerEvent('onTapGoods', detail);
    },  errImg(e){
      let index = e.currentTarget.dataset.index
      let img = `list[${index}].images[0].assist_url`
      console.log(index,img,185961)
      this.setData({
        [img]:'/images/my/default_img.png'
      })
    },
  },

  ready: function () {
    console.log("商品列表组件 调用")
  }
});