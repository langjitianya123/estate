<?php

namespace addons\friendlylink;

use app\common\library\Menu;
use think\Addons;

/**
 * 通用内容管理插件
 */
class Friendlylink extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'friendlylink/index',
                'title'   => '友情链接列表',
                'remark'  => '用于管理页面底部的相关链接',
                'icon'    => 'fa fa-link',
                'sublist' => [
                    ['name' => 'friendlylink/index/index', 'title' => '查看'],
                    ['name' => 'friendlylink/index/add', 'title' => '添加'],
                    ['name' => 'friendlylink/index/edit', 'title' => '修改'],
                    ['name' => 'friendlylink/index/del', 'title' => '删除'],
                ],
                'weigh'   => 116
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
        Menu::delete('friendlylink/index');
        return true;
    }

}
