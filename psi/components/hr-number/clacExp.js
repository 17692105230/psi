class Stack {
  constructor() {
    this._items = []; // 储存数据
  }
  // 向栈内压入一个元素
  push(item) {
    this._items.push(item);
  }
  // 把栈顶元素弹出
  pop() {
    return this._items.pop();
  }
  // 返回栈顶元素
  peek() {
    return this._items[this._items.length - 1];
  }
  // 判断栈是否为空
  isEmpty() {
    return !this._items.length;
  }
  // 栈元素个数
  size() {
    return this._items.length;
  }
  // 清空栈
  clear() {
    this._items = [];
  }
}
// 将普通算数表达式即中缀表达式转换为逆波兰表达式即后缀表达式
function rp(str) {
  var arr = str.split('');
  var ops = '+-#*/'.split(''); // #用来分级，+-是同一级，*/同一级，两级之间的位置差至少为2
  var result = [], temp = [];
  arr.forEach(function(ele, ind) {
      if (ele == '(') {
          temp.push(ele); // 左括号直接推入暂存区
      } else if (ele == ')') {
          var flag = true;
          while (flag) {
              if (temp[temp.length-1] != '(') {
                  result.push(temp.pop())
              } else {
                  temp.pop();
                  flag = false;
              }
          }
      } else if (ops.indexOf(ele) != -1) {
          cb(ele, temp)
          function cb(x, o) {
              if (o.length == 0 || o[o.length-1] == '(' || 
                  ops.indexOf(x) - ops.indexOf(o[o.length-1]) > 2) { //判断分级
                  o.push(x)
              }  else {
                  result.push(o.pop());
                  return cb(x, o)
              }
          }
      } else {
          result.push(ele);
      }
  })
  while (temp.length > 0) {
      if(temp[temp.length-1] != '(') {
          result.push(temp.pop())
      } else {
          temp.pop()
      }
  }
  return result;
}
function isOperator(str) {
  return ['+', '-', '*', '/'].includes(str);
}
// 逆波兰表达式计算
function clacExp(exp) {
  const stack = new Stack();
  for (let i = 0; i < exp.length; i++) {
    const one = exp[i];
    if (isOperator(one)) {
      const operatNum1 = stack.pop();
      const operatNum2 = stack.pop();
      const expStr = `${operatNum2}${one}${operatNum1}`;
      const res = eval(expStr);
      stack.push(res);
    } else {
      stack.push(one);
    }
  }
  return stack.peek();
}

const result = clacExp(rp('(2*(3+4)+1)*4'));
console.log(result); // 60
