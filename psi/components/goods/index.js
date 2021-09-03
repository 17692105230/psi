let app = getApp()
Component({
  observers: {
    'goods,list': function (goods, list) {}
  },
  options: {
    addGlobalClass: true
  },
  data: {
    hrnum: {
      show: false,
      value: '0'
    }
  },
  properties: {
    // 是否显示
    mIsDisplay: {
      type: Boolean,
      value: false,
      observer: function (newVal, oldVal) {
        console.log(newVal, this.data, '组件显示了')
        let list = this.data.list
        let flag = 0
        list.forEach(i=>{
          if(i.checked){
            ++flag
          }
        })
        if(flag==0){
          list[0].checked = true
          this.setData({
            list
          })
        }
      }

    },
    // 尺码显示索引
    mSelectIndex: {
      type: Number,
      value: 0
    },
    // 单选 true | 多选 false
    mSelectType: {
      type: Boolean,
      value: false
    },
    // 文字数据
    words: {
      type: Object,
      value: {
        submit: '确定',
        clear: '清空',
        style_code: '款号',
        size_name: '尺码',
        number_name: '库存'
      },
      observer: function (newVal, oldVal) {}
    },
    // 商品数据
    goods: {
      type: Object,
      value: {
        goods_id: 0,
        goods_name: '服装',
        style_code: 'HR2020-0319-0001',
        goods_code: '0',
        stockQuantity: 0,
        inputQuantity: 0,
        inputMoney: 0,
        price: 0,
        sale_price: 0
      },
      observer: function (newVal, oldVal) {}
    },
    // 色码尺码数据
    list: {
      type: Array,
      value: [],
      observer: function (newVal, oldVal) {}
    }
  },

  methods: {
    /**
     *  确定按钮
     */
    hideGoodsSelectModal(e) {
      this.setData({
        mIsDisplay: false
      });
      this.data.goods.list = this.data.list; //zll 临时
      this.triggerEvent('ReturnGoodsData', this.data.goods);
    },
    //zll 点击空白处
    hideGoods() {
      this.setData({
        mIsDisplay: false
      });
    },
    /**
     * 清除数据
     */
    onClearData(e) {
      let list = this.data.list,
        goods = this.data.goods;
      list.forEach((cItem, cIndex) => {
        cItem.sizeList.forEach((sItem, sIndex) => {
          sItem.inputQuantity = 0;
          goods.inputQuantity = 0;
          goods.inputMoney = 0;
          cItem.inputQuantity = 0;
        });
      });
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list; //zll 临时
      this.triggerEvent('ReturnGoodsData', this.data.goods);
    },
    /**
     * 选择色码
     */
    onSelectColor(e) {
      if (this.data.endTime - this.data.startTime > 350) {
        return;
      }
      // 震动
      wx.vibrateShort();
      let index = e.currentTarget.dataset.index;
      if (this.data.mSelectType) {
        let list = this.data.list;
        list.forEach((cItem, cIndex) => {
          cItem.checked = (index == cIndex);
        });
        this.setData({
          mSelectIndex: index,
          list: list
        });
      } else {
        // 多选
        let list = this.data.list;

        if (list[index].checked) {
          // 设置为不选中
          list[index].checked = false;
          let _Select = -1;
          for (var i = 0; i < list.length; i++) {
            if (list[i].checked) {
              _Select = i;
              break;
            }
          }
          this.setData({
            mSelectIndex: _Select < 0 ? index : _Select,
            ['list[' + index + '].checked']: _Select < 0
          });
          return;
        }
        this.setData({
          mSelectIndex: index,
          ['list[' + index + '].checked']: !list[index].checked
        });
      }
    },
    /**
     * 显示色码对应的尺码
     */
    onSelectSizeFromColor(e) {
      let index = e.currentTarget.dataset.index;
      if (!this.data.mSelectType && this.data.list[index].checked) {
        wx.vibrateShort();
        this.setData({
          mSelectIndex: index
        });
      }
    },
    /**
     * 全部减少
     */
    onAllReduce(e) {
      let list = this.data.list,
        goods = this.data.goods;
      list.forEach((cItem, cIndex) => {
        if (cItem.checked) {
          cItem.sizeList.forEach((sItem, sIndex) => {
            // 如果尺码数量大于 0 则进行运算
            if (sItem.inputQuantity > 0) {
              sItem.inputQuantity -= 1;
              if (goods.inputQuantity > 0) {
                goods.inputQuantity -= cItem.inputQuantity == 0 ? 0 : 1;
                goods.inputMoney -= cItem.inputQuantity == 0 ? 0 : goods.sale_price;
              }
              if (cItem.inputQuantity > 0) {
                cItem.inputQuantity -= 1;
              }
            }
          });
        }
      });
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list;
      this.triggerEvent('ReturnRealTimeData', this.data.goods);
    },
    /**
     * 全部增多
     */
    onAllPlus(e) {
      let list = this.data.list,
        goods = this.data.goods;
      list.forEach((cItem, cIndex) => {
        if (cItem.checked) {
          cItem.sizeList.forEach((sItem, sIndex) => {
            sItem.inputQuantity += 1;
            cItem.inputQuantity += 1;
            goods.inputQuantity += 1;
            goods.inputMoney += goods.sale_price;
          });
        }
      });
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list;
      this.triggerEvent('ReturnRealTimeData', this.data.goods);
    },
    /**
     * 单个减少
     */
    onUnitReduce(e) {
      let index = e.currentTarget.dataset.index,
        list = this.data.list,
        goods = this.data.goods;
      list.forEach((cItem, cIndex) => {
        if (cItem.checked) {
          // 如果尺码数量大于 0 则进行运算
          if (cItem.sizeList[index].inputQuantity > 0) {
            cItem.sizeList[index].inputQuantity -= 1;
            if (goods.inputQuantity > 0) {
              goods.inputQuantity -= 1;
              goods.inputMoney -= goods.sale_price;
            }
            if (cItem.inputQuantity > 0) {
              cItem.inputQuantity -= 1;
            }
          }
        }
      });
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list;
      this.triggerEvent('ReturnRealTimeData', this.data.goods);
    },
    /**
     * 单个增多
     */
    onUnitPlus(e) {
      let index = e.currentTarget.dataset.index,
        list = this.data.list,
        goods = this.data.goods;
      console.log(list, goods, 23534)
      list.forEach((cItem, cIndex) => {
        if (cItem.checked) {
          cItem.sizeList[index].inputQuantity += 1;
          cItem.inputQuantity += 1;
          goods.inputQuantity += 1;
          goods.inputMoney += goods.sale_price;
        }
      });
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list;
      this.triggerEvent('ReturnRealTimeData', this.data.goods);
    },
    /**
     * 单选或多选
     */ 
    onSelectType(e) {
      let index = -1;
      let list = this.data.list;
      if (e.detail.value) {
        list.forEach((cItem, cIndex) => {
          if (index < 0 && cItem.checked) {
            index = cIndex;
          }
          cItem.checked = false;
        });
        list[index].checked = true;
      }
      this.setData({
        mSelectIndex: index < 0 ? 0 : index,
        mSelectType: e.detail.value,
        list: list
      });
    },
    /**
     * 单一尺码输入数量
     */
    onUnitInput(e) {
      let index = e.currentTarget.dataset.index,
        list = this.data.list,
        goods = this.data.goods;

      let newNum = parseInt(isPositiveInteger(e.detail.value) ? e.detail.value : 0);
      let oldNum = list[this.data.mSelectIndex].sizeList[index].inputQuantity;
      //console.log('新：' + newNum + '旧：' + oldNum);
      list.forEach((cItem, cIndex) => {
        if (cItem.checked) {
          cItem.inputQuantity -= oldNum;
          goods.inputQuantity -= oldNum;
          goods.inputMoney -= goods.sale_price * oldNum;
          cItem.sizeList[index].inputQuantity = newNum;
          cItem.inputQuantity += newNum;
          goods.inputQuantity += newNum;
          goods.inputMoney += goods.sale_price * newNum;
        }
      });
      console.log(list, '色码list')
      this.setData({
        list: list,
        goods: goods
      });
      this.data.goods.list = this.data.list;
      this.triggerEvent('ReturnRealTimeData', this.data.goods);
    },
    bindTouchStart(e) {
      this.setData({
        startTime: e.timeStamp
      });
    },
    bindTouchEnd(e) {
      this.setData({
        endTime: e.timeStamp
      });
    },
    /**
     * 显示输入金额
     */
    onNumberModalShow(e) {
      this.setData({
        'hrnum.show': true,
        'hrnum.value': this.data.goods.sale_price
      });
    },
    /**
     * 确认输入金额
     */
    onNumberConfirm(e) {
      this.setData({
        'goods.sale_price': e.detail
      });
    },
    //zll 默认图片
    errorImg(e) {
      //goods.images[0].assist_url
      let _this = this;
      if (e.type == "error") {
        console.log("无图片,显示默认");
        let goods = this.data.goods;
        goods.images[0].assist_url = "/images/my/default_img.png"
        _this.setData({
          goods: goods
        });
        console.log('errImg',_this.data.goods)
      }
    },
    noHide(){
      return false
    }
  },

});

/**
 * 判断是否为正整数
 */
const isPositiveInteger = (value) => {
  return /(^[0-9]\d*$)/.test(value);
};

const outputData = (_goods, _list) => {

};