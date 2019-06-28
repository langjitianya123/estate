<?php

namespace addons\wechat;

use app\common\library\Menu;
use think\Addons;

/**
 * 微信插件
 */
class Wechat extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'wechat',
                'title'   => '微信管理',
                'icon'    => 'fa fa-wechat',
                'sublist' => [
                    [
                        'name'    => 'wechat/autoreply',
                        'title'   => '自动回复管理',
                        'icon'    => 'fa fa-reply-all',
                        'sublist' => [
                            ['name' => 'wechat/autoreply/index', 'title' => '查看'],
                            ['name' => 'wechat/autoreply/add', 'title' => '添加'],
                            ['name' => 'wechat/autoreply/edit', 'title' => '修改'],
                            ['name' => 'wechat/autoreply/del', 'title' => '删除'],
                            ['name' => 'wechat/autoreply/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'wechat/config',
                        'title'   => '配置管理',
                        'icon'    => 'fa fa-cog',
                        'sublist' => [
                            ['name' => 'wechat/config/index', 'title' => '查看'],
                            ['name' => 'wechat/config/add', 'title' => '添加'],
                            ['name' => 'wechat/config/edit', 'title' => '修改'],
                            ['name' => 'wechat/config/del', 'title' => '删除'],
                            ['name' => 'wechat/config/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'wechat/menu',
                        'title'   => '菜单管理',
                        'icon'    => 'fa fa-list',
                        'sublist' => [
                            ['name' => 'wechat/menu/index', 'title' => '查看'],
                            ['name' => 'wechat/menu/add', 'title' => '添加'],
                            ['name' => 'wechat/menu/edit', 'title' => '修改'],
                            ['name' => 'wechat/menu/del', 'title' => '删除'],
                            ['name' => 'wechat/menu/sync', 'title' => '同步'],
                            ['name' => 'wechat/menu/multi', 'title' => '批量更新'],
                        ]
                    ],
                    [
                        'name'    => 'wechat/response',
                        'title'   => '资源管理',
                        'icon'    => 'fa fa-list-alt',
                        'sublist' => [
                            ['name' => 'wechat/response/index', 'title' => '查看'],
                            ['name' => 'wechat/response/add', 'title' => '添加'],
                            ['name' => 'wechat/response/edit', 'title' => '修改'],
                            ['name' => 'wechat/response/del', 'title' => '删除'],
                            ['name' => 'wechat/response/select', 'title' => '选择'],
                            ['name' => 'wechat/response/multi', 'title' => '批量更新'],
                        ]
                    ]
                ]
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
        Menu::delete('wechat');
        return true;
    }

}
