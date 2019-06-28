<?php
/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/26
 * Time: 8:46
 */

namespace addons\frontendnav\controller;

use think\addons\Controller;
use think\Db;

class Index extends Controller {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $list = array();
        $code = $this->request->request('code');
        $category = Db::name('front_nav_cate')->where(array('code'=>$code))->find();
        if(!$category) {
            $this->error("无效的导航标识");
        }
        $list = Db::name('front_nav')->where(array('cate_id'=>$category['id'],'status'=>1))->order('sort','asc')->select();
        if (!$list) {
            $this->error("没有找到可用的导航菜单");
        }
        //print_r($list);
        $this->view->assign('slide',$list);
        return $this->view->fetch();
    }

}