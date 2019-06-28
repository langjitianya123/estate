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

class Duty extends Model {
    // 表名,不含前缀
    public $name = 'duty';

    public function community(){
        return $this->belongsTo('Community','community_code','code');
    }
}