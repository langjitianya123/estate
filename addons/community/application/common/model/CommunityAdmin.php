<?php
/**
 * Created by PhpStorm.
 * FileName: CommunityAdmin.php
 * User: Administrator
 * Date: 2017/11/2
 * Time: 9:33
 */

namespace app\common\model;


use think\Db;
use think\Model;

class CommunityAdmin extends Model {
    // 表名,不含前缀
    public $name = 'community_admin';

    /**
     * 获取小区管理员
     * @param $cmId
     * @return array
     */
    public function getManager($cmId) {
        $code = $this->getCodeById($cmId);
        return $this->where(array('community_code'=>$code))->column('admin_id');
    }

    /**
     * 新增小区管理员
     * @param $cmId
     * @param $adminIds
     */
    public function addManager($cmId,$adminIds) {
        $code = $this->getCodeById($cmId);
        $data = array();
        foreach ($adminIds as $adminId) {
            $data[] = array(
                'community_code' => $code,
                'admin_id' => $adminId
            );
        }
        if ($code && $adminIds) {
            $this->insertAll($data);
        }
    }

    /**
     * 更新小区管理员
     * @param $cmId
     * @param $adminIds
     */
    public function updateManager($cmId,$adminIds) {
        $code = $this->getCodeById($cmId);
        $where = array('community_code'=>$code);
        $oldManager = $this->where($where)->select();
        if ($oldManager) {
            if ($this->where($where)->delete() !== false) {
                $this->addManager($cmId,$adminIds);
            }
        }else{
            $this->addManager($cmId,$adminIds);
        }
    }

    /**
     * 删除小区管理员
     * @param $cmId
     */
    public function delManager($cmId) {
        $code = $this->getCodeById($cmId);
        $this->where(array('community_code'=>$code))->delete();
    }

    /**
     * 根据id获取小区编号
     * @param $id
     * @return mixed|null
     */
    public function getCodeById($id) {
        $community = Db::name('community')->where(array('id'=>$id))->field('code')->find();
        if ($community) {
            return $community['code'];
        }
        return null;
    }
}