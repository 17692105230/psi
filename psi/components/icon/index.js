/**
 * 评分组件子组件
 */
Component({
  /**
   * 组件的属性列表
   */
  externalClasses: ['l-class', 'l-class-self', ],
  options: {
    addGlobalClass: true,
  },
  properties: {
    name: {
      type: String,
      value: ''
    },
    color: {
      type: String,
      value: '',
    },
    size: {
      type: String,
      value: '',
    },
  },
  /**
   * 组件的初始数据
   */
  data: {
    default: {
      size: 40,
      color: '#45526B',
    },
  },
  /**
   * 组件的初始化方法
   */
  ready: function() {
    if (!this.data.name) {
      console.error('请传入Icon组件的name属性');
    }
  },
  /**
   * 组件的方法列表
   */
  methods: {}
});