<?php

namespace think\addons;

use think\Config;
use think\exception\HttpException;
use think\Hook;
use think\Loader;
use think\Request;

/**
 * 插件执行默认控制器
 * @package think\addons
 */
class Route
{

    /**
     * 插件执行
     */
    public function execute()
    {
        $request = Request::instance();
        // 是否自动转换控制器和操作名
        $convert = Config::get('url_convert');
        $filter = $convert ? 'strtolower' : '';
        // 处理路由参数
        $addon = $request->param('addon', '', $filter);
        $controller = $request->param('controller', 'index', $filter);
        $action = $request->param('action', 'index', $filter);
        if (!empty($addon) && !empty($controller) && !empty($action))
        {
            $info = get_addon_info($addon);
            if (!$info)
            {
                throw new HttpException(404, 'addon not exists:' . $addon);
            }
            if (!$info['state'])
            {
                throw new HttpException(500, 'the addon is disabled:' . $addon);
            }
            // 设置当前请求的控制器、操作
            $request->controller($controller)->action($action);

            $class = get_addon_class($addon, 'controller', $controller);
            if (!$class)
            {
                throw new HttpException(404, 'controller not exists:' . Loader::parseName($controller, 1));
            }

            // 监听addons_init
            Hook::listen('addons_init', $request);

            $instance = new $class($request);

            $vars = [];
            if (is_callable([$instance, $action]))
            {
                // 执行操作方法
                $call = [$instance, $action];
            }
            elseif (is_callable([$instance, '_empty']))
            {
                // 空操作
                $call = [$instance, '_empty'];
                $vars = [$action];
            }
            else
            {
                // 操作不存在
                throw new HttpException(404, 'method not exists:' . get_class($instance) . '->' . $action . '()');
            }

            Hook::listen('action_begin', $call);

            return call_user_func_array($call, $vars);
        }
        else
        {
            abort(500, lang('addon can not be empty'));
        }
    }

}
