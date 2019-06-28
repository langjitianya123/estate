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
use think\Session;

class Community extends Model {

    // 表名,不含前缀
    public $name = 'community';

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
        $codePrefix = 'CM';
        $defaultCode = sprintf('%s%05s',date('Ymd'),'1');
        $maxCode = $this->max('code',0,false);
        if ($maxCode) {
            $maxCode = str_replace($codePrefix,'',$maxCode)+1;
        } else {
            $maxCode = $defaultCode;
        }
        return sprintf('%s%s',$codePrefix,$maxCode);
    }

    /**
     * 根据权限获取小区id
     * @param $auth
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCommunityCodeByAuth($auth) {
        $admin = Session::get('admin');
        if($admin) {
            $userInfo = json_decode($admin,true);
            $userId = $userInfo['id'];
            if ($auth->checkIsSuperAdmin($userId)) {
                $result = $this->field('code')->select();
            } else {
                $result = model('CommunityAdmin')->where(array('admin_id'=>$userId))->field('community_code as code')->select();
            }
        }
        $cmIds = array();
        foreach ($result as $rs) {
            $cmIds[] = $rs['code'];
        }
        return $cmIds;
    }
}