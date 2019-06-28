<?php

namespace addons\community;

use app\common\library\Menu;
use think\Addons;

/**
 * 小区管理插件
 */
class Community extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'community/index',
                'title'   => '小区管理',
                'remark'  => '用于管理每个小区的基本信息',
                'icon'    => 'fa fa-list-alt',
                'sublist' => [
                    ['name' => 'community/index/index', 'title' => '查看'],
                    ['name' => 'community/index/detail', 'title' => '详情'],
                    ['name' => 'community/index/add', 'title' => '添加'],
                    ['name' => 'community/index/edit', 'title' => '修改'],
                    ['name' => 'community/index/del', 'title' => '删除'],
                ],
                'weigh'   => 200
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
        Menu::delete('community/index');
        return true;
    }

}
