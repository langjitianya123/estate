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

class Device extends Model {
    // 表名,不含前缀
    public $name = 'device';

    public function community(){
        return $this->belongsTo('Community','community_code','code');
    }

    public function maintain(){
        return $this->hasMany('DeviceMaintain','device_code','code');
    }

    /**
     * 获取编号最大值
     * @return string
     */
    public function getMaxCode() {
        $codePrefix = 'DV';
        $defaultCode = sprintf('%s%05s',date('Ymd'),'1');
        $maxCode = $this->max('code',0,false);
        if ($maxCode) {
            $maxCode = str_replace($codePrefix,'',$maxCode)+1;
        } else {
            $maxCode = $defaultCode;
        }
        return sprintf('%s%s',$codePrefix,$maxCode);
    }
}