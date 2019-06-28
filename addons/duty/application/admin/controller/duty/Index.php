<?php
namespace app\admin\controller\duty;

use app\common\controller\Backend;
use think\Session;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/11/01
 * Time: 13:42
 */

class Index extends Backend {

    protected $model = null;
    protected $communityModel = null;
    protected $noNeedRight = ['selectpage'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Duty');
        $this->communityModel = model('Community');
        $this->view->assign('community',$this->communityModel->where(array('code'=>array('in',parent::getCommunityIdByAuth())))->field('code,name')->select());
        $this->view->assign('users',$this->getAdminIds());
    }

    public function index() {
        $currentDate = date('Y-m-d');
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            return $this->handleSearch();
        }
        $this->view->assign('currentDate',$currentDate);
        return $this->view->fetch();
    }

    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['start_time'] = strtotime($params['start_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $this->request->post(['row' => $params]);
        }
        $community_code = $this->request->request('code');
        $selDate = $this->request->request('selDate')/1000;
        $duty = array(
            'start_time' => date('Y-m-d H:i',$selDate),
            'end_time' => date('Y-m-d H:i',$selDate),
            'community_code' => $community_code
        );
        $this->view->assign('duty',$duty);
        return parent::create();
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['start_time'] = strtotime($params['start_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $this->request->post(['row' => $params]);
        }
        $duty = $this->model->where(array('id'=>$ids))->find();
        $duty['start_time'] = date('Y-m-d H:i',$duty['start_time']);
        $duty['end_time'] = date('Y-m-d H:i',$duty['end_time']);
        $this->view->assign('duty',$duty);
        return parent::modify($ids,'add');
    }

    public function del($ids = null){
        $result = array('status' => false);
        if($ids) {
            $this->model->where(array('id'=>$ids))->delete();
            $result['status'] = true;
        }
        return json($result);
    }

    private function handleSearch() {
        $append = array(
            array('community_code','in',parent::getCommunityIdByAuth())
        );
        $start = $this->request->request('start');
        $end = $this->request->request('end');
        $community_code = $this->request->request('code');
        if ($community_code) {
            $append[] = array('community_code','=',$community_code);
        }
        if ($start) {
            $append[] = array('start_time','>=',$start);
        }
        if ($end) {
            $append[] = array('end_time','<=',$end);
        }
        $append = array_merge($append,$this->buildCommonSearch());
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams(null,null,$append);
        $list = $this->model->with('community')->where($where)->order($orderParams)->select();
        $result = array();
        foreach ($list as $item) {
            $shortStart = $item['start_time'] ? date('H:i',$item['start_time']) : '';
            $shortEnd = $item['end_time'] ? date('H:i',$item['end_time']) : '';
            $result[] = array(
                'id' => $item['id'],
                'title' => sprintf('%s - %s %s', $shortStart, $shortEnd, str_replace(',','，',$item['name'])),
                'start' => $item['start_time'] ? date('Y-m-d',$item['start_time']) : '',
                'end' => $item['end_time'] ? date('Y-m-d',$item['end_time']+1*24*60*60) : '',
                'remark' => $item['remark']
            );
        }
        return json($result);
    }

    /**
     * 自定义搜索
     * @return array
     */
    private function buildCommonSearch() {
        $where = array();
        $searchs = $this->request->request('query/a');
        if ($searchs['community_code']) {
            $where[] = array('community_code', '=', $searchs['community_code']);
        }
        return $where;
    }

    protected function getAdminIds() {
        $admin = Session::get('admin');
        if($admin) {
            $community_code = $this->request->request('code');
            $userInfo = json_decode($admin,true);
            $userIds = model('AuthGroupAccess')->where(array('group_id'=>array('in',array(2,3))))->column('uid');
            if ($this->auth->checkIsSuperAdmin($userInfo['id']) === false) {
                $adminIds = model('CommunityAdmin')->where(array('community_code' => $community_code))->column('admin_id');
                $userIds = array_intersect($adminIds,$userIds);
            }
            $where = array(
                'id' => array('in',$userIds),
                'status'=>'normal'
            );
            $users = model('Admin')->where($where)->field('id,nickname as name')->select();
            return $users;
        }
        return ;
    }

}