<?php

namespace addons\article;

use app\common\library\Menu;
use think\Addons;

/**
 * 通用内容管理插件
 */
class Article extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'article/index',
                'title'   => '文章管理',
                'remark'  => '用于通用内容管理',
                'icon'    => 'fa fa-list-alt',
                'sublist' => [
                    ['name' => 'article/index/index', 'title' => '查看'],
                    ['name' => 'article/index/add', 'title' => '添加'],
                    ['name' => 'article/index/edit', 'title' => '修改'],
                    ['name' => 'article/index/del', 'title' => '删除'],
                ],
                'weigh'   => 120
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
        Menu::delete('article/index');
        return true;
    }

}
