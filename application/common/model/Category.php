<?php

namespace app\common\model;

use fast\Tree;
use think\Model;

/**
 * 分类模型
 */
class Category Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
        'type_text',
        'flag_text',
    ];

    // 增删改查时必需的字段，为空则不作限制
    public $requiredField = array(
        'find' => array('id' => 'id'),
        'list' => array(),
        'add' => array('name' => '分类名称'),
        'update' => array('name' => '分类名称'),
        'delete' => array('id' => 'id')
    );

    /**
     * 读取分类类型
     * @return array
     */
    public static function getTypeList()
    {
        $typeList = config('site.categorytype');
        foreach ($typeList as $k => &$v)
        {
            $v = __($v);
        }
        return $typeList;
    }

    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getFlagList()
    {
        return ['hot' => __('Hot'), 'index' => __('Index'), 'recommend' => __('Recommend')];
    }

    public function getFlagTextAttr($value, $data)
    {
        $value = $value ? $value : $data['flag'];
        $valueArr = explode(',', $value);
        $list = $this->getFlagList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }

    /**
     * 读取分类列表
     * @param string $type 指定类型
     * @param string $status 指定状态
     * @return array
     */
    public static function getCategoryArray($type = NULL, $status = NULL)
    {
        $list = collection(self::where(function($query) use($type, $status) {
                    if (!is_null($type))
                    {
                        $query->where('type', '=', $type);
                    }
                    if (!is_null($status))
                    {
                        $query->where('status', '=', $status);
                    }
                })->order('weigh', 'desc')->select())->toArray();
        return $list;
    }

    /**
     * @return array获取分类树型结构数据
     */
    public function getTreeCategory() {
        $tree = Tree::instance();
        $tree->init($this->order('weigh desc,id desc')->select(), 'pid');
        $categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        return $categorydata;
    }

}
