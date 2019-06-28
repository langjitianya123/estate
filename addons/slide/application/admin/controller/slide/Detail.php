<?php
namespace app\admin\controller\slide;

use app\common\controller\Backend;
use app\common\model\SlideCategory;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/20
 * Time: 13:42
 */

class Detail extends Backend {

    protected $model = null;
    protected $slideCategoryModel = null;
    //检索时匹配的字段
    protected $searchfields = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Slide');
    }

    public function add($ids = null) {
        $this->view->assign('row',array('cid'=>$ids));
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

}