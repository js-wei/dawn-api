<?php
// +----------------------------------------------------------------------
// |  User: jswei  |  Email:524314430@qq.com  | Time:2017/3/9 15:10
// +----------------------------------------------------------------------
// | TITLE: this to do?
// +----------------------------------------------------------------------


namespace DawnApi\auth;

class BaseAuth
{
    /**
     * 执行授权验证
     * @param $auth
     * @return mixed
     */
    public static function auth($auth)
    {
        $request = app('request');
        return $auth->authenticate($request);
    }


}