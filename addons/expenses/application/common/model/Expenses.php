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

class Expenses extends Model {

    // 表名,不含前缀
    public $name = 'expenses';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    public function community(){
        return $this->belongsTo('Community','community_code','code');
    }

    public function project(){
        return $this->belongsTo('ExpensesProject','project_code','code');
    }

    public function house(){
        return $this->belongsTo('House','house_code','code');
    }
}