<?php

namespace addons\ucenter\model;

use think\Model;

/**
 * 会员模型
 */
class User Extends Model
{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    // 追加属性
    protected $append = [
    ];

    public static function register($username, $password, $email = '', $mobile = '', $extend = [], $keeptime = 0, $sync = TRUE)
    {
        
    }

    public static function login($account, $password, $keeptime = 0, $sync = TRUE)
    {
        
    }

    public static function logout($token = NULL)
    {
        return TRUE;
    }

    /**
     * 获取密码加密方式
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function getEncryptPassword($password, $salt = '')
    {
        return md5(md5($password) . $salt);
    }

}
