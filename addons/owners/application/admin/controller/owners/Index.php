<?php
namespace app\admin\controller\owners;

use app\common\controller\Backend;
use fast\Random;

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
    protected $houseModel = null;
    //检索时匹配的字段
    protected $searchfields = 'name,identity_id,tel,occupation';
    protected $noNeedRight = ['selectpage','get_house_by_cm_code'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Member');
        $this->communityModel = model('Community');
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
            if ($this->model->checkExists('tel',$params['tel']) !== false) {
                $this->error('该成员的联系方式已存在');
            }
            if ($this->model->checkExists('identity_id',$params['identity_id']) !== false) {
                $this->error('该成员的身份证号已存在');
            }
            $this->request->post(array('row'=>$params));
        }
        return parent::create('','syncAdmin');
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($this->model->checkExists('tel',$params['tel'],$ids) !== false) {
                $this->error('该成员的联系方式已存在');
            }
            if ($this->model->checkExists('identity_id',$params['identity_id'],$ids) !== false) {
                $this->error('该成员的身份证号已存在');
            }
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
        list($where, $sort, $order, $offset, $limit,$orderParams) = $this->buildparams($searchfields,null,$append);
        $total = $this->model->where($where)->count();
        $list = $this->model->with('community,house')->where($where)->order($orderParams)->limit($offset, $limit)->select();
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

    public function get_house_by_cm_code() {
        $result = array();
        $cmCode = $this->request->request('community_code');
        $house = $this->houseModel->getHouseByCMCode($cmCode);
        $result['house'] = $house;
        return json($result);
    }

    /**
     * 同步创建业主账号
     */
    protected function syncAdmin() {
        $data = array();
        $mobile = '';
        $defaultPwd = '';
        $params = $this->request->post("row/a");
        if ($params && $params['owner_type'] == 1) {
            $mobile = $params['tel'];
            if (!$mobile) {
                return false;
            }
            $defaultPwd = substr($mobile,-6);
            $data['username'] = $mobile;
            $data['nickname'] = $params['name'];
            $data['email'] = sprintf('%s@admin.com',$mobile);
            $data['status'] = 'normal';
            $data['salt'] = Random::alnum();
            $data['password'] = md5(md5($defaultPwd) . $data['salt']);
            $data['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。

            $admin = model('Admin')->create($data);
            return model('AuthGroupAccess')->save(['uid' => $admin->id, 'group_id' => 3]);
        }
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
        if ($searchs['house_code']) {
            $where[] = array('house_code', '=', $searchs['house_code']);
        }
        if ($searchs['birth_begin']) {
            $where[] = array('birth', '>=', $searchs['birth_begin']);
        }
        if ($searchs['birth_end']) {
            $where[] = array('birth', '<=', $searchs['birth_end']);
        }
        return $where;
    }

}