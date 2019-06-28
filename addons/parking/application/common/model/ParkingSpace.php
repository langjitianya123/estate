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

class ParkingSpace extends Model {

    // 表名,不含前缀
    public $name = 'parking_space';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 获取编号最大值
     * @return string
     */
    public function getMaxCode() {
        $codePrefix = 'PK';
        $defaultCode = sprintf('%s%05s',date('Ymd'),'1');
        $maxCode = $this->max('code',0,false);
        if ($maxCode) {
            $maxCode = str_replace($codePrefix,'',$maxCode)+1;
        } else {
            $maxCode = $defaultCode;
        }
        return sprintf('%s%s',$codePrefix,$maxCode);
    }

    public function community(){
        return $this->belongsTo('Community','community_code','code');
    }

    /**
     * 根据小区编号，获取停车位列表
     * @param $cmCode
     * @param $status
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getParkingByCMCode($cmCode,$status = null) {
        $where = array(
            'community_code'=>$cmCode
        );
        if ($status !== null) {
            $where['status'] = $status;
        }
        $result = $this->where($where)->field('code,name')->select();
        return $result;
    }

}