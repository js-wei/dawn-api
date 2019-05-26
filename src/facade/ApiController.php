<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// |  User:jswei  |  Email:524314430@qq.com  | Time:2017/3/9 11:39
// +----------------------------------------------------------------------
// | TITLE: API基础
// +----------------------------------------------------------------------
namespace DawnApi\facade;

use think\facade\Config;
use think\facade\App;
use think\facade\Request;
use think\Exception;
use DawnApi\contract\AuthContract;
use think\exception\HttpResponseException;
use DawnApi\exception\UnauthorizedException;
use think\facade\Env;

abstract class ApiController
{
    use Send;
    public static $app;
    /**
     * 默认关闭验证
     * @var bool
     */
    public $apiAuth = false;
    /**
     * 当前资源类型
     * @var
     */
    protected $type;
    /**
     * 当前请求类型
     * @var
     */
    protected $method; // 当前请求类型
    /**
     * REST 操作
     * @var array
     */
    protected $restActionList = ['index', 'create', 'save', 'read', 'edit', 'update', 'delete'];
    /**
     * 附加方法
     * @var array
     */
    protected $extraActionList = [];
    /**
     * 跳过验证方法
     * @var array
     */
    protected $skipAuthActionList = [];
    /**
     * REST 请求
     * @var string
     */
    protected $restMethodList = 'get|post|put|delete';
    /**
     * TYPE 类型
     * @var string
     */
    protected $restTypeList = 'html|xml|json|rss';
    /**
     * 默认请求方式
     * @var string
     */
    protected $restDefaultMethod = 'get';
    /**
     * REST允许输出的资源类型列表
     * @var array
     */
    protected $restOutputType = [
        'json' => 'application/json',
        'xml' => 'application/xml',
        'jsonp' => 'application/jsonp',
    ];
    private $className=null;

    /**
     * ApiController constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->_execExtraAction();
        $this->_init();
    }
    /**
     * 初始化操作
     */
    private function _init()
    {
        // 资源类型检测
        $request = request();
        $ext = Request::ext();
        $this->className = $request->controller();
        //p($request);die;
        if ('' == $ext) {
            // 自动检测资源类型
            $this->type = $request->type();
        } elseif (!preg_match('/\(' . $this->restTypeList . '\)$/i', $ext)) {
            // 资源类型非法 则用默认资源类型访问
            $this->type = $this->restDefaultType;
        } else {
            $this->type = $ext;
        }
        $this->type = (in_array($this->type, array_keys($this->restOutputType))) ? $this->type : $this->restDefaultType;
        //p($this->type);die;
        // 请求方式检测
        $method = strtolower($request->method());

        if (false === stripos($this->restMethodList, $method)) {
            // 请求方式非法 则用默认请求方法
            $method = $this->restDefaultMethod;
        }
        $this->method = $method;
        $this->_execAuth();
    }

    /**
     * 加载扩展函数
     */
    private function _execExtraAction(){
        $metnods = get_class_methods($this);
        foreach ($metnods as $k=>$v){
            if(in_array($v,$this->restActionList)){
                continue;
            }
            if(in_array($v,$this->extraActionList)){
                continue;
            }
            if(strpos($v,'_')===false
                && strpos($v,'set')===false
                && strpos($v,'get')===false
                && $v!='sendError'
                && $v!='sendSuccess'
                && $v!='sendRedirect'
                && $v!='response'
            ){
                array_push($this->extraActionList,humpToLine($v));
                //$this->_getDocComment($v);
            }
        }
    }

