<?php
namespace app\admin\controller\device;

use app\common\controller\Backend;
use app\common\model\SlideCategory;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/11/01
 * Time: 13:42
 */

class History extends Backend {

    protected $model = null;
    //检索时匹配的字段
    protected $searchfields = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('DeviceMaintain');
    }

    public function add($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['last_maintain_time'] = strtotime($params['last_maintain_time']);
            $params['next_maintain_time'] = strtotime($params['next_maintain_time']);
            $this->request->post(['row' => $params]);
        }
        $this->view->assign('row',array('device_code'=>$ids));
        return parent::create();
    }

    public function edit($ids = null) {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $params['last_maintain_time'] = strtotime($params['last_maintain_time']);
            $params['next_maintain_time'] = strtotime($params['next_maintain_time']);
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

}