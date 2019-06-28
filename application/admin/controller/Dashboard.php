<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend {

    protected $prefix = null;
    protected $communityModel = null;
    protected $noNeedRight = ['selectpage','get_activity','get_repair','get_expenses','get_general_data'];

    public function _initialize() {
        parent::_initialize();
        $this->prefix = Config::get('database.prefix');
        $this->communityModel = model('Community');
        $this->view->assign('community',$this->communityModel->where(array('code'=>array('in',parent::getCommunityIdByAuth())))->field('code,name')->select());
    }

    /**
     * 查看
     */
    public function index() {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $this->view->assign([
            'total_building'            => 35200,
            'total_house'               => 219390,
            'total_member'              => 32143,
            'total_lease'               => 174800,
            'total_parking_space'       => 321,
            'parking_space_percent'     => 430,
            'total_vehicle'             => 2324,
            'total_pet'                 => 132,
            'total_repair_pending'      => '80%',
            'total_repair_processing'   => '32%',
            'uploadmode'                => $uploadmode
        ]);

        return $this->view->fetch();
    }

    /**
     * 获取普通统计数据
     * @return \think\response\Json
     */
    public function get_general_data(){
        $result = array();
        $community_code = $this->request->request('code');

        $buildingHouse = $this->getTotalBuildingAndHouse($community_code);
        $result['total_building'] = $buildingHouse['building'];
        $result['total_house'] = $buildingHouse['house'];

        $parking = $this->getParkingSpace($community_code);
        $result['total_parking_space'] = $parking['space'];
        $result['parking_space_percent'] = $parking['percent'];

        $result['total_member'] = $this->getTotalMember($community_code);
        $result['total_lease'] = $this->getTotalLease($community_code);
        $result['total_vehicle'] = $this->getTotalVehicle($community_code);
        $result['total_pet'] = $this->getTotalPet($community_code);

        $repair = $this->getRepair($community_code);
        $result['total_repair_pending'] = $repair['pending'];
        $result['total_repair_processing'] = $repair['processing'];

        return json($result);
    }

    private function getTotalBuildingAndHouse($community_code) {
        $where = array(
            'code' => $community_code
        );
        $result = model('community')->where($where)->find();
        return array('building' => $result['total_building'],'house' => $result['total_owner']);
    }

    private function getTotalMember($community_code) {
        $where = array(
            'community_code' => $community_code
        );
        $count = model('Member')->where($where)->count();
        return $count;
    }

    private function getTotalLease($community_code) {
        $where = array(
            'community_code' => $community_code,
            'owner_type' => 3
        );
        $count = model('Member')->where($where)->count();
        return $count;
    }

    private function getParkingSpace($community_code) {
        $where = array(
            'community_code' => $community_code
        );
        $space = model('ParkingSpace')->where($where)->count();
        $spaceUse = model('ParkingSpaceUse')->where($where)->count();
        $percent = sprintf('%s%%',round($spaceUse*100/$space,2));
        return array('space'=>$space,'percent'=>$percent);
    }

    private function getTotalVehicle($community_code) {
        $where = array(
            'community_code' => $community_code
        );
        $memberIds = model('Member')->where($where)->column('id');
        $count = model('Vehicle')->where(array('member_id'=>array('in',$memberIds)))->count();
        return $count;
    }

    private function getTotalPet($community_code) {
        $where = array(
            'community_code' => $community_code
        );
        $memberIds = model('Member')->where($where)->column('id');
        $count = model('Pet')->where(array('member_id'=>array('in',$memberIds)))->count();
        return $count;
    }

    private function getRepair($community_code) {
        $where = array(
            'community_code' => $community_code,
            'status' => 0
        );
        $pending = model('Repair')->where($where)->count();
        $where['status'] = 1;
        $processing = model('Repair')->where($where)->count();
        return array('pending'=>$pending,'processing'=>$processing);
    }

    //获取小区最新活动
    public function get_activity() {
        $community_code = $this->request->request('code');
        $where = array(
            'status' => 1,
            'community_code' => $community_code
        );
        $activity = model('Activity')->where($where)->order('id desc')->field('id,title,create_time')->limit(0,10)->select();
        $this->view->assign('activity',$activity);
        echo $this->view->fetch('activity');
    }

    //获取最新报修申请
    public function get_repair() {
        $statusText = array(__('Pending'),__('Processing'),__('Processed'));
        $color = array('#e74c3c','#3498db','#18bc9c');
        $community_code = $this->request->request('code');
        $where = array(
            'community_code' => $community_code
        );
        $repair = model('Repair')->with('member')->where($where)->order('id desc')->limit(0,10)->select();
        foreach ($repair as &$item) {
            $item['statusText'] = $statusText[$item['status']];
            $item['color'] = $color[$item['status']];
        }
        $this->view->assign('repair',$repair);
        echo $this->view->fetch('repair');
    }

    //获取每月收费统计数据
    public function get_expenses() {
        $community_code = $this->request->request('code');
        $community = $this->communityModel->where(array('code'=>$community_code))->find();
        list($legend,$xAxis,$series,$units) = array(array(),array(),array(),__('RMB'));
        $sql = 'select m.mon,ifnull(total,0) as total,ifnull(paid,0) as paid from ( ';
        $sql .= 'select concat(date_format(last_day(now() - interval 12 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 11 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 10 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 9 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 8 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 7 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 6 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 5 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 4 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 3 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 2 month),\'%Y-%m\'),\'\') as mon union all ';
        $sql .= 'select concat(date_format(last_day(now() - interval 1 month),\'%Y-%m\'),\'\') as mon ';
        $sql .= ') m left join ';
        $sql .= '(select sum(amount_total) as total,sum(amount_paid) as paid,from_unixtime(create_time,\'%Y-%m\') as create_time ';
        $sql .= 'from '.$this->prefix.'expenses ';
        $sql .= 'where community_code=\''.$community_code.'\' ';
        $sql .= 'group by from_unixtime(create_time,\'%Y-%m\')';
        $sql .= ') e on (m.mon=e.create_time)';
        $expenses = Db::query($sql);

        list($totalData,$paidData) = array();

        foreach ($expenses as $expens) {
            $totalData[] = $expens['total'];
            $paidData[] = $expens['paid'];
            $xAxis[] = $expens['mon'];
        }

        $series[] = array(
            'name' => __('Amount total'),
            'type' => 'line',
            'smooth' => true,
            'data' => $totalData
        );
        $series[] = array(
            'name' => __('Amount paid'),
            'type' => 'line',
            'smooth' => true,
            'data' => $paidData
        );
        foreach ($series as $serie) {
            $legend[] = $serie['name'];
        }
        $echart = array(
            'title' => sprintf('%s（%s 至 %s）',__('Expenses statistics'),reset($expenses)['mon'],end($expenses)['mon']),
            'subTitle' => sprintf('-- %s',$community['name']),
            'legend' => $legend,
            'xAxis' => $xAxis,
            'series' => $series,
            'units' => $units
        );
        return json($echart);
    }

}
