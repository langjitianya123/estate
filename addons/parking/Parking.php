<?php

namespace addons\parking;

use app\common\library\Menu;
use think\Addons;

/**
 * 停车位管理插件
 */
class Parking extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'parking',
                'title'   => '停车位管理',
                'icon'    => 'fa fa-film',
                'sublist' => [
                    [
                        'name'    => 'parking/index',
                        'title'   => '车位管理',
                        'remark'  => '用于管理小区里每一个停车位的基本信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'parking/index/index', 'title' => '查看'],
                            ['name' => 'parking/index/add', 'title' => '添加'],
                            ['name' => 'parking/index/edit', 'title' => '修改'],
                            ['name' => 'parking/index/del', 'title' => '删除'],
                        ],
                        'weigh'   => 2
                    ],
                    [
                        'name'    => 'parking/usage',
                        'title'   => '车位使用管理',
                        'remark'  => '用于管理小区里每一个停车位的使用情况',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'parking/usage/index', 'title' => '查看'],
                            ['name' => 'parking/usage/add', 'title' => '添加'],
                            ['name' => 'parking/usage/edit', 'title' => '修改'],
                            ['name' => 'parking/usage/del', 'title' => '删除'],
                        ],
                        'weigh'   => 1
                    ],
                ],
                'weigh'   => 197
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
        Menu::delete('parking');
        return true;
    }

}
