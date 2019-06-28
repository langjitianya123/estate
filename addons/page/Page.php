<?php

namespace addons\page;

use app\common\library\Menu;
use think\Addons;

/**
 * 单页插件
 */
class Page extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'page',
                'title'   => '单页管理',
                'icon'    => 'fa fa-tags',
                'remark'  => '用于管理普通的单页面,通常用于关于我们、联系我们、商务合作等单一页面',
                'sublist' => [
                    ['name' => 'page/index', 'title' => '查看'],
                    ['name' => 'page/add', 'title' => '添加'],
                    ['name' => 'page/edit', 'title' => '修改'],
                    ['name' => 'page/del', 'title' => '删除'],
                    ['name' => 'page/multi', 'title' => '批量更新'],
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
        Menu::delete('page');
        return true;
    }

}
