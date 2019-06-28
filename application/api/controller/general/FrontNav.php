<?php
/**
 * Created by PhpStorm.
 * FileName: FrontNav.php
 * User: Administrator
 * Date: 2017/10/30
 * Time: 13:45
 */

namespace app\api\controller\general;


use app\api\library\ConstStatus;
use app\common\controller\BaseApi;
use think\Request;
use app\common\model\FrontNav as FrontNavModel;
use app\common\model\FrontNavCate as FrontNavCateModel;

class FrontNav extends BaseApi{

    private $frontNavModel = null;
    private $frontNavCateModel = null;
    protected $allowMethod = array('get');

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->frontNavModel = new FrontNavModel();
        $this->frontNavCateModel = new FrontNavCateModel();
    }

    /**
     * 资源列表（GET请求）
     * @param Request $request
     * @return resource
     */
    public function index(Request $request) {
        list($where,$order) = parent::buildParams();
        $cate = $this->frontNavCateModel->getCateByCode($where['code']);
        $where['status'] = 1;
        $where['cate_id'] = $cate['id'];
        unset($where['code']);
        $order['sort'] = 'asc';
        parent::validateField($this->frontNavModel->requiredField['list'],$where);
        $result = $this->frontNavModel->with('cate')->where($where)->order($order)->paginate(10);
        return parent::response($result,ConstStatus::CODE_SUCCESS);
    }

    /**
     * 获取单个资源（GET请求）
     * @param Request $request
     * @param $id
     * @return resource
     */
    public function read(Request $request, $id) {
        $where = array(
            'id' => $id,
            'status' => 1
        );
        parent::validateField($this->frontNavModel->requiredField['find'],$where);
        $result = $this->frontNavModel->with('cate')->where($where)->find();
        return parent::response($result,ConstStatus::CODE_SUCCESS);
    }

    /**
     * 新增资源（POST请求）
     * @param Request $request
     * @return resource
     */
    public function save(Request $request) {
        $postData = $request->post();
        parent::validateField($this->frontNavModel->requiredField['add'],$postData);
        $result = $this->frontNavModel->data($postData)->allowField(true)->save();
        if($result !== false) {
            return parent::response('',ConstStatus::CODE_SUCCESS,'新增成功');
        }
        return parent::response('',ConstStatus::CODE_ERROR,'新增失败');
    }


    /**
     * 更新单个资源（PUT请求）
     * @param Request $request
     * @param $id
     * @return resource
     */
    public function update(Request $request, $id) {
        $where = array(
            'id' => $id
        );
        $postData = $request->post();
        parent::validateField($this->frontNavModel->requiredField['update'],$postData);
        $result = $this->frontNavModel->allowField(true)->save($postData,$where);
        if($result !== false) {
            return parent::response('',ConstStatus::CODE_SUCCESS,'更新成功');
        }
        return parent::response('',ConstStatus::CODE_ERROR,'更新失败');
    }

    /**
     * 删除资源（DELETE请求）
     * @param Request $request
     * @param $id
     * @return resource
     */
    public function delete(Request $request, $id) {
        $where = array(
            'id' => $id
        );
        parent::validateField($this->frontNavModel->requiredField['delete'],$where);
        $result = $this->frontNavModel->where($where)->delete();
        if($result !== false) {
            return parent::response('',ConstStatus::CODE_SUCCESS,'删除成功');
        }
        return parent::response('',ConstStatus::CODE_ERROR,'删除失败');
    }
}