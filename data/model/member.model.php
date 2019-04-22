<?php
/**
 * 会员模型
 *
 */
defined('InShopBN') or exit('Access Invalid!');
class memberModel extends Model{
    private $net_model;
    private $biz_model;
    private $layerlist_model;
    private $bonuslog_model;
    private $bonuslaiyuan_model;
    private $group_model;
    public function __construct(){
        parent::__construct('member');
        $this->net_model = Model('net');
        $this->biz_model = Model('biz');
        $this->layerlist_model = Model('layerlist');
        $this->bonuslog_model = Model('bonuslog');
        $this->bonuslaiyuan_model = Model('bonuslaiyuan');
        $this->group_model = Model('group');
    }

    /**
     * 查询验证码
     */
    public function verify($condition, $field = '*', $master = false){
        return $this->table('verifycodes')->field($field)->where($condition)->master($master)->find();
    }
    /**
     * 验证码存储
     */
    public function addverify($param){
        if (empty($param)) {
            return false;
        }
        $insert_id = $this->table('verifycodes')->insert($param);
        return $insert_id;
    }
    /**
     * 验证码更新
     */
    public function updateverify($condition, $data){
        $update = $this->table('verifycodes')->where($condition)->update($data);
        return $update;
    }
    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false){
        return $this->table('users')->field($field)->where($condition)->master($master)->find();
    }
    /**
     * 检查用户验证码
     */
    public function getVerifyInfo($condition, $field = '*', $master = false){
        return $this->table('verifycodes')->field($field)->where($condition)->master($master)->count();
    }
    /**
     * 检查手机号码是否存在
     */
    public function phone_is($condition,$field="*",$master = false){
        return $this->table("users")->field($field)->where($condition)->master($master)->count();
    }
    /**
     * 检查用户名是否存在
     */
    public function username_id($condition,$field="*",$master = false){
        return $this->table("users")->field($field)->where($condition)->master($master)->count();
    }
    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*'){
        $member_info = $this->getMemberInfo(array('id' => $member_id), '*', true);
        return $member_info;
    }
    public function getMemberInfoByUsername($username, $fields = '*'){
        $member_info = $this->getMemberInfo(array('username' => $username), '*', true);
        return $member_info;
    }
    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = ''){
        return $this->table('users')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMembersList($condition, $page = null, $order = 'id desc', $field = '*'){
        return $this->table('users')->field($field)->where($condition)->page($page)->order($order)->select();
    }
    public function getStat($where, $field = '*', $page = 0, $order = '', $group = ''){
        return $this->table('users,parent')->field($field)->join('inner')->on('users.id=parent.uid')->where($where)->page($page)->order($order)->group($group)->select();
    }
    public function getMemberLevel($typ){
        $type = array('low' => ' 会员', 'middle' => '一星会员', 'high' => '企业商户');
        return $type[$typ];
    }
    public function getChild($uid){
        $map = array();
        $map['parent.parent'] = array('like', "%,{$uid},%");
        $map['users.status'] = 1;
        return $this->table('parent,users')->join('inner')->on('users.id = parent.uid')->where($map)->select();
    }
    /**
     * 取单个内容返回字段
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneMemberReField($id, $field){
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'users';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result[$field];
        } else {
            return false;
        }
    }
    //查询用户账户状态
    public function GetUserLevel($id){
        $level = Model('level');
        $account = Model('account');
        $info = $this->getMemberInfoByID($id);
        $LevelInfo = $level->getLevelById($info['level_id']);
        $AccountInfo = $account->getAccountById($LevelInfo['account_id']);
        return $arr = array('status' => $AccountInfo[$info['state']], 'state' => $info['state']);
    }
    //升级用户状态
    public function UpLevel($id, $amount){
        $res = array('status' => 0);
        if ($amount <= 0) {
            $res = array('status' => 0, 'msg' => '金额错误');
            return $res;
        }
        $trans = Model('trans');
        $users = Model("member");
        $message = Model('Message');
        $UserInfo = $users->getMemberInfoByID($id);
        if ($UserInfo['bao_balance'] < $amount) {
            $res = array('status' => 0, 'msg' => '余额不足');
            return $res;
        }
        $old_amount = $UserInfo['bao_balance'];
        $new_amount = $UserInfo['bao_balance'] - $amount;
        try {
            $this->beginTransaction();
            $data = array();
            $data['bao_balance'] = array('exp', "bao_balance - {$amount}");
            $where = array('id' => $id);
            $r = $users->editMember($where, $data);
            if (!$r) {
                $res['msg'] = '扣款失败';
                throw new Exception();
            }
            $data = array('uid' => $id, 'money_type' => '报单币', 'money' => 0 - $amount, 'type' => '升级消耗', 'time' => time(), 'intro' => '升级', 'cod' => 'bao', 'old_amount' => $old_amount, 'new_amount' => $new_amount);
            $r = $this->table('trans')->insert($data);
            if (!$r) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }
            if ($UserInfo['state'] == 'low') {
                $r = $this->editMember(array('id' => $id), array('state' => 'middle'));
            } elseif ($UserInfo['state'] == 'middle') {
                $r = $this->editMember(array('id' => $id), array('state' => 'high'));
            }
            if (!$r) {
                $res['msg'] = '升级失败';
                throw new Exception();
            }
            $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $id, 'title' => '升级消耗' . $amount . '报单币', 'addtime' => time(), 'content' => '升级消耗' . $amount . '报单币');
            $this->table('message')->insert($data);
            $this->commit();
            $res['status'] = 1;
            return $res;
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        return $res;
    }
    /**
     * 删除会员
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function del($id){
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('users', $where);
            return $result;
        } else {
            return false;
        }
    }
    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition){
        return $this->table('users')->where($condition)->count();
    }
    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data){
        $update = $this->table('users')->where($condition)->update($data);
        return $update;
    }
    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(), $reg = false){
        if (empty($member_info) || !is_array($member_info)) {
            return;
        }
        setBnCookie('is_login', '1');
        setBnCookie('uid', $member_info['id']);
        setBnCookie('username', $member_info['username']);
        // 自动登录
        if ($member_info['auto_login'] == 1) {
            $this->auto_login();
        }
    }
    /**
     * 获取会员信息
     *
     * @param    array $param 会员条件
     * @param    string $field 显示字段
     * @return    array 数组格式的返回结果
     */
    public function infoMember($param, $field = '*'){
        if (empty($param)) {
            return false;
        }
        //得到条件语句
        $condition_str = $this->getCondition($param);
        $param = array();
        $param['table'] = 'users';
        $param['where'] = $condition_str;
        $param['field'] = $field;
        $param['limit'] = 1;
        $member_list = Db::select($param);
        $member_info = $member_list[0];
        return $member_info;
    }
    /**
     * 7天内自动登录
     */
    public function auto_login(){
        // 自动登录标记 保存7天
        setBnCookie('auto_login', encrypt($_SESSION['member_id'], MD5_KEY), 7 * 24 * 60 * 60);
    }
    /**
     * 注册商城会员
     *
     * @param   array $param 会员信息
     * @return  array 数组格式的返回结果
     */
    public function addMember($param){
        if (empty($param)) {
            return false;
        }
        $insert_id = $this->table('users')->insert($param);
        return $insert_id;
    }
    /**
     * 会员登录检查
     *
     */
    public function checkloginMember(){
        if ($_SESSION['is_login'] == '1') {
            @header("Location: index.php");
            exit;
        }
    }
    public function GetXDate($overdue_time){
        $d = $overdue_time - time();
        if ($d > 0) {
            $d = floor($d / 86400);
            return '距离续费还有' . $d . '天';
        } else {
            return '您的账户已到期,请尽快续费!到期后收益会停止';
        }
    }
    /**
     * 获取信息
     *
     * @param    array $param 条件
     * @param    string $field 显示字段
     * @return    array 数组格式的返回结果
     */
    public function getMemeberInfoList($condition, $page = ''){
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'users';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order']) ? 'id desc' : $condition['order'];
        $result = Db::select($param, $page);
        return $result;
    }
    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition){
        $condition_str = '';
        if ($condition['id'] != '') {
            $condition_str .= " and users.id = '" . $condition['id'] . "'";
        }
        if ($condition['uid'] != '') {
            $condition_str .= " and users.uid = '" . $condition['uid'] . "'";
        }
        if ($condition['ssid'] != '') {
            $condition_str .= " and users.ssid = '" . $condition['ssid'] . "'";
        }
        if ($condition['status'] != 1) {
            $condition_str .= " and users.status = '" . $condition['status'] . "'";
        }
        if ($condition['rid'] != '') {
            $condition_str .= " and users.rid = '" . $condition['rid'] . "'";
        }
        return $condition_str;
    }
    private function getCondition($conditon_array){
        $condition_sql = '';
        if ($conditon_array['id'] != '') {
            $condition_sql .= " and id= '" . intval($conditon_array['id']) . "'";
        }
        if ($conditon_array['id'] != '') {
            $condition_sql .= " and id= '" . intval($conditon_array['id']) . "'";
        }
        if ($conditon_array['no_id'] != '') {
            $condition_sql .= " and id<> '" . intval($conditon_array['no_id']) . "'";
        }
        if ($conditon_array['username'] != '') {
            $condition_sql .= " and username='" . $conditon_array['username'] . "'";
        }
        if ($conditon_array['password'] != '') {
            $condition_sql .= " and password='" . $conditon_array['password'] . "'";
        }
        return $condition_sql;
    }
    public function register($para){
        $net = Model("net");
        $record = Model("record");
        $res = array('status' => 0, 'msg' => '注册失败');
        if (empty($para)) {
            $res['msg'] = '参数不能为空';
            return $res;
        }
        $uid = $para['uid'];
        $pid = $para['pid'];
        $scwz = $para['scwz'];
        $userid = $para['userid'];
        $tjr_info = $para['tjr'];
        $lsk = $para['lsk'];
        try {
            $this->beginTransaction();
            $new_user_id = $uid;
            if (!$new_user_id) {
                throw new Exception();
            }
            $data = array('num' => 6001 + $new_user_id);
            $re = $this->editMember(array('id' => $new_user_id), $data);
            if (!$re) {
                $res['msg'] = '编号失败!,请刷新后重试!';
                throw new Exception();
            }
            $star_l[1] = 0;
            $star_l[2] = 0;
            $star_l[3] = 0;
            $star_l[4] = 0;
            $star_l[5] = 0;
            $star_l[6] = 0;
            $star_l[7] = 0;
            $star_l[8] = 0;
            $star_l[9] = 0;
            $star_l[10] = 0;
            $star_ls = serialize($star_l);
            $data = array('uid' => $new_user_id, 'pid' => $pid, 'status' => 0, 'area' => $scwz, 'l_num_star' => $star_ls, 'r_num_star' => $star_ls);
            $new_id = $net->addNet($data);
            if (!$new_id) {
                $res['msg'] = "写入网络失败!,请刷新后重试!";
                throw new Exception();
            }
            //更新用户到接点人
            $jdr_status = $net->bind_user($pid, $scwz, $new_id, $lsk);
            if (!$jdr_status) {
                $res['msg'] = '更新节点失败!,请刷新后重试!';
                throw new Exception();
            }
            //注册记录
            $data = array('new_id' => $new_id, 'jdr_id' => $pid, 'zc_id' => $userid, 'tjr_id' => $tjr_info['id'], 'time' => time(), 'uid' => $new_user_id);
            $recordId = $record->addRecord($data);
            if (!$recordId) {
                $res['msg'] = '写入记录失败!,请刷新后重试!';
                throw new Exception();
            }
            //
            $re = $net->ChangeLay($pid, $new_id);
            if (!$re) {
                $res['msg'] = '更改层数失败!,请刷新后重试!';
                throw new Exception();
            }
            $re = $this->table("net,parent")->field('net.uid,parent.parent')->join('inner')->on('net.uid = parent.uid')->where(array('net.id' => $pid))->find();
            if (!$re) {
                $res['msg'] = '更新信息失败';
                throw new Exception();
            }
            $parent_id = $re['uid'];
            $parent = $re['parent'];
            if (!$parent) {
                $parent = ",";
            }
            $parent .= $parent_id . ',' . $new_id . ',';
            $data = array('uid' => $new_user_id, 'net_id' => $new_id, 'parent_id' => $parent_id, 'parent' => $parent);
            $re = $this->table("parent")->insert($data);
            if (!$re) {
                $res['msg'] = '写入信息失败';
                throw new Exception();
            }
            //*////
            //给上级新增业绩
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;
        return $res;
    }
    public function DelUser($id, $userid){
        $record = Model('record');
        $net = Model('net');
        $iplist = Model('iplist');
        $res = array('status' => 0);
        $NetInfo = $net->getNetByID($id);
        $UserInfo = $this->getMemberInfoByID($NetInfo['uid']);
        $uid = $NetInfo['uid'];
        if ($NetInfo['pid'] == 0) {
            $res['msg'] = '不能删除根节点用户';
            return $res;
        }
        if ($NetInfo['status'] == 1) {
            $res['msg'] = '不能删除已激活用户';
            return $res;
        }
        try {
            $this->beginTransaction();
            $re = $net->del($id);
            if (!$re) {
                $res['msg'] = "删除节点失败";
                throw new Exception();
            }
            $LInfo = $net->getNetInfo(array('l_id' => $id));
            if ($LInfo) {
                $re = $net->editNet(array('l_id' => $id), array('l_id' => 0));
            } else {
                $re = $net->editNet(array('r_id' => $id), array('r_id' => 0));
            }
            if (!$re) {
                $res['msg'] = "更新节点失败";
                throw new Exception();
            }
            $re = $this->del($uid);
            if (!$re) {
                $res['msg'] = "删除会员失败";
                throw new Exception();
            }
            $re = $record->del_record_new_id($id);
            if (!$re) {
                $res['msg'] = "删除记录失败";
                throw new Exception();
            }
            $re = $iplist->del_by($userid, $id);
            if (!$re) {
                $res['msg'] = "删除IP失败";
                throw new Exception();
            }
            $re = $this->table("parent")->where(array('uid' => $uid))->delete();
            if (!$re) {
                $res['msg'] = "删除PAR失败";
                throw new Exception();
            }
            $re = $this->table("lays")->where(array('uid' => $uid))->delete();
            if (!$re) {
                $res['msg'] = "删除LAY失败";
                throw new Exception();
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;
        return $res;
    }
    //管理员删除会员
    public function adminDel($uid){
        $record = Model('record');
        $net = Model('net');
        $iplist = Model('iplist');
        $res = array('status' => 0);
        $NetInfo = $net->getNetByUser($uid);
        $UserInfo = $this->getMemberInfoByID($uid);
        $status = $UserInfo['status'];
        $net_id = $NetInfo['id'];
        if ($NetInfo['pid'] == 0) {
            $res['msg'] = '不能删除根节点用户';
            return $res;
        }
        $have = $net->getNetInfo(array('pid' => $net_id));
        if ($have) {
            $res['msg'] = '下面还有节点，不能删除';
            return $res;
        }
        try {
            $this->beginTransaction();
            $re = $net->del($net_id);
            if (!$re) {
                $res['msg'] = "删除节点失败";
                throw new Exception();
            }
            $LInfo = $net->getNetInfo(array('l_id' => $net_id));
            if ($LInfo) {
                $re = $net->editNet(array('l_id' => $net_id), array('l_id' => 0));
            } else {
                $re = $net->editNet(array('r_id' => $net_id), array('r_id' => 0));
            }
            if (!$re) {
                $res['msg'] = "更新节点失败";
                throw new Exception();
            }
            $re = $this->del($uid);
            if (!$re) {
                $res['msg'] = "删除会员失败";
                throw new Exception();
            }
            $recInfo = $record->getRecordInfo(array('new_id' => $net_id));
            $re = $record->del_record_new_id($net_id);
            if (!$re) {
                $res['msg'] = "删除记录失败";
                throw new Exception();
            }
            $re = $iplist->del_by($uid, $net_id);
            if (!$re) {
                $res['msg'] = "删除IP失败";
                throw new Exception();
            }
            $par = $this->table("parent")->where(array('uid' => $uid))->find();
            $parent = $par['parent'];
            $re = $this->table("parent")->where(array('uid' => $uid))->delete();
            if (!$re) {
                $res['msg'] = "删除PAR失败";
                throw new Exception();
            }
            $re = $this->table("lays")->where(array('uid' => $uid))->delete();
            if (!$re) {
                $res['msg'] = "删除LAY失败";
                throw new Exception();
            }
            if ($status) {
                $zc_id = $recInfo['zc_id'];
                $Info = $this->getMemberInfoByID($zc_id);
                $amount = 500;
                $data = array();
                $data['bao_balance'] = array('exp', "bao_balance + {$amount}");
                $where = array('id' => $zc_id);
                $r = $this->editMember($where, $data);
                $old_amount = $Info['bao_balance'];
                $new_amount = $Info['bao_balance'] + $amount;
                if (!$r) {
                    $res['msg'] = '加款失败';
                    throw new Exception();
                }
                $data = array('uid' => $zc_id, 'money_type' => '报单币', 'money' => $amount, 'type' => '返还', 'time' => time(), 'intro' => '删除会员返还', 'cod' => 'bao', 'old_amount' => $old_amount, 'new_amount' => $new_amount);
                $r = $this->table('trans')->insert($data);
                if (!$r) {
                    $res['msg'] = '记录失败';
                    throw new Exception();
                }
                $where = array();
                $where['lay'] = 0;
                $where['uid'] = array('in', explode(",", $parent));
                $r = $this->table("lays")->where($where)->delete();
                if (!$r) {
                    $res['msg'] = 'del失败';
                    throw new Exception();
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;
        return $res;
    }
    //后台激活用户
    public function activation_user($uid, $from){
        $jihuouserinfo = $this->getMemberInfoByID($uid);
        $bizInfo = $this->biz_model->getOneBizByUid($jihuouserinfo['ssuid']);
        if ($jihuouserinfo['status'] == 1) {
            showMessage('请勿重复激活！');
        }
        $Net_info = $this->net_model->getNetByUser($uid);
        $re = $this->doActiveAdmin($Net_info['id'], $jihuouserinfo['ssuid'], $jihuouserinfo['ssid'], $jihuouserinfo['lsk'], $jihuouserinfo['rid']);
        if ($re['status']) {
            return true;
        } else {
            $this->retrunMsg($from, 'error', '用户激活失败,请检查');
        }
    }
    public function retrunMsg($from, $type, $msg){
        if ($from == 'index') {
            if ($type == "success") {
                $this->success($msg);
            } else {
                $this->error($msg);
            }
        } else {
            showMessage($msg);
        }
    }
    //netID     注册用户ID    服务站ID   报单币     推荐ID    激活人的信息
    public function doActive($net_id, $userid, $biz_id, $lsk, $rid, $jihuouserinfo = array()){
        $record = Model('record');
        $net = Model('net');
        $bonus_model = Model('bonus');
        $UserInfo = $this->getMemberInfoByID($jihuouserinfo['id']);
        $netInfo = $net->getNetByID($net_id);
        $res = array('status' => 0);
        $recordInfo = $record->getRecordInfo(array('new_id' => $net_id));
        if (!$recordInfo) {
            $res['msg'] = '该用户不存在!';
            return $res;
        }
        if ($recordInfo['status']) {
            $res['msg'] = '该用户不需要激活';
            return $res;
        }
        $pid = $netInfo['pid'];
        if ($pid == 0) {
            $res['msg'] = '无法激活，24小时后自动删除';
            return $res;
        }
        $PNetInfo = $net->getNetByID($pid);
        if ($PNetInfo['l_id'] != $net_id && $PNetInfo['r_id'] != $net_id) {
            $res['msg'] = '该用户位置出错，无法激活，建议删除';
            return $res;
        }
        try {
            $this->beginTransaction();
            //扣除报单币
            $amount = $lsk;
            $data = array();
            $data['bao_balance'] = array('exp', "bao_balance - {$amount}");
            $where = array('id' => $userid);
            $r = $this->editMember($where, $data);
            $old_amount = $UserInfo['bao_balance'];
            $new_amount = $UserInfo['bao_balance'] - $amount;
            if (!$r) {
                $res['msg'] = '扣款失败';
                throw new Exception();
            }
            $data = array('uid' => $userid, 'money_type' => '报单币', 'money' => 0 - $amount, 'type' => '激活会员', 'time' => time(), 'intro' => '激活新会员', 'cod' => 'bao', 'old_amount' => $old_amount, 'new_amount' => $new_amount);
            $r = $this->table('trans')->insertTran($data);
            if (!$r) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }
            $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $userid, 'title' => '激活新会员，扣除' . $amount . '报单币', 'addtime' => time(), 'content' => '扣除' . $amount . '报单币');
            $this->table('message')->insertTran($data);
            $newUserid = $netInfo['uid'];
            $data = array('status' => 1, 'login_status' => 1, 'login_time' => time());
            $where = array('id' => $newUserid);
            $r = $this->editMember($where, $data);
            if (!$r) {
                $res['msg'] = '改变用户状态失败';
                throw new Exception();
            }
            $data = array();
            $data = array('status' => 1);
            $where = array('id' => $net_id);
            $r = $net->editNet($where, $data);
            if (!$r) {
                $res['msg'] = '改变节点状态失败';
                throw new Exception();
            }
            $data = array('status' => 1, 'jh_time' => time());
            $where = array('new_id' => $net_id);
            $r = $record->editRecord($where, $data);
            if (!$r) {
                $res['msg'] = '改变记录状态失败';
                throw new Exception();
            }
            //更新业绩
            $this->addArea($newUserid, $netInfo['area'], $lsk);
            //更新PV
            $this->updatePv($jihuouserinfo, $lsk);
            /*            //更新星级
                        // $this->xingjiUp($newUserid);
                        $topUserInfo = $this->getMemberInfoByID($PNetInfo['uid']);
            
                        $this->newUpdateSatrLevel($UserInfo,$netInfo);*/
            //更新layerlist用于层碰
            $this->addLayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], 1);
            //更新lp_laylist 用于量碰
            $this->addLp_LayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], $netInfo['lay_num'], 1);
            //更新服务站总业绩
            $data = array('total' => array('exp', "total + {$lsk}"), 'count' => array('exp', "count + 1"));
            $where = array('id' => $biz_id);
            $this->biz_model->editBiz($where, $data);
            $data = array('uid' => $newUserid, 'add_time' => time(), 'net_id' => $net_id);
            $r = $this->table("jiang")->insertTran($data);
            if (!$r) {
                $res['msg'] = '写入奖金记录失败';
                throw new Exception();
            }
            //更新推荐人直推人数
            $data = array('recount' => array('exp', "recount + 1"));
            $where = array('id' => $rid);
            $this->editMember($where, $data);
            $data = array('uid' => $newUserid, 'lay' => '0', 'l_count' => '0', 'r_count' => '0');
            $r = $this->table("lays")->insertTran($data);
            if (!$r) {
                $res['msg'] = '写入层失败';
                throw new Exception();
            }
            //服务站补贴
            $r = $bonus_model->ssSubsidy($jihuouserinfo['ssid'], $jihuouserinfo['id'], $jihuouserinfo['username'], $jihuouserinfo['lsk']);
            if (!$r) {
                // $res['msg'] = '写入服务站补贴失败';
                $res['msg'] = '写入报单奖励失败';
                throw new Exception();
            }
            //销售奖
            //            $r = $bonus_model->xiaoshou($jihuouserinfo['id'], $jihuouserinfo['lsk'], '直推奖');
            //            if (!$r) {
            //                $res['msg'] = '写入销售奖失败';
            //                throw new Exception();
            //            }
            //直推奖励
            $recommendInfo = $this->getMemberInfoByID($jihuouserinfo['rid']);
            $groupInfo = $this->group_model->getOneGroup($jihuouserinfo['group_id']);
            $r = $bonus_model->recommendReward($groupInfo, $jihuouserinfo, $recommendInfo, 2);
            if (!$r) {
                $res['msg'] = '写入直推奖失败';
                throw new Exception();
            }
            //见点奖
            //            $r =$bonus_model->jiandian($jihuouserinfo['pid'], 1, $jihuouserinfo['id'], $jihuouserinfo['username']);
            /* if (!$r) {
                   $res['msg'] = '写入见点奖失败';
                   throw new Exception();
               }*/
            //对碰奖
            // $r = $bonus_model->dpj($jihuouserinfo['id']);
            $r = $bonus_model->liangpengjiangjisuan($jihuouserinfo['id']);
            if (!$r) {
                // $res['msg'] = '写入对碰奖失败';
                $res['msg'] = '写入量碰奖失败';
                throw new Exception();
            }
            //层碰奖
            $r = $bonus_model->cpj();
            if (!$r) {
                $res['msg'] = '写入层碰奖失败';
                throw new Exception();
            }
            //更新星级
            // $this->xingjiUp($newUserid);
            $topUserInfo = $this->getMemberInfoByID($PNetInfo['uid']);
            $this->newUpdateSatrLevel($UserInfo, $netInfo);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;
        return $res;
    }
    public function updatePv($userInfo, $pv){
        $topInfo = $this->getMemberInfo(['id' => $userInfo['pid']]);
        // $topInfo = $this->getMemberInfo(['id' => $userInfo['id']]);
        $updateCondition = ['id' => $topInfo['id']];
        $updateData = ['pv' => $topInfo['pv'] + $pv, 'newpv' => $topInfo['pv'] + $pv];
        $this->editMember($updateCondition, $updateData);
        if ($userInfo['pid'] == 0) {
            return false;
        }
        $this->updatePv($topInfo, $pv);
    }
    public function doActiveAdmin($net_id, $userid, $biz_id, $lsk, $rid){
        $record = Model('record');
        $net = Model('net');
        $bonus_model = Model('bonus');
        $UserInfo = $this->getMemberInfoByID($userid);
        $netInfo = $net->getNetByID($net_id);
        $jihuouserinfo = $this->getMemberInfo(['id' => $netInfo['uid']]);
        $res = array('status' => 0);
        $recordInfo = $record->getRecordInfo(array('new_id' => $net_id));
        if (!$recordInfo) {
            $res['msg'] = '该用户不存在!';
            return $res;
        }
        if ($recordInfo['status']) {
            $res['msg'] = '该用户不需要激活';
            return $res;
        }
        $pid = $netInfo['pid'];
        if ($pid == 0) {
            $res['msg'] = '无法激活，24小时后自动删除';
            return $res;
        }
        $PNetInfo = $net->getNetByID($pid);
        if ($PNetInfo['l_id'] != $net_id && $PNetInfo['r_id'] != $net_id) {
            $res['msg'] = '该用户位置出错，无法激活，建议删除';
            return $res;
        }
        try {
            $this->beginTransaction();
            // 新加   ---------
            //扣除报单币
            $amount = $lsk;
            $data = array();
            $data['bao_balance'] = array('exp', "bao_balance - {$amount}");
            $where = array('id' => $userid);
            $r = $this->editMember($where, $data);
            $old_amount = $UserInfo['bao_balance'];
            $new_amount = $UserInfo['bao_balance'] - $amount;
            if (!$r) {
                $res['msg'] = '扣款失败';
                throw new Exception();
            }
            $data = array('uid' => $userid, 'money_type' => '报单币', 'money' => 0 - $amount, 'type' => '激活会员', 'time' => time(), 'intro' => '激活新会员', 'cod' => 'bao', 'old_amount' => $old_amount, 'new_amount' => $new_amount);
            $r = $this->table('trans')->insertTran($data);
            if (!$r) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }
            $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $userid, 'title' => '激活新会员，扣除' . $amount . '报单币', 'addtime' => time(), 'content' => '扣除' . $amount . '报单币');
            $this->table('message')->insertTran($data);
            // ------------------
            $newUserid = $netInfo['uid'];
            $data = array('status' => 1, 'login_status' => 1, 'login_time' => time());
            $where = array('id' => $newUserid);
            $r = $this->editMember($where, $data);
            if (!$r) {
                $res['msg'] = '改变用户状态失败';
                throw new Exception();
            }
            $data = array();
            $data = array('status' => 1);
            $where = array('id' => $net_id);
            $r = $net->editNet($where, $data);
            if (!$r) {
                $res['msg'] = '改变节点状态失败';
                throw new Exception();
            }
            $data = array('status' => 1, 'jh_time' => time());
            $where = array('new_id' => $net_id);
            $r = $record->editRecord($where, $data);
            if (!$r) {
                $res['msg'] = '改变记录状态失败';
                throw new Exception();
            }
            //仅更新人数
            $this->addArea($newUserid, $netInfo['area'], $lsk, 'admin');
            $userInfo = $this->getMemberInfo(['id' => $netInfo['pid']]);
            //更新我自己的pv
            // $userInfo = $this->getMemberInfo(['id' =>$netInfo['uid']]);
            //更新PV
            $this->updatePv($jihuouserinfo, $lsk);
            /*            //更新星级
                        $topUserInfo = $this->getMemberInfoByID($PNetInfo['uid']);
                        $new_UserInfo = $this->getMemberInfoByID($jihuouserinfo['id']);
                        $this->newUpdateSatrLevel($new_UserInfo,$netInfo);*/
            //更新layerlist用于层碰
            $this->addLayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], 1);
            //更新lp_laylist 用于量碰
            $this->addLp_LayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], $netInfo['lay_num'], 1);
            //-----------------------------------
            //更新服务站总业绩
            $data = array('total' => array('exp', "total + {$lsk}"), 'count' => array('exp', "count + 1"));
            $where = array('id' => $biz_id);
            $this->biz_model->editBiz($where, $data);
            $data = array('uid' => $newUserid, 'add_time' => time(), 'net_id' => $net_id);
            $r = $this->table("jiang")->insertTran($data);
            if (!$r) {
                $res['msg'] = '写入奖金记录失败';
                throw new Exception();
            }
            //----------------------------------
            //更新推荐人直推人数
            $data = array('recount' => array('exp', "recount + 1"));
            $where = array('id' => $rid);
            $this->editMember($where, $data);
            $data = array('uid' => $newUserid, 'lay' => '0', 'l_count' => '0', 'r_count' => '0');
            $r = $this->table("lays")->insert($data);
            if (!$r) {
                $res['msg'] = '写入层失败';
                throw new Exception();
            }
            $recommendInfo = $this->getMemberInfoByID($jihuouserinfo['rid']);
            $groupInfo = $this->group_model->getOneGroup($jihuouserinfo['group_id']);
            $bonus_model = Model('bonus');
            $r = $bonus_model->recommendReward($groupInfo, $jihuouserinfo, $recommendInfo, 2);
            if (!$r) {
                $res['msg'] = '写入直推奖失败';
                throw new Exception();
            }
            //------------
            //服务站补贴
            $r = $bonus_model->ssSubsidy($jihuouserinfo['ssid'], $jihuouserinfo['id'], $jihuouserinfo['username'], $jihuouserinfo['lsk']);
            if (!$r) {
                // $res['msg'] = '写入服务站补贴失败';
                $res['msg'] = '写入报单奖励失败';
                throw new Exception();
            }
            // ---------
            //见点奖
            //            $r =$bonus_model->jiandian($jihuouserinfo['pid'], 1, $jihuouserinfo['id'], $jihuouserinfo['username']);
            /* if (!$r) {
                   $res['msg'] = '写入见点奖失败';
                   throw new Exception();
               }*/
            //对碰奖
            // $r = $bonus_model->dpj($jihuouserinfo['id']);
            $r = $bonus_model->liangpengjiangjisuan($jihuouserinfo['id']);
            if (!$r) {
                // $res['msg'] = '写入对碰奖失败';
                $res['msg'] = '写入量碰奖失败';
                throw new Exception();
            }
            //层碰奖
            $r = $bonus_model->cpj();
            if (!$r) {
                $res['msg'] = '写入层碰奖失败';
                throw new Exception();
            }
            //更新星级
            $topUserInfo = $this->getMemberInfoByID($PNetInfo['uid']);
            $new_UserInfo = $this->getMemberInfoByID($jihuouserinfo['id']);
            $this->newUpdateSatrLevel($new_UserInfo, $netInfo);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;
        return $res;
    }
    public function insertRegToday($id, $l_num = 0, $r_num = 0){
        $regtoday = $this->table("reg_day")->where("date='" . date("Y-m-d") . "' and user_id = {$id}")->find();
        if ($regtoday) {
            $data["l_num"] = $regtoday['l_num'] + $l_num;
            $data["r_num"] = $regtoday['r_num'] + $r_num;
            if (!$this->table("reg_day")->where("id=" . $regtoday['id'])->update($data)) {
                $res['msg'] = '更新人数失败';
                throw new Exception();
                return false;
            }
        } else {
            $data["date"] = date("Y-m-d");
            $data["user_id"] = $id;
            $data["l_num"] = $l_num;
            $data["r_num"] = $r_num;
            if (!$this->table("reg_day")->insertTran($data)) {
                $res['msg'] = '添加数据失败';
                throw new Exception();
                return false;
            }
        }
        return true;
    }
    /*
     * 星级升级
     * */
    public function xingjiUp($id){
        if ($us = $this->net_model->getNetByUser($id)) {
            $user = Model('member');
            $net = Model('net');
            $message = Model("message");
            $result = $net->getNumToday04($id);
            $star_l[1] = $result['l_num_1'];
            $star_l[2] = $result['l_num_2'];
            $star_l[3] = $result['l_num_3'];
            $star_l[4] = $result['l_num_4'];
            $star_r[1] = $result['r_num_1'];
            $star_r[2] = $result['r_num_2'];
            $star_r[3] = $result['r_num_3'];
            $star_r[4] = $result['r_num_4'];
            $starSetting = unserialize(C('con_star'));
            $level = 0;
            if ($result["l_num"] >= $starSetting[1] && $result["r_num"] >= $starSetting[1]) {
                $level = 1;
            }
            if ($result["l_num_1"] >= $starSetting[2] && $result["r_num_1"] >= $starSetting[2]) {
                $level = 2;
            }
            if ($result["l_num_2"] >= $starSetting[3] && $result["r_num_2"] >= $starSetting[3]) {
                $level = 3;
            }
            if ($result["l_num_3"] >= $starSetting[4] && $result["r_num_3"] >= $starSetting[4]) {
                $level = 4;
            }
            if ($result["l_num_4"] >= $starSetting[5] && $result["r_num_4"] >= $starSetting[5]) {
                $level = 5;
            }
            if ($level > 0) {
                $check = $user->editMember(array('id' => $id), array('star' => $level));
                if ($check) {
                    $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $id, 'title' => '升级为' . $level . '星会员', 'addtime' => time(), 'content' => '升级为' . $level . '星会员');
                    $message->insertTran($data);
                }
            }
            $star_l_s = serialize($star_l);
            $star_r_s = serialize($star_r);
            $fman_update['l_num_star'] = $star_l_s;
            $fman_update['r_num_star'] = $star_r_s;
            $net->editNet(array('uid' => $id), $fman_update);
            $this->xingjiUp($us['pid']);
        }
    }
    /**给上级增加业绩
     * @param $id
     * @param $treepalce 区域
     * @param $lsk
     * @param $from index前台 admin后台空单操作
     */
    public function addArea($id, $treeplace, $lsk, $from = 'index', $star = 0){
        if ($us = $this->net_model->getNetByUser($id)) {
            if ($fman = $this->net_model->getNetByUser($us['pid'])) {
                switch ($treeplace) {
                    case 1:
                        if ($from == 'index') {
                            $fman_update['all_l_count'] = $fman['all_l_count'] + $lsk;
                            #总业绩
                            $fman_update['l_count'] = $fman['l_count'] + $lsk;
                            #结余业绩
                        }
                        $fman_update['l_num'] = $fman['l_num'] + 1;
                        $fman_update['l_num_today'] = $fman['l_num_today'] + 1;
                        if (!$this->insertRegToday($fman['uid'], 1, 0)) {
                            return false;
                        }
                        break;
                    case 2:
                        if ($from == 'index') {
                            $fman_update['all_r_count'] = $fman['all_r_count'] + $lsk;
                            #总业绩
                            $fman_update['r_count'] = $fman['r_count'] + $lsk;
                            #结余业绩
                        }
                        $fman_update['r_num'] = $fman['r_num'] + 1;
                        $fman_update['r_num_today'] = $fman['r_num_today'] + 1;
                        if (!$this->insertRegToday($fman['uid'], 0, 1)) {
                            return false;
                        }
                        break;
                }
                $this->net_model->editNet(array('uid' => $fman['uid']), $fman_update);
                $userInfo = $this->getMemberInfoByID($fman['uid']);
                $userNet = $this->net_model->getNetByUser($fman['uid']);
                //                //星级升级
                //                $star = $this->updateSatrLevel($userInfo, $userNet);
                //升级为报单中心
                if (!$this->updateBiz($userInfo, $userNet)) {
                    return false;
                }
                $this->addArea($fman['uid'], $fman['area'], $lsk, $from, $star);
            }
        }
    }
    /**更新layerlist用于层碰 该碰法每层只碰1次
     * @param $pid 目标用户
     * @param $area 区域
     * @param $ysid 关联用户-本次激活用户
     * @param $layer
     */
    public function addLayerList($pid, $area, $ysid, $layer){
        $fman = $this->net_model->getNetByUser($pid);
        $layerlist = $this->layerlist_model->getLayerlist(array('uid' => $pid, 'layer' => $layer, 'area' => $area));
        if (empty($layerlist) && $fman) {
            $insert_data = array('uid' => $fman['uid'], 'area' => $area, 'layer' => $layer, 'ysid' => $ysid);
            //  $check = $this->layerlist_model->addMessage($insert_data);\
            $check = $this->table('layerlist')->insertTran($insert_data);
            if ($check) {
                $layer++;
                $this->addLayerList($fman['pid'], $fman['area'], $ysid, $layer);
            } else {
                return false;
            }
        }
    }
    /**更新lp-layerlist用于量碰
     * @param $pid 目标用户 上级用户
     * @param $area 区域    
     * @param $uid 关联用户-本次激活用户
     * @param $layer
        取到上一级的 用户信息     id 上级在上级的那一边 自己的id 层数
        目标id  我做对应目标id所在的区域  我的id  我所在的层数 
     */
    public function addLp_LayerList($pid, $area, $ysid, $mylayer, $layer){
        $fman = $this->net_model->getNetByUser($pid);
        //取到当前激活人的上级信息
        //我的id - $ysid   目标id $pid    我所在的层数  $mylayer   我对应目标的区域  $area   我是他的第几位  聚合一次
        //去聚合查询  目标id  我所在的层数  我所在的区域
        // $condition = array(
        //     'field' => '*,count(*) as num',
        //     'group' => 'pid,lay,area',
        //     'status' => 0
        // );
        // $layerlist = $this->layerlist_model->get_Lp_lay_List($condition);
        $layerlist = $this->layerlist_model->get_lp_Lay_list_find(array('pid' => $pid, 'lay' => $layer, 'area' => $area));
        if (empty($layerlist['site'])) {
            $site = 1;
        } else {
            $site = (int) $layerlist['site'] + 1;
        }
        if ($fman) {
            $insert_data = array('pid' => $fman['uid'], 'area' => $area, 'lay' => $layer, 'ysid' => $ysid, 'site' => $site, 'status' => 0);
            //  $check = $this->layerlist_model->addMessage($insert_data);\
            $check = $this->table('lp_laylist')->insertTran($insert_data);
            if ($check) {
                $layer++;
                $this->addLp_LayerList($fman['pid'], $fman['area'], $ysid, $mylayer, $layer);
            } else {
                return false;
            }
        }
    }
    /**
     * 星级升级
     */
    public function updateSatrLevel($userInfo, $userNet){
        $starSetting = unserialize(C('con_star'));
        $pvSetting = unserialize(C('con_pv'));
        $r_num_star = unserialize($userNet['r_num_star']);
        $l_num_star = unserialize($userNet['r_num_star']);
        $level = 0;
        if ($userNet['l_num'] >= $starSetting[1] && $userNet['r_num'] >= $starSetting[1] && $userInfo['pv'] >= $pvSetting[1]) {
            $level = 1;
        }
        if ($r_num_star[1] >= $starSetting[2] && $r_num_star[1] >= $starSetting[2] && $userInfo['pv'] >= $pvSetting[2]) {
            $level = 2;
        }
        if ($r_num_star[2] >= $starSetting[3] && $r_num_star[2] >= $starSetting[3] && $userInfo['pv'] >= $pvSetting[3]) {
            $level = 3;
        }
        if ($r_num_star[3] >= $starSetting[4] && $r_num_star[3] >= $starSetting[4] && $userInfo['pv'] >= $pvSetting[4]) {
            $level = 4;
        }
        if ($r_num_star[4] >= $starSetting[5] && $r_num_star[4] >= $starSetting[5] && $userInfo['pv'] >= $pvSetting[5]) {
            $level = 5;
        }
        if ($r_num_star[5] >= $starSetting[6] && $r_num_star[5] >= $starSetting[6] && $userInfo['pv'] >= $pvSetting[6]) {
            $level = 6;
        }
        if ($r_num_star[6] >= $starSetting[7] && $r_num_star[6] >= $starSetting[7] && $userInfo['pv'] >= $pvSetting[7]) {
            $level = 7;
        }
        if ($r_num_star[7] >= $starSetting[8] && $r_num_star[7] >= $starSetting[8] && $userInfo['pv'] >= $pvSetting[8]) {
            $level = 8;
        }
        if ($r_num_star[8] >= $starSetting[9] && $r_num_star[8] >= $starSetting[9] && $userInfo['pv'] >= $pvSetting[9]) {
            $level = 9;
        }
        if ($r_num_star[9] >= $starSetting[10] && $r_num_star[9] >= $starSetting[10] && $userInfo['pv'] >= $pvSetting[10]) {
            $level = 10;
        }
        //        echo $userInfo["id"].'--'.$userInfo['star'].'<br/>';
        //        echo $level."<br/>";
        if ($level > $userInfo['star']) {
            $check = $this->editMember(array('id' => $userInfo['id']), array('star' => $level));
            if ($check) {
                $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $userInfo['id'], 'title' => '升级为' . $level . '星会员', 'addtime' => time(), 'content' => '升级为' . $level . '星会员');
                $this->table('message')->insert($data);
            }
            return $level;
        }
        return $userInfo['star'];
        return 0;
    }
    /**
     * 新星级升级
     */
    public function newUpdateSatrLevel($userInfo, $userNet){
        $digui = false;
        $netModel = Model('net');
        if ($userNet['pid'] > 0) {
            $top_user_Info = $this->getMemberInfo(['id' => $userInfo['pid']]);
            //用户信息
            $top_Net_info = $netModel->getNetInfo(['uid' => $userNet['pid']]);
            //树形信息
            $digui = true;
        }
        $leftNetInfo = $netModel->getNetInfo(['pid' => $userNet['uid'], 'area' => 1]);
        $rightNetInfo = $netModel->getNetInfo(['pid' => $userNet['uid'], 'area' => 2]);
        file_put_contents('./up.json', json_encode(['up_id' => $userInfo['id'], 'tag' => 1]) . PHP_EOL, FILE_APPEND);
        $r_num_star = unserialize($userNet['r_num_star']);
        $l_num_star = unserialize($userNet['r_num_star']);
        $starModel = Model('star');
        if ($userInfo['star'] == 0) {
            $starInfo = $starModel->getStarInfo(['level' => 1]);
        } else {
            $starInfo = $starModel->getStarInfo(['level' => $userInfo['star']]);
        }
        if ($starInfo) {
            if ($starInfo['next_star'] == 0) {
                //已是最高等级
                if (!$digui) {
                    return false;
                } else {
                    return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                }
            }
            if ($userInfo['star'] == 0) {
                if ($leftNetInfo && $rightNetInfo) {
                    //左右小区都存在
                    $leftUserInfo = $this->getMemberInfo(['id' => $leftNetInfo['uid']]);
                    $rightUserInfo = $this->getMemberInfo(['id' => $rightNetInfo['uid']]);
                    $leftUserGroupOpen = $this->group_model->getOneGroup($leftUserInfo['begin_group']);
                    $rightUserGroupOpen = $this->group_model->getOneGroup($rightUserInfo['begin_group']);
                    if ($leftUserInfo['pv'] + $leftUserGroupOpen['lsk'] > $starInfo['first_pv'] && $rightUserInfo['pv'] + $rightUserGroupOpen['lsk'] > $starInfo['first_pv']) {
                        //判断小区是否达到升级条件
                        $nextStar = $starModel->getStarInfo(['level' => 1]);
                    } else {
                        if (!$digui) {
                            return false;
                        } else {
                            return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                        }
                    }
                } else {
                    if (!$digui) {
                        return false;
                    } else {
                        return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                    }
                }
            } else {
                if ($leftNetInfo && $rightNetInfo) {
                    //左右小区都存在
                    $leftUserInfo = $this->getMemberInfo(['id' => $leftNetInfo['uid']]);
                    $rightUserInfo = $this->getMemberInfo(['id' => $rightNetInfo['uid']]);
                    $leftUserGroupOpen = $this->group_model->getOneGroup($leftUserInfo['begin_group']);
                    $rightUserGroupOpen = $this->group_model->getOneGroup($rightUserInfo['begin_group']);
                    if ($leftUserInfo['pv'] + $leftUserGroupOpen['lsk'] > $starInfo['con_pv'] && $rightUserInfo['pv'] + $rightUserGroupOpen['lsk'] > $starInfo['con_pv']) {
                        //判断小区是否达到升级条件
                        $nextStar = $starModel->getStarInfo(['level' => $starInfo['next_star']]);
                    } else {
                        if (!$digui) {
                            return false;
                        } else {
                            return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                        }
                    }
                } else {
                    if (!$digui) {
                        return false;
                    } else {
                        return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                    }
                }
            }
            $starInfo['con_star'] = unserialize($starInfo['con_star']);
            file_put_contents('./up.json', json_encode(['up_id' => $userInfo['id'], 'tag' => 2]) . PHP_EOL, FILE_APPEND);
            if ($starInfo['level'] <= 6) {
                $i = 10;
            } else {
                $i = 0;
                //起始判断条件 当i=10时判断为满足升级条件之一
                file_put_contents('./up.json', json_encode(['starInfo' => $starInfo, 'tag' => 2]) . PHP_EOL, FILE_APPEND);
                foreach ($starInfo['con_star'] as $k => $v) {
                    $il = 0;
                    $ir = 0;
                    if ($r_num_star[$k] > 0) {
                        $ir = $r_num_star[$k];
                    }
                    if ($l_num_star[$k] > 0) {
                        $il = $l_num_star[$k];
                    }
                    if ($ir + $il >= $v) {
                        $i++;
                    }
                }
            }
            if ($i == 10) {
                //此时满足升级条件
                file_put_contents('./up.json', json_encode(['up_id' => $userInfo['id'], 'tag' => 3]) . PHP_EOL, FILE_APPEND);
                $check = $this->editMember(array('id' => $userInfo['id']), array('star' => $nextStar['level']));
                $netModel->updateParentNumStar($nextStar, $userInfo['id']);
                if ($check) {
                    $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $userInfo['id'], 'title' => '升级为' . $nextStar['name'], 'addtime' => time(), 'content' => '升级为' . $nextStar['name']);
                    $this->table('message')->insert($data);
                    file_put_contents('./up.json', json_encode(['up_id' => $userInfo['id'], 'tag' => 4]) . PHP_EOL, FILE_APPEND);
                    if (!$digui) {
                        return false;
                    } else {
                        return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                    }
                }
                return true;
            } else {
                if (!$digui) {
                    return false;
                } else {
                    return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
                }
            }
        } else {
            if (!$digui) {
                return false;
            } else {
                return $this->newUpdateSatrLevel($top_user_Info, $top_Net_info);
            }
        }
        //        $level = 0;
        //        if($userNet['l_num']>=$starSetting[1] && $userNet['r_num']>= $starSetting[1] && $userInfo['pv'] >= $pvSetting[1])
        //        {
        //            $level = 1;
        //        }
        //        if($r_num_star[1]>=$starSetting[2] && $r_num_star[1] >= $starSetting[2] && $userInfo['pv'] >= $pvSetting[2]){
        //            $level = 2;
        //        }
        //        if($r_num_star[2]>=$starSetting[3] && $r_num_star[2] >= $starSetting[3] && $userInfo['pv'] >= $pvSetting[3]){
        //            $level = 3;
        //        }
        //        if($r_num_star[3]>=$starSetting[4] && $r_num_star[3] >= $starSetting[4] && $userInfo['pv'] >= $pvSetting[4]){
        //            $level = 4;
        //        }
        //        if($r_num_star[4]>=$starSetting[5] && $r_num_star[4] >= $starSetting[5] && $userInfo['pv'] >= $pvSetting[5]){
        //            $level = 5;
        //        }
        //        if($r_num_star[5]>=$starSetting[6] && $r_num_star[5] >= $starSetting[6] && $userInfo['pv'] >= $pvSetting[6]){
        //            $level = 6;
        //        }
        //        if($r_num_star[6]>=$starSetting[7] && $r_num_star[6] >= $starSetting[7] && $userInfo['pv'] >= $pvSetting[7]){
        //            $level = 7;
        //        }
        //        if($r_num_star[7]>=$starSetting[8] && $r_num_star[7] >= $starSetting[8] && $userInfo['pv'] >= $pvSetting[8]){
        //            $level = 8;
        //        }
        //        if($r_num_star[8]>=$starSetting[9] && $r_num_star[8] >= $starSetting[9] && $userInfo['pv'] >= $pvSetting[9]){
        //            $level = 9;
        //        }
        //        if($r_num_star[9]>=$starSetting[10] && $r_num_star[9] >= $starSetting[10] && $userInfo['pv'] >= $pvSetting[10]){
        //            $level = 10;
        //        }
        //        echo $userInfo["id"].'--'.$userInfo['star'].'<br/>';
        //        echo $level."<br/>";
        //        if ($level > $userInfo['star']) {
        //            $check = $this->editMember(array('id' => $userInfo['id']), array('star' => $level));
        //            if ($check) {
        //                $data = array(
        //                    'formid' => 'A',
        //                    'formname' => '系统提示',
        //                    'totype' => 1,
        //                    'toid' => $userInfo['id'],
        //                    'title' => '升级为' . $level . '星会员',
        //                    'addtime' => time(),
        //                    'content' => '升级为' . $level . '星会员',
        //                );
        //                $this->table('message')->insert($data);
        //            }
        //            return $level;
        //        }
        //        return $userInfo['star'];
        //        return 0;
    }
    /**
     * 升级为报单中心
     */
    public function updateBiz($userInfo, $userNet){
        if (!$userInfo['is_biz']) {
            $bizSetting = C('con_ss_reach');
            if ($userNet['l_num'] >= $bizSetting && $userNet['r_num'] >= $bizSetting) {
                $insert_data = array('uid' => $userInfo['id'], 'addtime' => time(), 'status' => 1, 'username' => $userInfo['username']);
                // $result = $this->biz_model->add($insert_data);
                $result = $this->table('biz')->insertTran($insert_data);
                if ($result) {
                    $check = $this->editMember(array('id' => $userInfo['id']), array('is_biz' => 1));
                    if ($check) {
                        $data = array('formid' => 'A', 'formname' => '系统提示', 'totype' => 1, 'toid' => $userInfo['id'], 'title' => '恭喜您,成为服务站', 'addtime' => time(), 'content' => '恭喜您,成为服务站');
                        if (!$this->table('message')->insertTran($data)) {
                            $res['msg'] = '添加服务站失败';
                            throw new Exception();
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    /**
     * 重消奖励计算
     * @param $userInfo 相关用户信息
     * @param $userNet 相关用户节点信息
     * @param $orderPrice 相关金额
     * @return bool
     */
    public function RepetitiveConsumptionReward($userInfo, $userNet, $orderPrice){
        file_put_contents('./reward.json', json_encode(['tag' => 1]) . PHP_EOL, FILE_APPEND);
        if ($userNet['area'] == 1) {
            $area = 2;
        } elseif ($userNet['area'] == 2) {
            $area = 1;
        } else {
            return false;
        }
        file_put_contents('./reward.json', json_encode(['tag' => 2]) . PHP_EOL, FILE_APPEND);
        if ($userNet['pid'] == 0) {
            return false;
        }
        $topInfo = $this->getMemberInfoByID($userNet['pid']);
        $topNet = $this->net_model->getNetInfo(['uid' => $userNet['pid']]);
        //计算自己的pv
        // $topInfo = $this->getMemberInfoByID($userNet['uid']);
        // $topNet = $this->net_model->getNetInfo(['uid' => $userNet['pid']]);
        $member_where = array('id' => $userNet['pid']);
        //计算自己的pv
        /*$member_where = array(
              'id' => $userNet['uid']
          );*/
        $member_updata = array('pv' => $topInfo['pv'] + $orderPrice, 'newpv' => $topInfo['pv'] + $orderPrice);
        //TODO 更新上级PV
        $this->beginTransaction();
        $this->editMember($member_where, $member_updata);
        try {
            //TODO 查找兄弟小区
            $someAreaNetInfo = $this->net_model->getNetInfo(['pid' => $userNet['pid'], 'area' => $area]);
            file_put_contents('./reward.json', json_encode(['tag' => 3, 'someAreaNetInfo' => $someAreaNetInfo]) . PHP_EOL, FILE_APPEND);
            if ($someAreaNetInfo) {
                //TODO 兄弟小区存在
                $someAreaUserInfo = $this->getMemberInfoByID($someAreaNetInfo['uid']);
                //TODO 兄弟小区用户信息
                file_put_contents('./reward.json', json_encode(['tag' => 3, 'someAreaNetInfo' => $someAreaNetInfo]) . PHP_EOL, FILE_APPEND);
                if ($someAreaUserInfo['pv'] > $userInfo['pv']) {
                    //TODO 兄弟小区为大区,
                    //TODO 所以计算重消奖励
                    file_put_contents('./reward.json', json_encode(['tag' => 4]) . PHP_EOL, FILE_APPEND);
                    $groupInfo = $this->group_model->getOneGroup($userInfo['group_id']);
                    //TODO 当前用户等级信息
                    $rate = $groupInfo['cfxf'];
                    //TODO 重复消费奖换购物币比例 需要 /100
                    file_put_contents('./reward.json', json_encode(['tag' => 5, 'rate' => $rate]) . PHP_EOL, FILE_APPEND);
                    //TODO 重复消费奖比例
                    $repetitiveConsumRate = $groupInfo['re_consum_reward'];
                    file_put_contents('./reward.json', json_encode(['tag' => 6, 're_consum_reward' => $repetitiveConsumRate]) . PHP_EOL, FILE_APPEND);
                    //TODO 所得购物币
                    $get_buy_amount = $orderPrice * ($repetitiveConsumRate / 100) * ($rate / 100);
                    //TODO 所获得的实际重复消费奖金额
                    $repetitiveConsumptionReward = $orderPrice * ($repetitiveConsumRate / 100) - $get_buy_amount;
                    $updateCondition = array('id' => $topInfo['id']);
                    $updateData = array('ji_balance' => $topInfo['ji_balance'] + $repetitiveConsumptionReward, 'zhang_balance' => $topInfo['zhang_balance'] + $get_buy_amount);
                    //TODO 更新上级相关数据
                    $res = $this->editMember($updateCondition, $updateData);
                    //上级升星
                    $this->newUpdateSatrLevel($topInfo, $topNet);
                    file_put_contents('./reward.json', json_encode(['tag' => 7, 'res' => $res]) . PHP_EOL, FILE_APPEND);
                    //TODO 记录购物币变动信息
                    $this->bonuslaiyuan_model->recorde($topInfo['id'], $get_buy_amount, '购物币', 7, '重复消费所得' . $rate . '%转为购物币', $userInfo['id'], $userInfo['username'], $topInfo['username']);
                    //TODO 写入本日结算
                    $this->bonuslog_model->log($topInfo['id'], $topInfo['username'], 7, $repetitiveConsumptionReward);
                    //TODO 记录积分变动信息
                    $this->bonuslaiyuan_model->recorde($topInfo['id'], $repetitiveConsumptionReward, '积分', 7, '重复消费奖为购买金额的' . $repetitiveConsumRate . '%', $userInfo['id'], $userInfo['username'], $topInfo['username']);
                    //TODO 写入本日结算
                    $this->bonuslog_model->log($topInfo['id'], $topInfo['username'], 7, $repetitiveConsumptionReward);
                    file_put_contents('./reward.json', json_encode(['tag' => 8, 'res' => $res]) . PHP_EOL, FILE_APPEND);
                    $this->RepetitiveConsumptionReward($topInfo, $topNet, $orderPrice);
                    $this->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
            file_put_contents('./reward.json', json_encode(['tag' => 9, 'exception' => $e->getMessage()]) . PHP_EOL, FILE_APPEND);
            $this->rollback();
            return false;
        }
        return false;
    }
}