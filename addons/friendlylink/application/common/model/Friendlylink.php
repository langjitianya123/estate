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

class Friendlylink extends Model {
    // 表名,不含前缀
    public $name = 'friendlylink';

    // 增删改查时必需的字段，为空则不作限制
    public $requiredField = array(
        'find' => array('id' => 'id'),
        'list' => array(),
        'add' => array('name' => '友情链接名称'),
        'update' => array('name' => '友情链接名称'),
        'delete' => array('id' => 'id')
    );
}