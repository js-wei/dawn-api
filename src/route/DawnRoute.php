<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User:jswei  |  Email:524314430@qq.com  | Time:2017/9/26 16:18
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------
namespace DawnApi\route;

use think\App;
use think\Route;
use  DawnApi\controller\Wiki;

class DawnRoute{
    public static function wiki(){
        $pathInfo = \request()->pathinfo();
        if (false === stripos($pathInfo, 'wiki')) {
            // 请求方式非法 则用默认请求方法
            return;
        }
        Route::any('wiki/apiInfo', function () {
            $controller = new Wiki();
            return App::invokeMethod([$controller, 'apiInfo']);
        });
        Route::any('wiki', function () {
            $controller = new Wiki();
            return App::invokeMethod([$controller, 'index']);
        });
    }
}