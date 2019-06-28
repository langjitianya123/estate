<?php
namespace app\admin\controller\parking;

use app\common\controller\Backend;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/11/01
 * Time: 13:42
 */

class Usage extends Backend {

    protected $model = null;
    protected $communityModel = null;
    protected $parkingModel = null;
    //检索时匹配的字段
    protected $searchfields = 'pk_code,license_plate,owner,tel';
    protected $noNeedRight = ['selectpage','get_parking_by_cm_code'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('ParkingSpaceUse');
        $this->communityModel = model('Community');
        $this->parkingModel = model('ParkingSpace');
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
            $params['begin_time'] = strtotime($params['begin_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $this->request->post(array('row'=>$params));
        }
        return parent::create('','updateParkingStatus');
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['begin_time'] = strtotime($params['begin_time']);
            $params['end_time'] = strtotime($params['end_time']);
            $this->request->post(['row' => $params]);
        }
        return parent::modify($ids,'add','updateParkingStatus');
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
            array('community_code','in',parent::getCommunityIdByAuth())
        );
        $append = array_merge($append,$this->buildCommonSearch());
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams($searchfields,null,$append);
        $total = $this->model->where($where)->count();
        $list = $this->model->with('community,parking')->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    public function get_parking_by_cm_code() {
        $result = array();
        $cmCode = $this->request->request('community_code');
        $status = $this->request->request('status') ? null : 0;
        $pkCode = $this->request->request('parking_code');
        if ($pkCode) {
            $parking = $this->parkingModel->where(array('code' => $pkCode))->select();
        } else {
            $parking = $this->parkingModel->getParkingByCMCode($cmCode,$status);
        }
        $result['parking'] = $parking;
        return json($result);
    }

    protected function updateParkingStatus($id) {
        $info = $this->model->where(array('id'=>$id))->find();
        $this->parkingModel->save(array('status'=>1),array('code'=>$info['pk_code']));
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
        if ($searchs['pk_code']) {
            $where[] = array('pk_code', '=', $searchs['pk_code']);
        }
        return $where;
    }

}