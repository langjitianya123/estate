<?php
/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/26
 * Time: 8:46
 */

namespace addons\expenses\controller;

use think\addons\Controller;

class Index extends Controller {

    public function index() {
        $this->error("当前插件暂无前台页面");
    }

}