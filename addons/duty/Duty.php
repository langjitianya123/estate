<?php

namespace addons\duty;

use app\common\library\Menu;
use think\Addons;

/**
 * 值班管理插件
 */
class Duty extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'duty/index',
                'title'   => '值班管理',
                'remark'  => '用于管理小区物业的员工值班情况',
                'icon'    => 'fa fa-calendar',
                'sublist' => [
                    ['name' => 'duty/index/index', 'title' => '查看'],
                    ['name' => 'duty/index/add', 'title' => '添加值班人员'],
                    ['name' => 'duty/index/edit', 'title' => '修改值班人员'],
                    ['name' => 'duty/index/del', 'title' => '删除值班人员'],
                ],
                'weigh'   => 193
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete('duty/index');
        return true;
    }

}
