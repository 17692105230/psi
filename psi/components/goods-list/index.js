Component({
  options: {
    addGlobalClass: true
  },
  data: {},
  properties: {
    // 色码尺码数据
    list: {
      type: Array,
      value: [],
      observer: function (newVal, oldVal) { }
    }
  },
  methods: {
    onEditGoods(e) {
      this.triggerEvent('EditGoods', this.properties.list[e.currentTarget.dataset.index]);
    },
    onItemClick(e) {
      let isExpand = true;
      if (typeof (this.data.list[e.currentTarget.dataset.index].expand) != 'undefined')
        isExpand = !this.data.list[e.currentTarget.dataset.index].expand;
      this.setData({
        ['list[' + e.currentTarget.dataset.index + '].expand']: isExpand
      });
    }
  },
  ready: function () {
    
  }
});