<?php
/**
 * Created by PhpStorm.
 * FileName: Category.php
 * User: Administrator
 * Date: 2017/10/30
 * Time: 11:20
 */

namespace app\api\controller\general;


use app\api\library\ConstStatus;
use app\common\controller\BaseApi;
use think\Request;
use app\common\model\Category as CategoryModel;

class Category extends BaseApi {

    private $categoryModel = null;
    protected $allowMethod = array('get');

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->categoryModel = new CategoryModel();
    }

    /**
     * 资源列表（GET请求）
     * @param Request $request
     * @return resource
     */
    public function index(Request $request) {
        list($where,$order) = parent::buildParams();
        $where['status'] = 'normal';
        $order['weigh'] = 'desc';
        parent::validateField($this->categoryModel->requiredField['list'],$where);
        $result = $this->categoryModel->where($where)->order($order)->select();
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
            'status' => 'normal'
        );
        parent::validateField($this->categoryModel->requiredField['find'],$where);
        $result = $this->categoryModel->where($where)->find();
        return parent::response($result,ConstStatus::CODE_SUCCESS);
    }

    /**
     * 新增资源（POST请求）
     * @param Request $request
     * @return resource
     */
    public function save(Request $request) {
        $postData = $request->post();
        parent::validateField($this->categoryModel->requiredField['add'],$postData);
        $result = $this->categoryModel->data($postData)->allowField(true)->save();
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
        parent::validateField($this->categoryModel->requiredField['update'],$postData);
        $result = $this->categoryModel->allowField(true)->save($postData,$where);
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
        parent::validateField($this->categoryModel->requiredField['delete'],$where);
        $result = $this->categoryModel->where($where)->delete();
        if($result !== false) {
            return parent::response('',ConstStatus::CODE_SUCCESS,'删除成功');
        }
        return parent::response('',ConstStatus::CODE_ERROR,'删除失败');
    }

}