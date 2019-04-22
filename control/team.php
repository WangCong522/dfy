<?php
/**
 *
 ***/
defined('InShopBN') or exit('Access Invalid!');
class teamControl extends BaseMemberControl
{
    private $group_model;
    private $users_model;
    private $net_model;
    private $biz_model;
    private $record_model;
    private $bonus_model;
    public function __construct(){
        parent::__construct();
        $this->group_model = Model('group');
        $this->users_model = Model('member');
        $this->net_model = Model('net');
        $this->biz_model = Model('biz');
        $this->record_model = Model('record');
        $this->bonus_model = Model('bonus');
        Tpl::output('Team_index_active', "active");
    }
    //注册页面
    public function registerOp(){
        $member = Model('member');
        $memberInfo = $member->getMemberInfoByID($this->userid);
        if($memberInfo['group_id'] == "5"){
            showMessage('你还是普通会员不可以查看注册会员！','','html','error');
        }else{
            $biz = Model('biz');
            $userInfo = $biz->getOneBizByUid($this->userid);
            // var_dump($userInfo);exit;
            if (!$userInfo['uid']) {
                $ServiceStation = $this->reg_ServiceStationOp();
                if($ServiceStation){
                    $this->member_register();
                }
            }else{
                $this->member_register();
            }
            Tpl::showpage('team.register');
        }
    }
    //注册页面数据读取
    public function member_register(){
        $id = $_REQUEST['id'] + 0;
        if (C('reg_auto_username')) {
            //获取系统生成的用户名
            $username = $this->generate_username(6, C('reg_auto_username_prefix'));
            Tpl::output('username', $username);
        }
        if ($id) {
            $NetInfo = $this->net_model->getNetByID($id);
            $tjrInfo = $this->users_model->getMemberInfoByID($NetInfo['uid']);
            $jdrInfo = $this->net_model->getJdr($NetInfo['uid']);
            Tpl::output('tjrInfo', $tjrInfo);
            Tpl::output('jdrInfo', $jdrInfo);
        } else {
            $tjrInfo = $this->users_model->getMemberInfoByID($this->userid);
            $jdrInfo = $this->net_model->getJdr($this->userid);
            Tpl::output('tjrInfo', $tjrInfo);
            Tpl::output('jdrInfo', $jdrInfo);
        }
        $setting = array('tel' => C("reg_tel_key"), 'idcard' => C("reg_idcard_key"), 'qq' => C("reg_qq_key"), 'addr' => C("reg_addr_key"));
        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        Tpl::output('setting', $setting);
        Tpl::output('groupList', $groupList);
        Tpl::output('Team_register_dian', "active");
    }
    //自动为用户随机生成用户名(长度6-13)
    private function generate_username($length = 6, $str = 'SFN'){
        // 密码字符集，可任意添加你需要的字符
        $chars = '0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组$chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $username = str . $password;
        if ($this->check_username($username)) {
            return $str . $password;
        } else {
            $this->generate_username(8);
        }
    }
    //检查生成username是否重复
    private function check_username($username){
        $have = $this->users_model->getMemberInfo(array('username' => $username));
        if ($have) {
            return false;
        }
        return true;
    }
    //新用户注册数据
    public function do_registerOp(){
        $yhm = trim($_REQUEST['yhm']);
        $dlmm = trim($_REQUEST['dlmm']);
        $jymm = trim($_REQUEST['jymm']);
        $hyjb = $_REQUEST['hyjb'];
        $group_id = $_REQUEST['group_id'];
        $email = $_REQUEST['email'];
        //$mbwt = $_REQUEST['mbwt'];
        //$mbda = $_REQUEST['mbda'];
        $tjr = trim($_REQUEST['tjr']);
        $jdr = trim($_REQUEST['jdr']);
        $scwz = $_REQUEST['scwz'];
        $sfzh = $_REQUEST['sfzh'];
        $dz = $_REQUEST['dz'];
        $tel = $_REQUEST['tel'];
        $qq = $_REQUEST['qq'];
        $name = $_REQUEST['name'];
        $sex = $_REQUEST['sex'];
        $ssname = $_REQUEST['ssname'];
        $users = Model("member");
        /**
         * 验证
         */
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            //array("input" => $_REQUEST["yhm"], "require" => "true", 'validator' => 'mobile', "message" => '用户名必须为正确手机号'),
            array("input" => $_REQUEST["name"], "require" => "true", 'validator' => 'chinese', "message" => '真实姓名必须为中文'),
        );
        $error = $obj_validate->validate();
        if ($error) {
            $this->error($error);
        }
        $have_username = $this->check_username($yhm);
        if (!$have_username) {
            $this->error('用户名重复！');
        }
        //验证推荐人是否存在
        $tjr_info = $this->select_tjr($tjr);
        //验证接点人
        $jdr_uid = $this->check_wz($jdr, $scwz);
        //验证服务站是否存在并且启用
        $ssInfo = $this->select_ss($ssname);
        //报单费
        $lsk = $this->group_model->getOneGroupReField($group_id, 'lsk');
        //添加用户到表
        $data = array(
            'username' => $yhm,
            'password' => md5(trim($dlmm)),
            'jy_pwd' => md5(trim($jymm)),
            'status' => 0,
            'time' => time(),
            'level_id' => 1,
            'num' => 0,
            'login_status' => 0,
            'login_time' => time(),
            'name' => $name,
            //'problem' => $mbwt,
            //'answer' => $mbda,
            'idcard' => $sfzh,
            'address' => $dz,
            'tel' => $tel,
            'qq' => $qq,
            'email' => $email,
            'sex' => $sex,
            'rid' => $tjr_info['id'],
            'pid' => $jdr_uid,
            'ssid' => $ssInfo['id'],
            'ssuid' => $ssInfo['uid'],
            'ssname' => $ssname,
            'group_id' => $group_id,
            'begin_group' => $group_id,
            'rname' => $tjr_info['username'],
            'lsk' => $lsk,
        );
        $res = $users->addMember($data);
        if ($res) {
            $check = $users->register(array('uid' => $res, 'pid' => $jdr_uid, 'tjr' => $tjr_info, 'scwz' => $scwz, 'userid' => $this->userid, 'lsk' => $lsk));
        }
        if ($check) {
            $this->success('注册成功');
        } else {
            $this->error('注册用户失败，请刷新后重试');
        }
    }
    //自动给用户选择左区还是右区
    private function auto_chose_area(){
        $where = "l_id = 0 or r_id = 0";
        $pidInfo = $this->net_model->getNetInfo($where);
        if ($pidInfo) {
            $return['uid'] = $pidInfo['uid'];
            if ($pidInfo['l_id'] == 0) {
                $return['area'] = 1;
                return $return;
            } elseif ($pidInfo['r_id'] == 0) {
                $return['area'] = 2;
                return $return;
            }
        }
        return 0;
    }
    //检测用户下级
    private function check_wz($username, $wz){
        $net = Model('net');
        $info = $net->select_wz($username);
        if ($info == 0) {
            $this->error('该接点用户尚未激活,不能添加下级');
        } elseif ($info == -1) {
            $this->error('该接点用户不存在,请检查');
        } else {
            if ($wz == 1) {
                if (empty($info['l_id'])) {
                    return $info['uid'];
                } else {
                    $this->error('该接点用户的市场位置左区已满!请重新选择');
                }
            } else {
                if (empty($info['r_id'])) {
                    return $info['uid'];
                } else {
                    $this->error('该接点用户的市场位置右区已满!请重新选择');
                }
            }
        }
    }
    //检测用户是否存在(检查侧推荐人是否存在)
    private function select_tjr($tjr){
        $net = Model('net');
        $re = $net->select_tjr($tjr);
        if ($re) {
            return $re;
        } else {
            $this->error('推荐人不存在,请检查!');
        }
    }
    //检查服务站是否存在
    private function select_ss($name){
        $re = $this->biz_model->getOneBizByUsername($name);
        if ($re) {
            if ($re['is_show']) {
                return $re;
            } else {
                $this->error('该服务站未启用！请更换服务站');
            }
        } else {
            $this->error('服务站不存在,请检查!');
        }
        exit;
    }
    //申请服务站
    public function ServiceStationOp(){
        $uid = $this->userid;
        $member = Model('member');
        $memberInfo = $member->getMemberInfoByID($uid);
        if($memberInfo['group_id'] == 5){
            showMessage('你还是普通会员不可以查看注册会员！','','html','error');
        }else{
            $biz_model = Model('biz');
            $bizInfo = $biz_model->getOneBizByUid($uid);
            Tpl::output('bizInfo', $bizInfo);
            Tpl::output('Team_ss_dian', "active");
        }
        Tpl::showpage('team.ss');
    }
    //申请服务站
    public function applyServiceStationOp(){
        $uid = $this->userid;
        $member = Model('member');
        $memberInfo = $member->getMemberInfo($uid);
        if($memberInfo['group_id'] == 5){
            showMessage('你还是普通会员不可以查看注册会员！','','html','error');
        }else{
            $username = $this->username;
            $biz_model = Model('biz');
            $bizInfo = $biz_model->getOneBizByUid($uid);
            if ($bizInfo) {
                showMessage("您已申请!");
            } else {
                $insert_data = array('uid' => $uid, 'addtime' => time(), 'username' => $username);
                $result = $biz_model->add($insert_data);
                if ($result) {
                    showMessage("申请成功！");
                }
            }
        }
    }
    //注册会员申请服务站
    public function reg_ServiceStationOp(){
        $uid = $this->userid;
        $username = $this->username;
        $biz_model = Model('biz');
        $bizInfo = $biz_model->getOneBizByUid($uid);
        if ($bizInfo) {
            return true;
        } else {
            $insert_data = array('uid' => $uid, 'addtime' => time(), 'username' => $username);
            $result = $biz_model->add($insert_data);
            if ($result) {
                return true;
            }else{
                return false;
            }
        }
    }
    //直推列表
    public function zhituilistOp(){
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $where = array('rid' => $this->userid, 'status' => 1);
        $memberList = $this->users_model->getMemeberInfoList($where, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('memberList', $memberList);
        Tpl::output('Team_zhituiList_dian', "active");
        Tpl::showpage('team.zhituiList');
    }
    //待激活会员
    public function activationOp(){
        $this->check_jy_pwd();
        $status = $_GET['status'] ? 'yes' : 'no';
        $uid = $this->userid;
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $condition_users = array('u_status' => $status, 'u_ssuid' => $uid, 'order' => 'id asc', 'field' => 'users.*,group.name as group_name');
        $userList = $this->group_model->getJoinList($condition_users, $page);
        if ($status == 'yes' && $userList) {
            foreach ($userList as &$item) {
                $item['jh_time'] = $this->record_model->getOneRecordReField($item['id'], 'jh_time');
            }
        }
        Tpl::output('show_page', $page->show());
        Tpl::output('list', $userList);
        Tpl::output('Team_activation_dian', "active");
        Tpl::showpage('team.activation');
    }
    //删除会员
    public function delUserOp(){
        $uid = $_REQUEST['id'] + 0;
        $id = $this->net_model->getOneNetReField($uid, 'id');
        $re = Model("member")->DelUser($id, $this->userid);
        if ($re['status']) {
            $this->success('删除成功');
        } else {
            $this->error($re['msg']);
        }
    }
    //删除已激活的会员
    public function delUserNewOp(){
        $id = intval($_POST['id']);
        if ($id) {
            $userInfo = $this->users_model->getMemberInfoByID($id);
            if ($userInfo['status'] == 1) {
                $this->error("不能删除已激活用户");
            } else {
                $check = $this->users_model->del($id);
            }
        }
        if ($check) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }
    public function confirm_addOp(){
        $id = $_REQUEST['id'];
        $uid = $this->userid;
        $bizInfo = $this->biz_model->getOneBizByUid($uid);
        $userInfo = $this->users_model->getMemberInfoByID($uid);
        //用户信息
        $jihuouserinfo = $this->users_model->getMemberInfoByID($id);
        //被激活用户信息
        if ($jihuouserinfo['status'] == 1) {
            $this->error('该用户不需要激活!');
        }
        if (empty($bizInfo)) {
            $this->error('请先申请成为服务站！');
        }
        if ($userInfo['bao_balance'] < $jihuouserinfo['lsk']) {
            $this->error('报单需要' . $jihuouserinfo['lsk'] . '报单币,请先转换!');
        }
        $Net_info = $this->net_model->getNetByUser($id);
        //树形信息
        //树形信息  用户id   服务站id    激活账户报单币     推荐人id    激活人信息
        $re = $this->users_model->doActive($Net_info['id'], $uid, $bizInfo['id'], $jihuouserinfo['lsk'], $jihuouserinfo['rid'], $jihuouserinfo);
        /*  header("Content-type: text/html; charset=utf-8");
            print_r($re);*/
        if ($re['status']) {
            //触发奖项
            //报单配送
            // $this->bonus_model->baodanpeisong($jihuouserinfo['id'], $jihuouserinfo['group_id']);
            //服务站补贴
            // $this->bonus_model->ssSubsidy($jihuouserinfo['ssid'], $jihuouserinfo['id'], $jihuouserinfo['username'], $jihuouserinfo['lsk']);
            //销售奖
            // $this->bonus_model->xiaoshou($jihuouserinfo['id'], $jihuouserinfo['lsk'], '直推奖');
            //见点奖
            // $this->bonus_model->jiandian($jihuouserinfo['pid'], 1, $jihuouserinfo['id'], $jihuouserinfo['username']);
            //对碰奖 (量碰)
            // $this->bonus_model->dpj($jihuouserinfo['id']);
            // $this->bonus_model->liangpengjiangjisuan($jihuouserinfo['id']);
            //层碰奖
            // $this->bonus_model->cpj();
            $this->success("");
        } else {
            $this->error($re['msg']);
        }
    }
    public function WtjgdrOp(){
        Tpl::output('list', $list);
        Tpl::output('status', $status);
        Tpl::output('Team_Wtjgdr_dian', "active");
        Tpl::showpage('team.Wtjgdr');
    }
    public function sctjOp(){
        $userid = $this->userid;
        $time = strtotime(date("Y-m-d")) + 86400;
        $days = 27;
        $stime = $time - $days * 86400;
        $etime = $time - 1;
        $NetInfo = Model("net")->getNetByUser($userid);
        $LNet = Model("net")->getNetByID($NetInfo['l_id']);
        $RNet = Model("net")->getNetByID($NetInfo['r_id']);
        $parent = Model('parent');
        $l_id = $LNet['uid'];
        $r_id = $RNet['uid'];
        $field = ' COUNT(*) as allnum ';
        $field .= ' ,DAY(FROM_UNIXTIME(users.login_time)) as dayval ';
        $_group = 'dayval';
        $where = array('users.status' => 1);
        $where['parent.parent'] = array('like', "%,{$userid},{$l_id},%");
        $where['users.login_time'] = array('between', array($stime, $etime));
        $list = Model("member")->getStat($where, $field, 0, '', $_group);
        $arr_c = array();
        for ($i = $days; $i >= 0; $i--) {
            $str = date("d", time() - $i * 86400) + 0;
            $arr_c[] = $str;
        }
        $t_c = array();
        foreach ($arr_c as $k => $v) {
            $t_c[$v] = $k;
        }
        $l_data = $r_data = array();
        for ($i = 0; $i <= $days; $i++) {
            $l_data[$i] = 0;
            $r_data[$i] = 0;
        }
        foreach ($list as $k => $v) {
            $x = $t_c[$v['dayval']];
            $l_data[$x] += $v['allnum'];
        }
        $where['parent.parent'] = array('like', "%,{$userid},{$r_id},%");
        $list = Model("member")->getStat($where, $field, 0, '', $_group);
        foreach ($list as $k => $v) {
            $x = $t_c[$v['dayval']];
            $r_data[$x] += $v['allnum'];
        }
        $stat_arr['xAxis'] = array('categories' => $arr_c);
        $stat_arr['series'] = array();
        $stat_arr['series'][0] = array('name' => '左市场', 'data' => $l_data);
        $stat_arr['series'][1] = array('name' => '右市场', 'data' => $r_data);
        $stat_arr['yAxis'] = array('title' => array('text' => '业绩'));
        $stat_arr['title'] = array('text' => '<b>业绩统计</b>', 'x' => -20);
        $stat_arr['chart'] = array('type' => 'line');
        $stat_arr['colors'] = array(0 => '#058DC7', 1 => '#ED561B', 2 => '#8bbc21', 3 => '#0d233a');
        $stat_json = json_encode($stat_arr);
        Tpl::output('sctj_stat_json', $stat_json);
        $myTeam = $parent->getParentList(array('parent' => $this->userid));
        $myTeamNum = count($myTeam);
        if ($this->userid == 1) {
            $myTeamNum += 1;
        }
        Tpl::output('myTeamNum', $myTeamNum);
        Tpl::output('Team_sctj_dian', "active");
        Tpl::showpage('team.sctj');
    }
}