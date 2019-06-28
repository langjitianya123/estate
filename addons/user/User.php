<?php

namespace addons\user;

use think\Addons;

/**
 * 会员中心插件
 */
class User extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        \app\common\library\Menu::create([
            [
                'name'    => 'user',
                'title'   => '会员管理',
                'icon'    => 'fa fa-user-o',
                'remark'  => '用于管理前台注册的会员',
                'sublist' => [
                    ['name' => 'user/index', 'title' => '查看'],
                    ['name' => 'user/edit', 'title' => '修改'],
                    ['name' => 'user/del', 'title' => '删除'],
                    ['name' => 'user/multi', 'title' => '批量更新'],
                ]
            ]
        ]);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        \app\common\library\Menu::delete('user');
        return true;
    }

}
