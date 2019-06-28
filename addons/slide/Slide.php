<?php

namespace addons\slide;

use app\common\library\Menu;
use think\Addons;

/**
 * 轮播管理插件
 */
class Slide extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'slide/index',
                'title'   => '轮播列表',
                'remark'  => '用于幻灯片管理',
                'icon'    => 'fa fa-columns',
                'sublist' => [
                    ['name' => 'slide/index/index', 'title' => '查看'],
                    ['name' => 'slide/index/add', 'title' => '添加'],
                    ['name' => 'slide/index/edit', 'title' => '修改'],
                    ['name' => 'slide/index/del', 'title' => '删除'],
                ],
                'weigh'   => 117
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
        Menu::delete('slide/index');
        return true;
    }

}
