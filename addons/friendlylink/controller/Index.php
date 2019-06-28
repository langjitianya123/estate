<?php
/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/26
 * Time: 9:15
 */
namespace addons\friendlylink\controller;

use think\addons\Controller;
use think\Db;

class Index extends Controller {

    public function _initialize() {
        parent::_initialize();
    }

    public function index() {
        $result = Db::name('friendlylink')->where(array('status'=>1))->order('sort', 'asc')->select();
        $list = array();
        foreach ($result as $key => $item) {
            $list[$key] = $item;
            $link = $item['link'];
            if (strpos($link,'http://') !== 0 && strpos($link,'https://') !== 0 && strpos($link,'/') !== 0) {
                $link = url($link);
            }
            $list[$key]['link'] = $link;
        }
        $this->view->assign('friendlyLinks',$list);
        return $this->view->fetch();
    }

}