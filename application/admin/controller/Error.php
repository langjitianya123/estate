<?php
/**
 * Created by PhpStorm.
 * FileName: Tets.php
 * User: Administrator
 * Date: 2017/10/24
 * Time: 14:03
 */

namespace app\admin\controller;

use app\common\controller\Backend;

class Error extends Backend {

    public function index() {
        $this->error(__('The controller does not exist'));
    }
}