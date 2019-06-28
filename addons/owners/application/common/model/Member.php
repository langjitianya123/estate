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

class Member extends Model {

    // 表名,不含前缀
    public $name = 'member';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    public function community(){
        return $this->belongsTo('Community','community_code','code');
    }

    public function house(){
        return $this->belongsTo('House','house_code','code');
    }

    /**
     * 根据小区编号，获取房产列表
     * @param $cmCode
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getHouseByCMCode($cmCode) {
        $result = $this->where(array('community_code'=>$cmCode))->field('code,name')->select();
        return $result;
    }

    /**
     * 根据小区编号，获取人员列表
     * @param $cmCode
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMemberByCMCode($cmCode) {
        $result = $this->where(array('community_code'=>$cmCode))->field('id,name')->select();
        return $result;
    }

    /**
     * 根据小区编号，获取人员id
     * @param $cmCode
     * @return array
     */
    public function getMemberIdByCMCode($cmCode) {
        $result = $this->where(array('community_code'=>array('in',$cmCode)))->column('id');
        return $result;
    }

    /**
     * 判断指定的字段值是否已存在
     * @param $key 待比对的数据库表字段名称
     * @param $value 表字段值
     * @param null $id 用户id，为空时，表示新记录，否则为已有记录
     * @return bool
     */
    public function checkExists($key,$value,$id=null){
        $where = array(
            $key => $value
        );
        if ($id) {
            $rs = $this->where(array('id'=>$id))->find();
            //修改后的字段值如果与原值一致，忽略
            if ($rs[$key] == $value) {
                return false;
            }
        }
        $count = $this->where($where)->count();
        return $count>0 ? true : false;
    }

}