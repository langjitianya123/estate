<?php

namespace addons\qrcode;

use think\Addons;

/**
 * 二维码生成
 */
class Qrcode extends Addons
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
