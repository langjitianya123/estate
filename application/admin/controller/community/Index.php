<?php
namespace app\admin\controller\community;

use app\common\controller\Backend;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/11/01
 * Time: 13:42
 */

class Index extends Backend {

    protected $model = null;
    protected $communityAdminModel = null;
    //检索时匹配的字段
    protected $searchfields = 'code,name,address,developer,estate';
    protected $noNeedRight = ['selectpage'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Community');
        $this->communityAdminModel = model('CommunityAdmin');
        $admins = model('Admin')->where(array('status'=>'normal'))->column('id,concat(nickname,\' [\',username,\']\') as name');
        $this->view->assign('admins',$admins);
    }

    public function index() {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            return $this->handleSearch($this->searchfields);
        }
        return $this->view->fetch();
    }

    public function detail($ids = null) {
        $this->view->assign('selectedAdmins',$this->communityAdminModel->getManager($ids));
        return parent::modify($ids);
    }

    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post('row/a');
            $params['code'] = $this->model->getMaxCode();
            $this->request->post(array('row'=>$params));
        }
        return parent::create('','addManager');
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $this->request->post(['row' => $params]);
        }
        $this->view->assign('selectedAdmins',$this->communityAdminModel->getManager($ids));
        return parent::modify($ids,'add','updateManager');
    }

    public function del($ids = null){
        $where = array(
            'id' => array('in',$ids)
        );
        parent::delete($where,'delManager');
    }

    public function selectpage() {
        return parent::selectpage();
    }

    private function handleSearch($searchfields=null) {
        $append = array(
            array('code','in',parent::getCommunityIdByAuth())
        );
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams($searchfields,null,$append);
        $total = $this->model->where($where)->count();
        $list = $this->model->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    protected function addManager($id) {
        $this->communityAdminModel->addManager($id,$this->request->post('admins/a'));
    }

    protected function updateManager($id) {
        $this->communityAdminModel->updateManager($id,$this->request->post('admins/a'));
    }

    protected function delManager($where) {
        $this->communityAdminModel->delManager($where['id'][1]);
    }

}