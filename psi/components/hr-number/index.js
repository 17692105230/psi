const calc = require('./calc.js'); //导入js文件
Component({
  options: {
    addGlobalClass: true
  },
  data: {
    op: '',
    result: null, //保存结果
    isClear: false,
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
      observer: function (newVal, oldVal) {}
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
    numBtn: function (e) {
      var value = e.target.dataset.val;
      if (this.data.value === '0' || this.data.isClear) { //设置新值
        this.setData({
          value: value,
        })
        this.data.isClear = false;
      } else { //追加
        this.setData({
          value: this.data.value + value,
        })
      }
    },
    opBtn: function (e) {
      var op = this.data.op;
      var value = Number(this.data.value);
      this.setData({
        op: e.target.dataset.val
      });
      if (this.data.isClear) {
        return;
      }
      this.data.isClear = true;
      if (this.data.result === null) { //把第一个值存入result
        this.data.result = value;
        return
      }
      //计算两个值的结果
      if (op === '+') {
        this.data.result = calc.add(this.data.result, value);
      } else if (op === '-') {
        this.data.result = calc.sub(this.data.result, value);
      } else if (op === '*') {
        this.data.result = calc.mul(this.data.result, value);
      } else if (op === '/') {
        this.data.result = calc.div(this.data.result, value);
      }
      this.setData({
        value: this.data.result + '',
      })
    },
    dotBtn: function (e) {

      if (this.data.isClear) { //设置新值
        this.setData({
          value: '0.',
        })
        this.data.isClear = false
        return;
      }
      if (this.data.value.indexOf('.') >= 0) {
        return
      }
      this.setData({ //追加
        value: this.data.value + '.',
      })
    },
    delBtn: function (e) {
      var value = this.data.value.substr(0, this.data.value.length - 1);
      this.setData({
        value: value === '' ? '0' : value,
      })
    },
    resetBtn: function (e) {
      this.data.result = null;
      this.data.isClear = false;
      this.setData({
        value: '0',
        op: '',
      })
    },
    onConfirm(e) {
      this.opBtn({
        target: {
          dataset: {
            val: '='
          }
        }
      })
      this.setData({
        showModal: false,
        op: '',
        result: null,
        isClear: false,
      });
      this.triggerEvent('Confirm', parseFloat(this.data.value));
    },
    /**
     * 取消事件
     */
    onCancel(e) {
      this.setData({
        showModal: false,
        op: '',
        result: null,
        isClear: false,
      });
      this.triggerEvent('Cancel', parseFloat(this.data.value));
    }
  },
});