<?php
/**
 * Created by PhpStorm.
 * FileName: Tets.php
 * User: Administrator
 * Date: 2017/10/24
 * Time: 14:03
 */

namespace app\api\controller;

use app\api\library\ConstStatus;
use think\Response;
use think\Request;

class Error {

    public function index(Request $request) {
        $response = array(
            'data' => null,
            'code' => ConstStatus::CODE_ERROR,
            'desc' => sprintf('无效的请求 - %s',$request->controller())
        );
        return Response::create($response, 'json');
    }
}