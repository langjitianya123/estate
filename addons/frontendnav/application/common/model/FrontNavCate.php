<?php
/**
 * Created by PhpStorm.
 * FileName: Article.php
 * User: Administrator
 * Date: 2017/10/20
 * Time: 13:51
 */

namespace app\common\model;


use think\Model;

class FrontNavCate extends Model {
    // 表名,不含前缀
    public $name = 'front_nav_cate';

    public function getCateByCode($code) {
        return $this->where(array('code'=>$code))->find();
    }
}