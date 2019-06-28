<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 会员管理
 *
 * @icon fa fa-user-o
 */
class User extends Backend
{

    protected $noNeedRight = ['selectpage'];

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->field(['password', 'salt', 'token'], true)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 
     * @internal
     */
    public function add()
    {
        return;
    }

    /**
     * 修改
     */
    public function edit($ids = NULL)
    {
        if ($this->request->isPost())
        {
            $params = $this->request->post("row/a");
            if ($params['password'])
            {
                $password = $params['password'];
                $salt = \fast\Random::alnum();
                $params = array_merge($params, ['password' => \addons\user\library\Auth::instance()->getEncryptPassword($password, $salt), 'salt' => $salt]);
            }
            else
            {
                unset($params['password']);
            }
            $this->request->post(['row' => $params]);
        }
        return parent::edit($ids);
    }

    /**
     * 快捷搜索
     * @internal
     */
    public function selectpage()
    {
        $origin = parent::selectpage();
        $result = $origin->getData();
        $list = [];
        foreach ($result['list'] as $k => $v)
        {
            $list[] = ['id' => $v->id, 'nickname' => $v->nickname, 'username' => $v->username];
        }
        $result['list'] = $list;
        return json($result);
    }

}
