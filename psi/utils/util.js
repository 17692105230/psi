const formatTime = date => {
  const year = date.getFullYear()
  const month = date.getMonth() + 1
  const day = date.getDate()
  const hour = date.getHours()
  const minute = date.getMinutes()
  const second = date.getSeconds()
  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}
const formatNumber = n => {
  n = n.toString()
  return n[1] ? n : '0' + n
}
module.exports = {
  // 格式化时间
  formatTime: formatTime,
  //scene解码
  scene_decode(e) {
    if (e === undefined)
      return {};
    let scene = decodeURIComponent(e),
      params = scene.split(','),
      data = {};
    for (let i in params) {
      var val = params[i].split(':');
      val.length > 0 && val[0] && (data[val[0]] = val[1] || null)
    }
    return data;
  },
  // 格式化日期格式 (用于兼容ios Date对象)
  format_date(time) {
    // 将xxxx-xx-xx的时间格式，转换为 xxxx/xx/xx的格式 
    return time.replace(/\-/g, "/");
  },
  //对象转URL
  urlEncode(data) {
    var _result = [];
    for (var key in data) {
      var value = data[key];
      if (value.constructor == Array) {
        value.forEach(_value => {
          _result.push(key + "=" + _value);
        });
      } else {
        _result.push(key + '=' + value);
      }
    }
    return _result.join('&');
  },

  //遍历对象
  objForEach(obj, callback) {
    Object.keys(obj).forEach((key) => {
      callback(obj[key], key);
    });
  },

  //是否在数组内
  inArray(search, array) {
    for (var i in array) {
      if (array[i] == search) {
        return true;
      }
    }
    return false;
  },
  //判断是否为正整数
  isPositiveInteger(value) {
    return /(^[0-9]\d*$)/.test(value);
  },
  //zll 获取当前日期
  getNowDate() {
    let myDate = new Date();
    let year = myDate.getFullYear();
    let month = myDate.getMonth() + 1;
    let date = myDate.getDate();
    return year + "-" + (month > 9 ? month : '0' + month) + "-" + (date > 9 ? date : '0' + date);
  },
  //获取一个月前的日期
  //入参格式：YYYY-MM-DD
  getPreMonthDay(date) {
    var arr = date.split('-');
    var year = arr[0];     //当前年
    var month = arr[1];      //当前月
    var day = arr[2];        //当前日
    //验证日期格式为YYYY-MM-DD
    var reg = date.match(/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/);
    if ((!reg) || (month > 12) || (day > 31)) {
        console.log('日期或格式有误！请输入正确的日期格式（年-月-日）');
        return;
    }
    var pre_year = year;     //前一个月的年
    var pre_month = parseInt(month) - 1;      //前一个月的月，以下几行是上月数值特殊处理
    if (pre_month === 0) {
        pre_year = parseInt(pre_year) - 1;
        pre_month = 12;
    }
    var pre_day = parseInt(day);       //前一个月的日，以下几行是特殊处理前一个月总天数
    var pre_month_alldays = new Date(pre_year, pre_month, 0).getDate();    //巧妙处理，返回某个月的总天数
    if (pre_day > pre_month_alldays) {
        pre_day = pre_month_alldays;
    }
    if (pre_month < 10) {   //补0
        pre_month = '0' + pre_month;
    }
    else if (pre_day < 10) {   //补0
        pre_day = '0' + pre_day;
    }
    var pre_month_day = pre_year + '-' + pre_month + '-' + pre_day;
    return pre_month_day;
},

  /*
    cpf 
    获取常用列表
    参数: arr 具体获取那些数据,如不传则获取全部  
    arr示例: ['settlement','supplier','category','client','color','organization','size','user','despatch']
    对应关系: ['结算账户',  '供应商',   '商品分类',  '客户', '色码',     '仓库',     '尺码','销售员','发货方式']
  */

  async getAllList(arr) {
    let app = getApp();
    let list = {}
    //为了解决loading框多次弹出
    let loading_count = arr.length;
    wx.showLoading({
      title: '加载中...',
    })
    //权限数组
    if (!arr || arr.includes('node_list')) {
      list.node_list = await new Promise((resolve, reject) => {
        app._get("web/power/node_list", {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(res.data);
        });
      },()=>{},()=>{},0,1)
      list.node_list.name = '权限数组'
    }
    //结算账户
    if (!arr || arr.includes('settlement')) {
      list.settlement = await new Promise((resolve, reject) => {
        app._get("web/settlement/loadSettlement", {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.settlement_id,
                code: i.settlement_code,
                name: i.settlement_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.settlement.name = '结算账户'
    }
    //供应商
    if (!arr || arr.includes('supplier')) {
      list.supplier = await new Promise((resolve, reject) => {
        app._get("web/supplier/getSupplierList", {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.supplier_id,
                name: i.supplier_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.supplier.name = '供应商'
    }

    //仓库列表
    if (!arr || arr.includes('organization')) {
      list.organization = await new Promise((resolve, reject) => {
        app._get("web/organization/getList", {}, (res) => {
          // console.log(res,988)
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.org_id,
                name: i.org_name,
                data: i
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.organization.name = '仓库'
    }
    //会员列表
    if (!arr || arr.includes('member')) {
      list.member = await new Promise((resolve, reject) => {
        app._get("web/MemberBase/getListData", {}, (res) => {
          // console.log(res,988)
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(res.data.data);
        },()=>{},()=>{},0,1);
      })
      list.member.name = '会员'
    }
    //商品分类 
    if (!arr || arr.includes('category')) {

      list.category = await new Promise((resolve, reject) => {
        app._get('web/Category/loadCategory', {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.category_id,
                name: i.category_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.category.name = '商品分类'
    }
    //客户
    if (!arr || arr.includes('client')) {

      list.client = await new Promise((resolve, reject) => {
        app._get("web/client/getList", {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          //  console.log(res,988)
          resolve(
            res.data.map(i => {
              return {
                id: i.client_id,
                name: i.client_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.client.name = '客户'
    }
    //销售员列表
    if (!arr || arr.includes('user')) {

      list.user = await new Promise((resolve, reject) => {
        app._get("web/user/getList", {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.user_id,
                name: i.user_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.user.name = '销售员'
    }
    //尺码
    if (!arr || arr.includes('size')) {

      list.size = await new Promise((resolve, reject) => {
        app._get('web/size/loadSizeList', {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          //  console.log(res,988)
          resolve(
            res.data.map(i => {
              return {
                id: i.size_id,
                name: i.size_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.size.name = '尺码'
    }
    //色码
    if (!arr || arr.includes('color')) {

      list.color = await new Promise((resolve, reject) => {
        app._get('web/color/loadColorList', {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          //  console.log(res,988)
          resolve(
            res.data.map(i => {
              return {
                id: i.color_id,
                name: i.color_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.color.name = '色码'
    }
    //发货方式
    if (!arr || arr.includes('despatch')) {
      list.despatch = await new Promise((resolve, reject) => {
        app._get('/web/dict/getDeliveryList', {}, (res) => {
          loading_count--;
          if(loading_count<=0){
            wx.hideLoading({
              success: (res) => {},
            })
          }
          resolve(
            res.data.map(i => {
              return {
                id: i.dict_id,
                name: i.dict_name
              }
            }));
        },()=>{},()=>{},0,1);
      })
      list.despatch.name = '发货方式'
    }

    return list
  }













}