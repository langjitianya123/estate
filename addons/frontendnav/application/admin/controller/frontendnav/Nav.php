<?php
namespace app\admin\controller\frontendnav;

use app\common\controller\Backend;
use app\common\model\FrontNavCate;

/**
 * Created by PhpStorm.
 * FileName: Index.php
 * User: Administrator
 * Date: 2017/10/20
 * Time: 13:42
 * @remark 用于通用内容管理
 */

class Nav extends Backend {

    protected $model = null;
    protected $navCateModel = null;
    protected $navList = null;
    //检索时匹配的字段
    protected $searchfields = 'name';
    protected $noNeedRight = ['selectpage'];

    public function _initialize() {
        parent::_initialize();
        $this->model = model('FrontNav');
        $this->navCateModel = new FrontNavCate();
        $this->view->assign("navCateList", $this->navCateModel->select());
        $this->navList = $this->model->getTreeCategory();
        $categorydata = [0 => ['name' => __('/')]];
        foreach ($this->navList as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        $this->view->assign("navParentList", $categorydata);
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
        $search = $this->request->request("search");
        //构造父类select列表选项数据
        $list = [];
        if ($search) {
            foreach ($this->navList as $k => $v) {
                if (stripos($v['name'], $search) !== false) {
                    $list[] = $v;
                }
            }
        } else {
            $list = $this->navList;
        }
        $total = count($list);
        $result = array("total" => $total, "rows" => $list);
        return json($result);
    }

}