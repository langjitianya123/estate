<?php

namespace addons\service;

use app\common\library\Menu;
use think\Addons;

/**
 * 服务管理插件
 */
class Service extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'service',
                'title'   => '服务管理',
                'icon'    => 'fa fa-coffee',
                'sublist' => [
                    [
                        'name'    => 'service/activity',
                        'title'   => '活动管理',
                        'remark'  => '用于管理小区里不定期举办的各种活动',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'service/activity/index', 'title' => '查看'],
                            ['name' => 'service/activity/add', 'title' => '添加'],
                            ['name' => 'service/activity/edit', 'title' => '修改'],
                            ['name' => 'service/activity/del', 'title' => '删除'],
                            ['name' => 'service/activity/detail', 'title' => '查看详情'],
                        ],
                        'weigh'   => 4
                    ],
                    [
                        'name'    => 'service/repair',
                        'title'   => '报修管理',
                        'remark'  => '用于管理小区里业主的报修信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'service/repair/index', 'title' => '查看'],
                            ['name' => 'service/repair/add', 'title' => '添加'],
                            ['name' => 'service/repair/edit', 'title' => '修改'],
                            ['name' => 'service/repair/del', 'title' => '删除'],
                            ['name' => 'service/repair/detail', 'title' => '查看详情'],
                        ],
                        'weigh'   => 3
                    ],
                    [
                        'name'    => 'service/complain',
                        'title'   => '投诉管理',
                        'remark'  => '用于管理小区里业主的投诉信息',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'service/complain/index', 'title' => '查看'],
                            ['name' => 'service/complain/add', 'title' => '添加'],
                            ['name' => 'service/complain/edit', 'title' => '修改'],
                            ['name' => 'service/complain/del', 'title' => '删除'],
                            ['name' => 'service/complain/detail', 'title' => '查看详情'],
                        ],
                        'weigh'   => 2
                    ],
                    [
                        'name'    => 'service/mailbox',
                        'title'   => '信箱管理',
                        'remark'  => '用于管理小区里业主的信箱信息，包括工作建议，意见反馈等。',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'service/mailbox/index', 'title' => '查看'],
                            ['name' => 'service/mailbox/add', 'title' => '添加'],
                            ['name' => 'service/mailbox/edit', 'title' => '修改'],
                            ['name' => 'service/mailbox/del', 'title' => '删除'],
                            ['name' => 'service/mailbox/detail', 'title' => '查看详情'],
                        ],
                        'weigh'   => 1
                    ],
                ],
                'weigh'   => 196
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
        Menu::delete('service');
        return true;
    }

}
