<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User:jswei  |  Email:524314430@qq.com  | Time:2017/3/11 10:56
// +----------------------------------------------------------------------
// | TITLE: 发送响应
// +----------------------------------------------------------------------
namespace DawnApi\facade;

use think\facade\Response;
use think\response\Redirect;

trait Send
{
    protected $restDefaultType = 'json';
    /**
     * 设置响应类型
     * @param null $type
     * @return $this
     */
    public function setType($type = null)
    {
        $this->type = (string)(!empty($type)) ? $type : $this->restDefaultType;
        return $this;
    }

    /**
     * 失败响应
     * @param int $status
     * @param string $message
     * @param int $code
     * @param array $data
     * @param array $headers
     * @param array $options
     * @return Response|\think\response\Json|\think\response\Jsonp|Redirect|\think\response\View|\think\response\Xml
     */
    public function sendError($status = 0, $message = 'error', $code = 200, $data = [], $headers = [], $options = [])
    {
        $responseData['status'] = $status?(int)$status:0;
        $responseData['message'] = (string)$message;
        if (!empty($data) && is_array($data)) {
            $responseData['data'] = $data;
        }elseif (!empty($data) && is_object($data)){
            $responseData = $data->toArray();
        }else{
            $responseData['data'] = $data;
        }
        $responseData = array_merge($responseData, $options);
        return $this->response($responseData, $code, $headers,$options);
    }

    /**
     * 成功响应
     * @param array $data
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param array $options
     * @return Response|\think\response\Json|\think\response\Jsonp|Redirect|\think\response\Xml
     */
    public function sendSuccess($data = [], $message = 'success', $code = 200, $headers = [], $options = []){
        $responseData['status'] = 1;
        $responseData['message'] = (string)$message;
        if (!empty($data) && is_array($data)) {
            $responseData['data'] = $data;
        }elseif (!empty($data) && is_object($data)){
            $responseData['data'] = $data->toArray();
        }else{
            $responseData['data'] = $data;
        }
        $responseData = array_merge($responseData, $options);
        return $this->response($responseData, $code, $headers,$options);
    }

    /**
     * 重定向
     * @param $url
     * @param array $params
     * @param int $code
     * @param array $with
     * @return Redirect
     */
    public function sendRedirect($url, $params = [], $code = 302, $with = [])
    {
        $response = new Redirect($url);
        if (is_integer($params)) {
            $code = $params;
            $params = [];
        }
        $response->code($code)->params($params)->with($with);
        return $response;
    }

    /**
     * 响应
     * @param $responseData
     * @param $code
     * @param $headers
     * @param $options
     */
    public function response($responseData, $code, $headers,$options)
    {
        if (!isset($this->type) || empty($this->type)) $this->setType();
        return Response::create($responseData,$this->type,$code,$headers,$options)->send();
    }
}