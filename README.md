# PSI服装零售小程序

#### 介绍
PSI服装零售是一个集采购、销售、库存、成本核算、核心业务、账务协同应用功能于一体的服装类进销存小程序。分为小程序psi，接口wxpsi两部分。PSI服装零售小程序以销售企业的核心业务：采购-销售-库存-财务为切入点，着手打造移动便捷式管理平台。整个系统一体化设计，功能流畅，操作方便，界面美观友好，是您商贸管理的好帮手！



#### 演示
 小程序：请搜索《psi服装零售》

 体验账号：admin123

 体验密码：123456


####  **PSI技术架构** 

数据库：MySQL 5.5+

运行环境：开源版PHP7+

后端框架：ThinkPHP5+

缓存：Redis

小程序框架：ColorUi

#### 使用说明
1、本项目使用的是.env文件的配置文件来连接数据库，在wxpai目录根目录的.env文件中，需要自行搭建环境导入数据库(wxpsi/sql/psi.sql)并修改配置(wxpsi/.env)文件；

2、本系统分两部分，分别是psi小程序，wxpsi接口两部分。wxpsi目录是小程序的接口文件，部署之后修改小程序（psi）的基础配置文件中（siteinfo.js）的接口请求地址为wxpsi的域名地址

3、本系统需要开启Redis，需要自行搭建Redis环境并且修改（wxpsi/.env）文件中Redis配置项

4、本系统目前是初始版本，后期会持续更新、完善、优化。

#### 功能清单

![微信图片_20210902162249](https://user-images.githubusercontent.com/67311804/131936724-d1ecb82d-dcc3-416c-b07b-3a239efeaa1f.png)

#### 代码仓库

本系统分为PSI服装零售小程序和PSI服装零售管理系统，如有需求请自行选择下载

1、PSI服装零售小程序

演示：小程序请搜索《psi服装零售》

GitHub： https://github.com/17692105230/psi.git

Gitee: https://gitee.com/nikegit/psi.git

2、PSI服装零售管理系统

演示： http://psi.100wi.cn/

GitHub： https://github.com/17692105230/psi-web.git

Gitee: https://gitee.com/nikegit/psi-web.git

#### 开源版使用须知
1、遵循GPL-3.0开源协议发布

2、允许用于个人学习、毕业设计、教学案例、公益事业

3、如果商用必须保留版权信息，并联系版主，请自觉遵守

4、禁止将本项目的代码和资源进行任何形式的出售，产生的一切责任由侵权者承担

#### 技术支持请联系

QQ群：619082556

![微信图片_20210901160410](https://user-images.githubusercontent.com/67311804/131639505-6821beb1-bfc7-4461-82c8-f932e04a543f.jpg)
