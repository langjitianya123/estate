<?php
/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/26
 * Time: 9:15
 */
namespace addons\slide\controller;

use think\addons\Controller;
use think\Db;

class Index extends Controller {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $list = array();
        $code = $this->request->request('code');
        $category = Db::name('slide_category')->where(array('code'=>$code))->find();
        if(!$category) {
            $this->error("无效的轮播标识");
        }
        $list = Db::name('slide')->where(array('cid'=>$category['id'],'status'=>1))->order('sort','asc')->select();
        if (!$list) {
            $this->error("没有找到可用的轮播资源");
        }
        $this->view->assign('slide',$list);
        return $this->view->fetch();
    }

}