<?php

use think\App;
use think\Cache;
use think\Config;
use think\Exception;
use think\Hook;
use think\Loader;
use think\Route;

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);

// 定义路由
Route::any('addons/:addon/[:controller]/[:action]', "\\think\\addons\\Route@execute");

// 如果插件目录不存在则创建
if (!is_dir(ADDON_PATH))
{
    @mkdir(ADDON_PATH, 0755, true);
}

// 注册类的根命名空间
Loader::addNamespace('addons', ADDON_PATH);

// 闭包自动识别插件目录配置
Hook::add('app_init', function () {
    // 获取开关
    $autoload = (bool) Config::get('addons.autoload', false);
    // 非正是返回
    if (!$autoload)
    {
        return;
    }
    // 当debug时不缓存配置
    $config = App::$debug ? [] : Cache::get('addons', []);
    if (empty($config))
    {
        $config = get_addon_autoload_config();
        Cache::set('addons', $config);
    }
});

// 闭包初始化行为
Hook::add('app_init', function () {
    // 获取系统配置
    $hooks = App::$debug ? [] : Cache::get('hooks', []);
    if (empty($hooks))
    {
        $hooks = (array) Config::get('addons.hooks');
        // 初始化钩子
        foreach ($hooks as $key => $values)
        {
            if (is_string($values))
            {
                $values = explode(',', $values);
            }
            else
            {
                $values = (array) $values;
            }
            $hooks[$key] = array_filter(array_map('get_addon_class', $values));
        }
        Cache::set('hooks', $hooks);
    }
    //如果在插件中有定义app_init，则直接执行
    if (isset($hooks['app_init']))
    {
        foreach ($hooks['app_init'] as $k => $v)
        {
            Hook::exec($v, 'app_init');
        }
    }
    Hook::import($hooks, false);
});

/**
 * 处理插件钩子
 * @param string $hook 钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = [])
{
    Hook::listen($hook, $params);
}

/**
 * 获得插件列表
 * @return array
 */
function get_addon_list()
{
    $results = scandir(ADDON_PATH);
    $list = [];
    foreach ($results as $name)
    {
        if ($name === '.' or $name === '..')
            continue;
        $addonDir = ADDON_PATH . DS . $name . DS;
        if (!is_dir($addonDir))
            continue;

        if (!is_file($addonDir . ucfirst($name) . '.php'))
            continue;

        //这里不采用get_addon_info是因为会有缓存
        //$info = get_addon_info($name);
        $info_file = $addonDir . 'info.ini';
        if (!is_file($info_file))
            continue;

        $info = Config::parse($info_file, '', "addon-info-{$name}");
        $info['url'] = addon_url($name);
        $list[$name] = $info;
    }
    return $list;
}

/**
 * 获得插件自动加载的配置
 * @return array
 */
function get_addon_autoload_config($truncate = false)
{
    // 读取addons的配置
    $config = (array) Config::get('addons');
    if ($truncate)
    {
        // 清空手动配置的钩子
        $config['hooks'] = [];
    }
    // 读取插件目录及钩子列表
    $base = get_class_methods("\\think\\Addons");

    $addons = get_addon_list();

    foreach ($addons as $name => $addon)
    {
        if (!$addon['state'])
            continue;

        // 读取出所有公共方法
        $methods = (array) get_class_methods("\\addons\\" . $name . "\\" . ucfirst($name));
        // 跟插件基类方法做比对，得到差异结果
        $hooks = array_diff($methods, $base);
        // 循环将钩子方法写入配置中
        foreach ($hooks as $hook)
        {
            $hook = Loader::parseName($hook, 0, false);
            if (!isset($config['hooks'][$hook]))
            {
                $config['hooks'][$hook] = [];
            }
            // 兼容手动配置项
            if (is_string($config['hooks'][$hook]))
            {
                $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
            }
            if (!in_array($name, $config['hooks'][$hook]))
            {
                $config['hooks'][$hook][] = $name;
            }
        }
    }
    return $config;
}

