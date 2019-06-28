<?php

namespace addons\device;

use app\common\library\Menu;
use think\Addons;

/**
 * 资产设备管理插件
 */
class Device extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'device/index',
                'title'   => '资产设备管理',
                'remark'  => '用于管理小区里的公共设备，比如电梯，路灯，垃圾桶，配电箱等',
                'icon'    => 'fa fa-legal',
                'sublist' => [
                    ['name' => 'device/index/index', 'title' => '查看'],
                    ['name' => 'device/index/add', 'title' => '添加'],
                    ['name' => 'device/index/edit', 'title' => '修改'],
                    ['name' => 'device/index/del', 'title' => '删除'],
                    ['name' => 'device/index/detail', 'title' => '查看详情'],
                ],
                'weigh'   => 195
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
        Menu::delete('device/index');
        return true;
    }

}
