<?php
/**
 * Created by PhpStorm.
 * FileName: Slide.php
 * User: Administrator
 * Date: 2017/10/31
 * Time: 10:47
 */

namespace app\api\controller\general;


use app\common\controller\BaseApi;
use app\api\library\ConstStatus;
use think\Request;
use app\common\model\Slide as SlideModel;
use app\common\model\SlideCategory as SlideCateModel;

class Slide extends BaseApi {

    private $slideModel = null;
    private $slideCateModel = null;
    protected $allowMethod = array('get');

    public function __construct(Request $request) {
        parent::__construct($request);
        $this->slideModel = new SlideModel();
        $this->slideCateModel = new SlideCateModel();
    }

    /**
     * 资源列表（GET请求）
     * @param Request $request
     * @return resource
     */
    public function index(Request $request) {
        list($where,$order) = parent::buildParams();
        $cate = $this->slideCateModel->getCateByCode($where['code']);
        $where['status'] = 1;
        $where['cid'] = $cate['id'];
        unset($where['code']);
        $order['sort'] = 'asc';
        parent::validateField($this->slideModel->requiredField['list'],$where);
        $result = $this->slideModel->where($where)->order($order)->paginate(10);
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
        parent::validateField($this->slideModel->requiredField['find'],$where);
        $result = $this->slideModel->where($where)->find();
        return parent::response($result,ConstStatus::CODE_SUCCESS);
    }

    /**
     * 新增资源（POST请求）
     * @param Request $request
     * @return resource
     */
    public function save(Request $request) {
        $postData = $request->post();
        parent::validateField($this->slideModel->requiredField['add'],$postData);
        $result = $this->slideModel->data($postData)->allowField(true)->save();
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
        parent::validateField($this->slideModel->requiredField['update'],$postData);
        $result = $this->slideModel->allowField(true)->save($postData,$where);
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
        parent::validateField($this->slideModel->requiredField['delete'],$where);
        $result = $this->slideModel->where($where)->delete();
        if($result !== false) {
            return parent::response('',ConstStatus::CODE_SUCCESS,'删除成功');
        }
        return parent::response('',ConstStatus::CODE_ERROR,'删除失败');
    }
}