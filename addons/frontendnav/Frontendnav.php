<?php

namespace addons\frontendnav;

use app\common\library\Menu;
use think\Addons;

/**
 * 通用内容管理插件
 */
class Frontendnav extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'frontendnav',
                'title'   => '前台导航管理',
                'icon'    => 'fa fa-compass',
                'sublist' => [
                    [
                        'name'    => 'frontendnav/navcate',
                        'title'   => '导航分类管理',
                        'remark'  => '用于设置导航的类别',
                        'icon'    => 'fa fa-align-justify',
                        'sublist' => [
                            ['name' => 'frontendnav/navcate/index', 'title' => '查看'],
                            ['name' => 'frontendnav/navcate/add', 'title' => '添加'],
                            ['name' => 'frontendnav/navcate/edit', 'title' => '修改'],
                            ['name' => 'frontendnav/navcate/del', 'title' => '删除'],
                        ],
                        'weigh'   => 1
                    ],
                    [
                        'name'    => 'frontendnav/nav',
                        'title'   => '导航管理',
                        'remark'  => '用于设置导航信息',
                        'icon'    => 'fa fa-desktop',
                        'sublist' => [
                            ['name' => 'frontendnav/nav/index', 'title' => '查看'],
                            ['name' => 'frontendnav/nav/add', 'title' => '添加'],
                            ['name' => 'frontendnav/nav/edit', 'title' => '修改'],
                            ['name' => 'frontendnav/nav/del', 'title' => '删除'],
                        ],
                        'weigh'   => 2
                    ],
                ],
                'weigh'   => 118
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
        Menu::delete('frontendnav');
        return true;
    }

}
