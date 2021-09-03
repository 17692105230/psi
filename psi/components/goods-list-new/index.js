// pages/ceshi/ceshi.js
Component({
  options: {
    addGlobalClass: true
  },
  data: {
    cuIcon_right2:[]
  },
  properties: {
    // 色码尺码数据
    list: {
      type: Object,
      value: [],
    },
    //二级列表名称(颜色)
    twoname:{
      type:String,
      value:'list'
    },
    //三级列表名称(尺码)
    threename:{
      type:String,
      value:'sizeList'
    },
    //是否显示删除和编辑按钮,正式单不显示
    edit:{
      type:Boolean,
      value:false,
    }
  },
  observers:{
    'list':function(list){
      let _this = this; 
      //列表数据发生改变,计算总价,并返回
      let sumprice = 0;
      let sumcount = 0;
      list.forEach((item)=>{
        //把goods_pprice采购价该为了goods_rprice零售价 
        sumprice = (sumprice*100 + ((item.goods_rprice?item.goods_rprice:item.goods_price)*item.inputQuantity).toFixed(2)*100) / 100;
        sumcount = sumcount + item.inputQuantity;
      });
      let detail = {
        sumprice:sumprice,
        sumcount:sumcount
      };
      _this.triggerEvent('ListChange', detail); 
    }
  },
  methods: {
    onEditGoods(e) { 
      let _this = this;
      console.log(_this.data.list,111111111);
      let detail = {
        index:e.currentTarget.dataset.index,
        goodsInfo:_this.properties.list[e.currentTarget.dataset.index]
      };
      _this.triggerEvent('EditGoods', detail);
    },
    //删除商品 zll
    onDelGoods(e){
      let _this = this;
      _this.triggerEvent('DelGoods', e.currentTarget.dataset.index);
    },
    onItemClick(e) {      
      let _this = this;
      let linshi = _this.data.list;
      linshi[e.currentTarget.dataset.index].colorShow = !linshi[e.currentTarget.dataset.index].colorShow;      
      this.setData({
        list:linshi,cuIcon_right:!this.data.cuIcon_right
      });
    },
    onColorClick(e) {
      let _this = this;
      let linshi = _this.data.list;
      e.currentTarget.dataset.index;
      e.currentTarget.dataset.colorindex;
      linshi[e.currentTarget.dataset.index][_this.properties.twoname][e.currentTarget.dataset.colorindex].sizeShow = !linshi[e.currentTarget.dataset.index][_this.properties.twoname][e.currentTarget.dataset.colorindex].sizeShow;
      let index = e.currentTarget.dataset.colorindex+'';
      let cuIcon_right2 = this.data.cuIcon_right2
      console.log(cuIcon_right2)

      cuIcon_right2[index] = cuIcon_right2[index]?!cuIcon_right2[index]:true
      _this.setData({
        list:linshi,
        cuIcon_right2
      });
      console.log(cuIcon_right2)
    }
  },
  ready: function () {
      // console.log(this.properties.list)
  }
});