/**
 * 获取插件类的类名
 * @param $name 插件名
 * @param string $type 返回命名空间类型
 * @param string $class 当前类名
 * @return string
 */
function get_addon_class($name, $type = 'hook', $class = null)
{
    $name = Loader::parseName($name);
    // 处理多级控制器情况
    if (!is_null($class) && strpos($class, '.'))
    {
        $class = explode('.', $class);

        $class[count($class) - 1] = Loader::parseName(end($class), 1);
        $class = implode('\\', $class);
    }
    else
    {
        $class = Loader::parseName(is_null($class) ? $name : $class, 1);
    }
    switch ($type)
    {
        case 'controller':
            $namespace = "\\addons\\" . $name . "\\controller\\" . $class;
            break;
        default:
            $namespace = "\\addons\\" . $name . "\\" . $class;
    }
    return class_exists($namespace) ? $namespace : '';
}

/**
 * 读取插件的基础信息
 * @param string $name 插件名
 * @return array
 */
function get_addon_info($name)
{
    $class = get_addon_class($name);
    if (class_exists($class))
    {
        $addon = new $class();
        return $addon->getInfo($name);
    }
    else
    {
        return [];
    }
}

/**
 * 获取插件类的配置数组
 * @param string $name 插件名
 * @return array
 */
function get_addon_fullconfig($name)
{
    $class = get_addon_class($name);
    if (class_exists($class))
    {
        $addon = new $class();
        return $addon->getFullConfig($name);
    }
    else
    {
        return [];
    }
}

/**
 * 获取插件类的配置值值
 * @param string $name 插件名
 * @return array
 */
function get_addon_config($name)
{
    $class = get_addon_class($name);
    if (class_exists($class))
    {
        $addon = new $class();
        return $addon->getConfig($name);
    }
    else
    {
        return [];
    }
}

/**
 * 插件显示内容里生成访问插件的url
 * @param $url 地址
 * @param array $param
 * @return bool|string
 * @param bool|string $suffix 生成的URL后缀
 * @param bool|string $domain 域名
 */
function addon_url($url, $param = [], $suffix = true, $domain = false)
{
    return url("@addons/{$url}", $param, $suffix, $domain);
}

/**
 * 设置基础配置信息
 * @param string $name 插件名
 * @param array $array
 * @return boolean
 * @throws Exception
 */
function set_addon_info($name, $array)
{

    $file = ADDON_PATH . $name . '/info.ini';
    $obj = get_addon_class($name);
    $res = array();
    foreach ($array as $key => $val)
    {
        if (is_array($val))
        {
            $res[] = "[$key]";
            foreach ($val as $skey => $sval)
                $res[] = "$skey = " . (is_numeric($sval) ? $sval : $sval);
        }
        else
            $res[] = "$key = " . (is_numeric($val) ? $val : $val);
    }
    if ($handle = fopen($file, 'w'))
    {
        fwrite($handle, implode("\r\n", $res));
        fclose($handle);
        //清空当前配置缓存
        Config::set("addon-info-{$name}", NULL);
    }
    else
    {
        throw new Exception("文件没有写入权限");
    }
    return true;
}

/**
 * 写入配置文件
 * 
 * @param string $name 插件名
 * @param array $array
 * @return boolean
 * @throws Exception
 */
function set_addon_fullconfig($name, $array)
{
    $file = ADDON_PATH . $name . '/config.php';
    if (!is_really_writable($file))
    {
        throw new Exception("文件没有写入权限");
    }
    if ($handle = fopen($file, 'w'))
    {
        fwrite($handle, "<?php\r\n\r\n" . "return " . var_export($array, TRUE) . ";");
        fclose($handle);
    }
    else
    {
        throw new Exception("文件没有写入权限");
    }
    return true;
}
