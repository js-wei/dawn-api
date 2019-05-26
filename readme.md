Dawn-Api 
===============
[![Latest Stable Version](https://poser.pugx.org/liushoukun/dawn-api/v/stable)](https://packagist.org/packages/liushoukun/dawn-api)
[![Total Downloads](https://poser.pugx.org/liushoukun/dawn-api/downloads)](https://packagist.org/packages/liushoukun/dawn-api)
[![Latest Unstable Version](https://poser.pugx.org/liushoukun/dawn-api/v/unstable)](https://packagist.org/packages/liushoukun/dawn-api)
[![License](https://poser.pugx.org/liushoukun/dawn-api/license)](https://packagist.org/packages/liushoukun/dawn-api)
[![Monthly Downloads](https://poser.pugx.org/liushoukun/dawn-api/d/monthly)](https://packagist.org/packages/liushoukun/dawn-api)


## 说明
ThinkPHP5编写的restful风格的API，集API请求处理，权限认证，自动生成文档等功能；

 - restful风格处理请求
 > 每个接口对于一个控制器，method对应[method]方法响应

 - 权限认证
 > Basic,Oauth Client Credentials Grant
 
 - 文档生成
 > 简洁，优雅，不需要额外的文档工具;

 - 包已经进行了ThinkPHP5.1.*的适配
 > 已经对包进行了ThinkPHP5.1.*的适配,并且将token生成改变了机制(原先的生成方式已被废弃)
 ,并且引入了注解路由进行文档的生成.更方便了使用
 
 - 关于dawn-api说明
 1. 为了方便使用这里讲修改过的dawn-api也提交到项目中了
 2. 原先下载是的是没有适配后的dawn-api所以不支持ThinkPHP5.1.*
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

## v0.0.4版本
 1. 修复ThinkPHP更新出现的BUG
 2. 生成文档API优化

## 功能增强

### 关于dawn-api更改的部分

> 这里再次对dawn-api进行了改动,下面就更改的一些地方进行说明

1. 优化并使用了自动加载扩展函数,解放了每次都要手动在`extraActionList`书写的问题
2. Wiki文档可以使用注解进行标记了,解放了需要在`getRules()`中写参数才能显示的问题,当然,只是做优化,您还可以写在里面,在这里我主要使用注解方式.下面是注解的用法,以login为例:
   
   ~~~
   /**
         * @title 用户登录
         * @method login 
         * @param string $phone 账号 true
         * @param string $password 密码 true md5
         * @route('v1/user/login')
         * @return Object User 用户信息
       */
    ~~~
    
   * @title 接口名称
   * @method 注解的方法,表现为Wiki方法`login`的接口参数说明
   * @param 参数列表,以一个空格隔开.形如:[数据类型 形参 参数名称 是否必须 备注说明 取值范围]
   * @route 注解路由,在wiki的表现形式是将路由显示在方法后面
   * @return 返回的数据类型,以一个空格隔开.形如:[数据类型 参数名称 备注说明]
   * 完成后的效果:
  
   ![注解显示效果](https://www.image.jswei.cn/dawn/login.png)

3. 配合使用ThinkPHP5.1.*的注解路由更家灵活多变,上面的例子就用到了注解路由,更多有关于注解路由[请参考](https://www.kancloud.cn/manual/thinkphp5_1/469333)

 ### 关于dawn-api说明
 
 1. 为了方便使用这里讲修改过的dawn-api也提交到项目中了
 2. 原先下载是的是没有适配后的dawn-api所以不支持ThinkPHP5.1.*
 3. 关于其他的配置以及wiki的配置,请参见dawn-api的说明
 4. 修改的不太成熟,如有问题请提出
 
 ### 新增了命令行工具
 
 >为了方便使用在这里新增了命令行工具,使用命令行工具可以快速的创建API控制器,下面做简单的介绍
 
 1. 进入项目目录后,输入`php ./think`可以查看可以使用的命令行,看到了`api`,就表示可以使用提供的命令行工具了,显示如图:
 
 ![命令行](https://www.image.jswei.cn/dawn/line01.png)
 
 2. 使用命令`php ./think api -s first -c news`创建一个命名空间为`first`
 名称为`news`的控制器,运行命令后出现了`Success`就创建成功了.如图:
 
 ![wiki文档](https://www.image.jswei.cn/dawn/line02.png)
 
 3. 命令执行成功后你会看到在项目中`application\first\controller`中多出了一个名为`News.php`的文件,这个就是为你生成的一些方法.
 
 4. 在生成控制器的同时还在配置文件`api_doc.php`生成了相应的文档的相关配置,如图:
 
 ![wiki文档](https://www.image.jswei.cn/dawn/line03.png)
 
 5. 打开wiki这时就可以看见基本的相关api的文档说明,如图:
 
 ![wiki文档](https://www.image.jswei.cn/dawn/line04.png)
 
 6. 这时候点击api地址可以测试是否成功(这里使用的是BaseAuth,浏览器提示登录窗口).如图:
 
 ![wiki文档](https://www.image.jswei.cn/dawn/line05.png)
 
 > 需要注意的是因为开启了注解路由,创建成功之后最好执行路由生成工具,重新生成路由.
 
 ### api命令号的说明
 
 > 下面主要介绍一下提供的参数,方便您使用
 
 1. 首先,你可以使用`php ./think api -h`查看帮助,您将得到的结果如下:
 
 ![wiki文档](https://www.image.jswei.cn/dawn/line06.png)
 
 * -s or --namespace 生成的控制器的命名空间
 * -c or --controller 生成的控制器的名称
 * -i or --id 生成的文档配置的id,默认是根据原配置一次增加
 * -p or --parent 作为一个文档的子节点的父亲id,默认是0顶级节点
 * 看一些列子
 
 1. ` php ./think api -s first -c sub -p 5 #为id为5的创建一个字文档,id自增`  
 2. `php ./think api -s first -c sub1 -i 8 #创建一个id为8的文档节点,父级为顶级`
 3. `php ./think api -s first -c sub2 -i 9 -p 5 #创建一个id为5的创建一个id为9的子级文档节点`
 
 运行成功后:
 
 ![wiki文档02](https://www.image.jswei.cn/dawn/line07.png)
 
 ![wiki文档03](https://www.image.jswei.cn/dawn/line08.png)
 
 ![wiki文档04](https://www.image.jswei.cn/dawn/line09.png)
 
 ![wiki文档04](https://www.image.jswei.cn/dawn/line10.png)
 
### 在线的例子

[在线示例WIKI](http://api.jswei.cn/wiki)

[Postman接口文档](https://documenter.getpostman.com/view/4206182/RW1dHKCt)
