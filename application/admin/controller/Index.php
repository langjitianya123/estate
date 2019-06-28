<?php

namespace app\admin\controller;

use app\common\controller\Backend;
// use function fast\array_except;
use think\Session;
use think\Validate;
use think\Config;

/**
 * 后台首页
 * @internal
 */
class Index extends Backend
{

    protected $noNeedLogin = ['login'];
    protected $noNeedRight = ['index', 'logout'];
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 后台首页
     */
    public function index()
    {
        //左侧菜单
        $badge = $this->getBadge();
        $menulist = $this->auth->getSidebar($badge, $this->view->site['fixedpage']);
        $this->view->assign('menulist', $menulist);
        $this->view->assign('title', __('Home'));
        return $this->view->fetch();
    }

    /**
     * 管理员登录
     */
    public function login()
    {
        $url = $this->request->get('url', 'index/index');
        if ($this->auth->isLogin())
        {
            $this->error(__("You've logged in, do not login again"), $url);
        }
        if ($this->request->isPost())
        {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $keeplogin = $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:3,30',
                '__token__' => 'token',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                '__token__' => $token,
            ];
            $validate = new Validate($rule);
            $result = $validate->check($data);
            if (!$result)
            {
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            \app\admin\model\AdminLog::setTitle(__('Login'));
            $result = $this->auth->login($username, $password, $keeplogin ? 86400 : 0);
            if ($result === true)
            {
                $this->success(__('Login successful'), $url, ['url' => $url, 'id' => $this->auth->id, 'username' => $username, 'avatar' => $this->auth->avatar]);
            }
            else
            {
                $this->error(__('Username or password is incorrect'), $url, ['token' => $this->request->token()]);
            }
        }

        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin())
        {
            $this->redirect($url);
        }
        $site = Config::get("site");
        $background = cdnurl(sprintf('/assets/img/%s',$this->getBgImage()));
        $this->view->assign('background', $background);
        $this->view->assign('title', __($site['name']));
        \think\Hook::listen("login_init", $this->request);
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success(__('Logout successful'), 'index/login');
    }

    private function getBadge() {
        $badge = [
            'dashboard'             => '控',
            'community/index'       => '小',
            'house/index'           => '房',
            'house/building'        => '栋',
            'owners/index'          => '人',
            'owners/vehicle'        => '车',
            'owners/pet'            => '宠',
            'parking/index'         => '车',
            'parking/usage'         => '用',
            'service/activity'      => '活',
            'service/repair'        => '修',
            'service/complain'      => '诉',
            'service/mailbox'       => '信',
            'device/index'          => '资',
            'expenses/index'        => '明',
            'expenses/project'      => '项',
            'duty/index'            => '值',
            'general/config'        => '配',
            'general/attachment'    => '附',
            'general/profile'       => '个',
            'general/crontab'       => '定',
            'auth/admin'            => '管',
            'auth/adminlog'         => '日',
            'auth/group'            => ['角', 'purple'],
            'auth/rule'             => '规',
            //'addon'       => ['new', 'red', 'badge'],
        ];
        return $badge;
    }

    /**
     * 获取随机背景图
     * @return mixed
     */
    private function getBgImage() {
        $bg = array(
            'loginbg.jpg','loginbg1.jpg','loginbg2.jpg','loginbg3.jpg',
            'loginbg4.jpg','loginbg5.jpg','loginbg6.jpg','loginbg7.jpg',
            'loginbg8.jpg','loginbg9.jpg','loginbg10.jpg'
        );
        $bgKey = 0;
        if (Config::get('random_bg_image')) {
            $bgKey = array_rand($bg);
        }
        return $bg[$bgKey];
    }

}
