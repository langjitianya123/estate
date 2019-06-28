<?php

namespace addons\third;

use think\Addons;

/**
 * 第三方登录
 */
class Third extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

}
