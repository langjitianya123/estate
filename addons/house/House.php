<?php

namespace addons\house;

use app\common\library\Menu;
use think\Addons;

/**
 * 房产信息管理插件
 */
class House extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'house',
                'title'   => '房产信息管理',
                'icon'    => 'fa fa-home',
                'sublist' => [
                    [
                        'name'    => 'house/index',
                        'title'   => '房产管理',
                        'remark'  => '用于管理小区里每一户的房产基本信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'house/index/index', 'title' => '查看'],
                            ['name' => 'house/index/add', 'title' => '添加'],
                            ['name' => 'house/index/edit', 'title' => '修改'],
                            ['name' => 'house/index/del', 'title' => '删除'],
                        ],
                        'weigh'   => 2
                    ],
                    [
                        'name'    => 'house/building',
                        'title'   => '栋数管理',
                        'remark'  => '用于管理小区里每一栋住宅的基础信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'house/building/index', 'title' => '查看'],
                            ['name' => 'house/building/add', 'title' => '添加'],
                            ['name' => 'house/building/edit', 'title' => '修改'],
                            ['name' => 'house/building/del', 'title' => '删除'],
                        ],
                        'weigh'   => 1
                    ],
                ],
                'weigh'   => 199
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
        Menu::delete('house');
        return true;
    }

}
