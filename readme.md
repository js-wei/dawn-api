Dawn-Api 
===============
[![Latest Stable Version](https://poser.pugx.org/liushoukun/dawn-api/v/stable)](https://packagist.org/packages/liushoukun/dawn-api)
[![Total Downloads](https://poser.pugx.org/liushoukun/dawn-api/downloads)](https://packagist.org/packages/liushoukun/dawn-api)
[![Latest Unstable Version](https://poser.pugx.org/liushoukun/dawn-api/v/unstable)](https://packagist.org/packages/liushoukun/dawn-api)
[![License](https://poser.pugx.org/liushoukun/dawn-api/license)](https://packagist.org/packages/liushoukun/dawn-api)
[![Monthly Downloads](https://poser.pugx.org/liushoukun/dawn-api/d/monthly)](https://packagist.org/packages/liushoukun/dawn-api)


## 说明
thinkphp5编写的restful风格的API，集API请求处理，权限认证，自动生成文档等功能；

 - restful风格处理请求
 > 每个接口对于一个控制器，method对应[method]方法响应

 - 权限认证
 > Basic,Oauth Client Credentials Grant
 
 - 文档生成
 > 简洁，优雅，不需要额外的文档工具;

 - 包已经进行了ThinkPHP5.1.X的适配
 > 已经对包进行了ThinkPHP5.1.X的适配,并且将token生成改变了机制(原先的生成方式已被废弃)
 ,并且引入了注解路由进行文档的生成.更方便了使用
 
 - 关于dawn-api说明
 1. 为了方便使用这里讲修改过的dawn-api也提交到项目中了
 2. 原先下载是的是没有适配后的dawn-api所以不支持ThinkPHP5.1.X
 3. 项目克隆下就可以使用了
 
## 安装
- 如果想在你的TP5项目中使用,那么可以直接使用
```
composer require jswei/dawn-api
```
- 如果要使用生成文档 需要在public/static/ 下 安装hadmin
```
cd /public/static/
git clone  https://github.com/js-wei/hadmin.git
```
## DEMO和例子
[DEMO](https://gitee.com/jswei/Thinkphp_restful_api.git)
[例子](http://api.jswei.cn/wiki)
