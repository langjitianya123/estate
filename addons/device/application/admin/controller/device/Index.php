<?php
namespace app\admin\controller\device;

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
    //检索时匹配的字段
    protected $searchfields = 'community_code,code,name,brand';
    protected $noNeedRight = ['selectpage'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Device');
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

    public function add() {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['code'] = $this->model->getMaxCode();
            $params['buy_time'] = strtotime($params['buy_time']);
            $this->request->post(['row' => $params]);
        }
        return parent::create();
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['buy_time'] = strtotime($params['buy_time']);
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

    public function detail($ids = null) {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if (filter_var($ids,FILTER_VALIDATE_INT)) {
            $append = array(
                array('id','=',$ids)
            );
        } else {
            $append = array(
                array('code','=',$ids)
            );
        }
        list($where, $sort, $order, $offset, $limit, $orderParams) = $this->buildparams(null,null,$append);
        $maintain = array(
            'maintain' => function ($query) {
                $query->order(array('id' => 'desc'));
            }
        );
        $device = $this->model->with($maintain)->where($where)->find();
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            $result = array("rows" => $device['maintain']);
            return json($result);
        }
        $this->view->assign('code',$device['code']);
        $this->view->assign('breadCrumb',array(sprintf('%s（%s）- %s',$device['name'],$device['code'], '维修记录')));
        return $this->view->fetch();
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
        $list = $this->model->with('community')->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
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

}