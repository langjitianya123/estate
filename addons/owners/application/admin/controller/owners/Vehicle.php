<?php
namespace app\admin\controller\owners;

use app\common\controller\Backend;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/11/01
 * Time: 13:42
 */

class Vehicle extends Backend {

    protected $model = null;
    protected $communityModel = null;
    protected $memberModel = null;
    //检索时匹配的字段
    protected $searchfields = 'name,license_plate,color';
    protected $noNeedRight = ['selectpage','get_member_by_cm_code'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Vehicle');
        $this->memberModel = model('Member');
        $this->communityModel = model('Community');
        $this->view->assign('community',$this->communityModel->where(array('code'=>array('in',parent::getCommunityIdByAuth())))->field('code,name')->select());
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
        return parent::modify($ids);
    }

    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post('row/a');
            $this->request->post(array('row'=>$params));
        }
        return parent::create();
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $this->request->post(['row' => $params]);
        }
        $vehicle = $this->model->with('member')->where(array('id'=>$ids))->find();
        $this->view->assign('vehicle',$vehicle);
        return parent::modify($ids,'add');
    }

    public function del($ids = null){
        $where = array(
            'id' => array('in',$ids)
        );
        parent::delete($where);
    }

    public function selectpage() {
        return parent::selectpage();
    }

    private function handleSearch($searchfields=null) {
        $append = array(
            array('member_id','in',$this->memberModel->getMemberIdByCMCode(parent::getCommunityIdByAuth()))
        );
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams($searchfields,null,$append);
        $total = $this->model->where($where)->count();
        $list = $this->model->with('member')->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    public function get_member_by_cm_code() {
        $result = array();
        $cmCode = $this->request->request('community_code');
        $house = $this->memberModel->getMemberByCMCode($cmCode);
        $result['member'] = $house;
        return json($result);
    }

}