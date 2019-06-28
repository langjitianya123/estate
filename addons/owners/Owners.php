<?php

namespace addons\owners;

use app\common\library\Menu;
use think\Addons;

/**
 * 业主信息管理插件
 */
class Owners extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'owners',
                'title'   => '业主信息管理',
                'icon'    => 'fa fa-user',
                'sublist' => [
                    [
                        'name'    => 'owners/index',
                        'title'   => '人员管理',
                        'remark'  => '用于管理小区里每个住户的基本信息，包括业主、家庭成员及租户等信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'owners/index/index', 'title' => '查看'],
                            ['name' => 'owners/index/add', 'title' => '添加'],
                            ['name' => 'owners/index/edit', 'title' => '修改'],
                            ['name' => 'owners/index/del', 'title' => '删除'],
                        ],
                        'weigh'   => 3
                    ],
                    [
                        'name'    => 'owners/vehicle',
                        'title'   => '车辆管理',
                        'remark'  => '用于管理小区里的车辆信息，包括业主、家庭成员及租户的车辆',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'owners/vehicle/index', 'title' => '查看'],
                            ['name' => 'owners/vehicle/add', 'title' => '添加'],
                            ['name' => 'owners/vehicle/edit', 'title' => '修改'],
                            ['name' => 'owners/vehicle/del', 'title' => '删除'],
                        ],
                        'weigh'   => 2
                    ],
                    [
                        'name'    => 'owners/pet',
                        'title'   => '宠物管理',
                        'remark'  => '用于管理小区里的宠物信息，包括业主、家庭成员及租户饲养的宠物',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'owners/pet/index', 'title' => '查看'],
                            ['name' => 'owners/pet/add', 'title' => '添加'],
                            ['name' => 'owners/pet/edit', 'title' => '修改'],
                            ['name' => 'owners/pet/del', 'title' => '删除'],
                        ],
                        'weigh'   => 1
                    ],
                ],
                'weigh'   => 198
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
        Menu::delete('owners');
        return true;
    }

}
