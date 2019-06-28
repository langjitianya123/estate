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

class Article extends Model {

    // 表名,不含前缀
    public $name = 'article';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'publish_time';

    // 增删改查时必需的字段，为空则不作限制
    public $requiredField = array(
        'find' => array('id' => 'id'),
        'list' => array(),
        'add' => array('cid' => '所属分类','title' => '标题'),
        'update' => array('cid' => '所属分类','title' => '标题'),
        'delete' => array('id' => 'id')
    );

    public function category(){
        return $this->belongsTo('Category','cid','id');
    }
}