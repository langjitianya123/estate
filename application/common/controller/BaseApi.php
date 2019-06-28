<?php
/**
 * Created by PhpStorm.
 * FileName: BaseApi.php
 * User: Administrator
 * Date: 2017/10/28
 * Time: 10:35
 */

namespace app\common\controller;


use app\api\library\ConstStatus;
use think\Exception;
use think\Request;
use think\Response;

class BaseApi {

    protected $allowMethod = null;

    public function __construct(Request $request) {
        error_reporting(0);
        $this->validateAllowMethod($request);
    }

    public function _empty(Request $request) {
        return $this->response(null,ConstStatus::CODE_ERROR,sprintf('无效的操作 - %s',$request->action()));
    }

    /**
     * 输出
     * @param string $data 输出数据
     * @param string $code 状态码
     * @param string $desc 描述
     * @param string $type 输出类型|json|jsonp|redirect|view|xml
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function response($data = '', $code='', $desc='', $type = 'json') {
        $response = array(
            'data' => $data,
            'code' => $code,
            'desc' => $desc
        );
        return Response::create($response, $type);
    }

    /**
     * 获取自增的主键id
     * @param $model
     * @param string $pk
     * @return mixed
     */
    public function getAutoId($model,$pk='id') {
        return $model->$pk;
    }

    /**
     * 通用查询参数构造
     * @return array
     */
    protected function buildParams() {
        $request = Request::instance();
        //过滤条件 - 精确匹配
        //参数形式 filter[参数名]=参数值
        $where = (array)$request->request("filter/a");
        //排序
        //参数形式 sort[参数名]=参数值
        $order = (array)$request->request("sort/a");
        foreach ($where as $k => $v) {
            if (filter_var($k,FILTER_VALIDATE_INT) !== false) {
                unset($where[$k]);
            }
        }
        foreach ($order as $k => $v) {
            if (filter_var($k,FILTER_VALIDATE_INT) !== false) {
                unset($order[$k]);
            }
        }
        return array($where, $order);
    }

    /**
     * 验证必需字段
     * @param $requiredField
     * @param $params
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    protected function validateField($requiredField,$params) {
        $result = '';
        foreach ($requiredField as $key => $item) {
            if (array_key_exists($key,$params) === false || !$params[$key]) {
                $result = $item;
                break;
            }
        }
        if ($result) {
            $response = array(
                'data' => null,
                'code' => ConstStatus::CODE_ERROR,
                'desc' => sprintf('%s不能为空',$result)
            );
            die(json($response)->send());
        }
    }

    /**
     * 验证当前请求是否允许
     * @param Request $request
     * @return bool
     */
    protected function validateAllowMethod(Request $request) {
        $method = $request->method();
        if ($this->allowMethod && !in_array(strtolower($method),$this->allowMethod)) {
            $response = array(
                'data' => null,
                'code' => ConstStatus::CODE_ERROR,
                'desc' => sprintf('非法请求 - %s',$method)
            );
            die(json($response)->send());
        }
    }
}