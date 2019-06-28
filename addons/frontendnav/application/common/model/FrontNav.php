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
use fast\Tree;

class FrontNav extends Model {
    // 表名,不含前缀
    public $name = 'front_nav';

    // 增删改查时必需的字段，为空则不作限制
    public $requiredField = array(
        'find' => array('id' => 'id'),
        'list' => array('cate_id' => '导航分类'),
        'add' => array('cate_id' => '导航分类','name' => '导航名称'),
        'update' => array('cate_id' => '导航分类','name' => '导航名称'),
        'delete' => array('id' => 'id')
    );

    public function cate(){
        return $this->belongsTo('FrontNavCate','cate_id','id');
    }

    /**
     * @return array获取分类树型结构数据
     */
    public function getTreeCategory() {
        $tree = Tree::instance();
        $tree->init($this->with('cate')->order('sort asc,id desc')->select(), 'pid');
        $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        return $categorylist;
    }
}