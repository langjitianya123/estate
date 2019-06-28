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

class SlideCategory extends Model {

    // 表名,不含前缀
    public $name = 'slide_category';

    public function slide(){
        return $this->hasMany('Slide','cid','id');
    }

    public function getCateByCode($code) {
        return $this->where(array('code'=>$code))->find();
    }
}