    /**
     * 鉴权
     * @throws UnauthorizedException
     */
    private function _execAuth(){
        //是否跳过验证
        $method = request()->action();
        $isSkipAuth = false;
        array_map(function ($item) use ($method, &$isSkipAuth) {
            $isSkipAuth = (strtolower($item) == strtolower($method)) ? true : $isSkipAuth;
        }, $this->skipAuthActionList);

        if ($isSkipAuth){
          return;
        };
        $this->_register();
        if (self::_getConfig('api_debug')) {
            if (self::_getConfig('api_auth') && $this->apiAuth) {
                $auth = self::_auth();
            } else {
                $auth = true;
            }
            if ($auth !== true) throw new UnauthorizedException();
            //执行操作
        } else {
            try {
                /**
                 * 配置开启并且控制器开启后执行验证程序
                 * 认证授权通过  return true,
                 * 不通过可返回 return false or throw new UnauthorizedException
                 */
                //认证
                $auth = (self::_getConfig('api_auth') && $this->apiAuth) ? self::_auth() : true;
                if ($auth !== true){
                    $msg =  Config::get('app.lang_switch_on')?lang('unauthorized'):'Unauthorized';
                    throw new UnauthorizedException($msg);
                }
            } catch (UnauthorizedException $e) {
                //授权认证失败
                throw  new HttpResponseException($this->sendError(401, lang('authentication'), 401, [], $e->getHeaders()));
            } catch (Exception $e) {
                $msg =  Config::get('app.lang_switch_on')?lang('error',[lang('server')]):$e->getMessage();
                throw  new HttpResponseException($this->sendError(500, $msg, 500));
            }
        }
    }

    /**
     * 注册必要
     */
    private function _register(){
        //初始化配置
        self::_getConfig();
        //授权器
        if (self::_getConfig('api_auth') && $this->apiAuth) self::_getAuth();
    }

    /**
     * 获取授权
     * @return mixed
     * @throws \ReflectionException
     */
    private static function _getAuth(){
        if (!isset(self::$app['auth']) || !self::$app['auth']) {
            $auth = self::_getConfig('auth_class');

            //支持数组配置
            //判断是否实现验证接口
            if (((new \ReflectionClass($auth))->implementsInterface(AuthContract::class))){
                self::$app['auth'] = Factory::getInstance($auth);
            }
        }
        return self::$app['auth'];
    }

    /**
     * 授权验证
     * @return mixed
     * @throws Exception
     * @throws UnauthorizedException
     */
    private static function _auth(){
        $baseAuth = Factory::getInstance(\DawnApi\auth\BaseAuth::class);
        try {
            return $baseAuth->auth(self::$app['auth']);
        } catch (UnauthorizedException $e) {
            throw  new UnauthorizedException($e->authenticate, $e->getMessage());
        } catch (Exception $e) {
            throw  new  Exception(lang('error',[lang('serve'),'']), 500);
        }
    }

    /**
     * 获取配置信息
     * @param null $keys
     * @return mixed
     */
    private static function _getConfig($keys = null){
        if (!self::$app['config']) self::_registerConfig();
        return ($keys == null) ? self::$app['config'] : self::$app['config'][$keys];
    }

    /**
     * 注册配置信息
     */
    private static function _registerConfig(){
        $path = realpath(Env::get('config_path') . 'api.php');
        $api = is_array(Config::get('api.')) ? Config::get('api.') : [];
        self::$app['config'] = array_merge(require $path, $api);
    }

    /**
     * REST 调用
     * @param $method
     * @throws Exception
     */
    public function _empty($method){
        if (method_exists($this, $method . '_' . $this->method . '_' . $this->type)) {
            // RESTFul方法支持
            $fun = $method . '_' . $this->method . '_' . $this->type;
        } elseif ($this->method == $this->restDefaultMethod && method_exists($this, $method . '_' . $this->type)) {
            $fun = $method . '_' . $this->type;
        } elseif ($this->type == $this->restDefaultType && method_exists($this,$this->method)) {
            $fun = $this->method;
        } elseif (method_exists($this,$this->method)) {
            $fun = $this->method;
        }else{
            $fun = 'index';
        }
        if (isset($fun)) {
            App::instance()
                ->invokeMethod([$this,$fun],Request::routeInfo()['var']);
        } else {
            // 抛出异常
            $msg = lang('error',[lang('method'),'']);
            throw  new HttpResponseException($this->sendError(500, $msg . $this->method, 500, []));
        }
    }
}