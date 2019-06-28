<?php

namespace addons\expenses;

use app\common\library\Menu;
use think\Addons;

/**
 * 收费项目管理插件
 */
class Expenses extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'expenses',
                'title'   => '收费管理',
                'icon'    => 'fa fa-usd',
                'sublist' => [
                    [
                        'name'    => 'expenses/index',
                        'title'   => '收费明细管理',
                        'remark'  => '用于管理小区里每一项收费的明细信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'expenses/index/index', 'title' => '查看'],
                            ['name' => 'expenses/index/add', 'title' => '添加'],
                            ['name' => 'expenses/index/edit', 'title' => '修改'],
                            ['name' => 'expenses/index/del', 'title' => '删除'],
                        ],
                        'weigh'   => 2
                    ],
                    [
                        'name'    => 'expenses/project',
                        'title'   => '收费项目管理',
                        'remark'  => '用于管理小区里的收费项目',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'expenses/project/index', 'title' => '查看'],
                            ['name' => 'expenses/project/add', 'title' => '添加'],
                            ['name' => 'expenses/project/edit', 'title' => '修改'],
                            ['name' => 'expenses/project/del', 'title' => '删除'],
                        ],
                        'weigh'   => 1
                    ],
                ],
                'weigh'   => 194
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
        Menu::delete('expenses');
        return true;
    }

}
