Component({
  options: {
    addGlobalClass: true
  },
  data: {
    status: -1,
    KeyboardKeys: [1, 2, 3, 4, 5, 6, 7, 8, 9, '·', 0, '<']
  },
  properties: {
    // 标题
    title: {
      type: String,
      value: '商品金额'
    },
    // 金额
    value: {
      type: String,
      value: '',
      observer: function(newVal, oldVal) {}
    },
    showCancel: {
      type: Boolean,
      value: true
    },
    cancelText: {
      type: String,
      value: '取消'
    },
    cancelColor: {
      type: String,
      value: '#000000'
    },
    confirmText: {
      type: String,
      value: '确定'
    },
    confirmColor: {
      type: String,
      value: '#576B95'
    },
    showModal: {
      type: Boolean,
      value: true
    }
  },
  methods: {
    /**
     * 键盘事件
     */
    keyTap(e) {
      let keys = e.currentTarget.dataset.keys,
        value = this.data.value,
        len = value.length;
      switch (keys) {
        case '·': // 点击小数点
          if (len < 10 && value.indexOf('.') == -1) {
            if (value.length < 1) {
              value = '0.';
            } else {
              value += '.';
            }
          }
          break;
        case '<': // 如果点击删除键就删除字符串里的最后一个
          value = value.substr(0, value.length - 1);
          break;
        default:
          if (value.length == 1 && value.substr(0, 1) == '0') {
            value = '';
          }
          let Index = value.indexOf('.'); //小数点在字符串中的位置
          if (Index == -1 || len - Index != 3) { //这里控制小数点只保留两位
            if (len < 11) { //控制最多可输入10个字符串
              value += keys;
            }
          }
          break
      }
      this.setData({
        value
      });
    },
    /**
     * 确定事件
     */
    onConfirm(e) {
      this.setData({
        showModal: false
      });
      this.triggerEvent('Confirm', parseFloat(this.data.value));
    },
    /**
     * 取消事件
     */
    onCancel(e) {
      this.setData({
        showModal: false
      });
      this.triggerEvent('Cancel', parseFloat(this.data.value));
    }
  },
  ready: function() {

  }
});