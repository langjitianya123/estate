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

class House extends Model {

    // 表名,不含前缀
    public $name = 'house';

    /**
     * 获取编号最大值
     * @return string
     */
    public function getMaxCode() {
        $codePrefix = 'HS';
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

    public function building(){
        return $this->belongsTo('Building','building_code','code');
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

}