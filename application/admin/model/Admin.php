<?php

namespace app\admin\model;

use think\Model;
use think\Session;

class Admin extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 重置用户密码
     * @author baiyouwen
     */
    public function resetPassword($uid, $NewPassword)
    {
        $passwd = $this->encryptPassword($NewPassword);
        $ret = $this->where(['id' => $uid])->update(['password' => $passwd]);
        return $ret;
    }

    // 密码加密
    protected function encryptPassword($password, $salt = '', $encrypt = 'md5')
    {
        return $encrypt($password . $salt);
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
