<?php
namespace app\admin\controller\expenses;

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
    protected $communityModel = null;
    protected $projectModel = null;
    protected $houseModel = null;
    //检索时匹配的字段
    protected $searchfields = null;
    protected $noNeedRight = ['selectpage','get_project_by_cm_code'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Expenses');
        $this->communityModel = model('Community');
        $this->projectModel = model('ExpensesProject');
        $this->houseModel = model('House');
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
            array('community_code','in',parent::getCommunityIdByAuth())
        );
        $append = array_merge($append,$this->buildCommonSearch());
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams($searchfields,null,$append);
        $total = $this->model->where($where)->count();
        $list = $this->model->with('community,project,house')->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    public function get_project_by_cm_code() {
        $result = array();
        $cmCode = $this->request->request('community_code');
        $project = $this->projectModel->getProjectByCMCode($cmCode);
        $house = $this->houseModel->getHouseByCMCode($cmCode);
        $result['project'] = $project;
        $result['house'] = $house;
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
        if ($searchs['project_code']) {
            $where[] = array('project_code', '=', $searchs['project_code']);
        }
        if ($searchs['house_code']) {
            $where[] = array('house_code', '=', $searchs['house_code']);
        }
        if ($searchs['create_time_begin']) {
            $where[] = array('create_time', '>=', strtotime($searchs['create_time_begin'].'00:00:00'));
        }
        if ($searchs['create_time_end']) {
            $where[] = array('create_time', '<=', strtotime($searchs['create_time_end'].'23:59:59'));
        }
        return $where;
    }

}