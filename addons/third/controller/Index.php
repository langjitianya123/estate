<?php

namespace addons\third\controller;

use addons\third\library\Application;

/**
 * 第三方登录
 */
class Index extends \think\addons\Controller
{

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->error("当前插件暂无前台页面");
    }

    /**
     * 第三方授权登录跳转
     */
    public function authorize()
    {
        $platform = $this->request->param('platform');
        $config = $this->view->config;
        if (!isset($config[$platform]))
        {
            $this->error(__('Invalid parameters'));
        }
        $thirdapp = new Application($config);
        // 跳转到登录授权页面
        $this->redirect($thirdapp->{$platform}->getAuthorizeUrl());
    }

    /**
     * 回调处理
     */
    public function callback()
    {
        $platform = $this->request->param('platform');
        $config = $this->view->config;
        if (!isset($config[$platform]))
        {
            $this->error(__('Invalid parameters'));
        }
        $thirdapp = new Application($config);
        
        $userinfo = $thirdapp->{$platform}->getUserInfo();
        if ($userinfo)
        {
            $params = [
                'platform' => $platform,
                'service'  => $thirdapp->{$platform},
                'userinfo' => $userinfo,
            ];
            \think\Hook::listen("third_login_success", $params);
            $this->success(__('Logged in successful'), '/');
        }
        $this->error(__('Operation failed'), 'user/login');
    }

}
