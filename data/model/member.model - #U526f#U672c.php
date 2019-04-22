<?php
/**
 * 会员模型
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class memberModel extends Model
{
    private $net_model;
    private $biz_model;
    private $layerlist_model;

    public function __construct()
    {
        parent::__construct('member');
        $this->net_model = Model('net');
        $this->biz_model = Model('biz');
        $this->layerlist_model = Model('layerlist');
    }

    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false)
    {
        return $this->table('users')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*')
    {
        $member_info = $this->getMemberInfo(array('id' => $member_id), '*', true);
        return $member_info;
    }

    public function getMemberInfoByUsername($username, $fields = '*')
    {
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
    public function getMemberList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('users')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMembersList($condition, $page = null, $order = 'id desc', $field = '*')
    {
        return $this->table('users')->field($field)->where($condition)->page($page)->order($order)->select();
    }

    public function getStat($where, $field = '*', $page = 0, $order = '', $group = '')
    {
        return $this->table('users,parent')->field($field)->join('inner')->on('users.id=parent.uid')->where($where)->page($page)->order($order)->group($group)->select();
    }

    public function getMemberLevel($typ)
    {
        $type = array('low' => ' 会员', 'middle' => '一星会员', 'high' => '企业商户');

        return $type[$typ];
    }

    public function getChild($uid)
    {
        $map = array();
        $map['parent.parent'] = array('like', "%,$uid,%");
        $map['users.status'] = 1;

        return $this->table('parent,users')->join('inner')->on('users.id = parent.uid')->where($map)->select();
    }

    /**
     * 取单个内容返回字段
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneMemberReField($id, $field)
    {
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
    public function GetUserLevel($id)
    {
        $level = Model('level');
        $account = Model('account');
        $info = $this->getMemberInfoByID($id);
        $LevelInfo = $level->getLevelById($info['level_id']);
        $AccountInfo = $account->getAccountById($LevelInfo['account_id']);
        return $arr = array(
            'status' => $AccountInfo[$info['state']],
            'state' => $info['state'],
        );

    }

    //升级用户状态
    public function UpLevel($id, $amount)
    {
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
            $data['bao_balance'] = array('exp', "bao_balance - $amount");
            $where = array('id' => $id);

            $r = $users->editMember($where, $data);

            if (!$r) {
                $res['msg'] = '扣款失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $id,
                'money_type' => '报单币',
                'money' => (0 - $amount),
                'type' => '升级消耗',
                'time' => time(),
                'intro' => '升级',
                'cod' => 'bao',
                'old_amount' => $old_amount,
                'new_amount' => $new_amount
            );

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

            $data = array(
                'formid' => 'A',
                'formname' => '系统提示',
                'totype' => 1,
                'toid' => $id,
                'title' => '升级消耗' . $amount . '报单币',
                'addtime' => time(),
                'content' => '升级消耗' . $amount . '报单币',
            );
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
    public function del($id)
    {
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
    public function getMemberCount($condition)
    {
        return $this->table('users')->where($condition)->count();
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data)
    {
        $update = $this->table('users')->where($condition)->update($data);
        return $update;
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(), $reg = false)
    {
        if (empty($member_info) || !is_array($member_info)) return;

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
    public function infoMember($param, $field = '*')
    {
        if (empty($param)) return false;

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
    public function auto_login()
    {
        // 自动登录标记 保存7天
        setBnCookie('auto_login', encrypt($_SESSION['member_id'], MD5_KEY), 7 * 24 * 60 * 60);
    }

    /**
     * 注册商城会员
     *
     * @param   array $param 会员信息
     * @return  array 数组格式的返回结果
     */
    public function addMember($param)
    {
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
    public function checkloginMember()
    {
        if ($_SESSION['is_login'] == '1') {
            @header("Location: index.php");
            exit();
        }
    }

    public function GetXDate($overdue_time)
    {
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
    public function getMemeberInfoList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'users';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition)
    {
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

    private function getCondition($conditon_array)
    {
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

    public function register($para)
    {
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

            $data = array(
                'num' => 6001 + $new_user_id
            );
            $re = $this->editMember(array('id' => $new_user_id), $data);

            if (!$re) {
                $res['msg'] = '编号失败!,请刷新后重试!';
                throw new Exception();
            }

            $data = array('uid' => $new_user_id, 'pid' => $pid, 'status' => 0, 'area' => $scwz);
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

            $data = array('new_id' => $new_id, 'jdr_id' => $pid, 'zc_id' => $userid, 'tjr_id' => $tjr_info['id'], 'time' => time(), 'uid' => $new_user_id);
            $recordId = $record->addRecord($data);
            if (!$recordId) {
                $res['msg'] = '写入记录失败!,请刷新后重试!';
                throw new Exception();
            }

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
            if (!$parent) $parent = ",";
            $parent .= $parent_id . ',';
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


    public function DelUser($id, $userid)
    {
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
    public function adminDel($uid)
    {
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
                $data['bao_balance'] = array('exp', "bao_balance + $amount");
                $where = array('id' => $zc_id);

                $r = $this->editMember($where, $data);
                $old_amount = $Info['bao_balance'];
                $new_amount = $Info['bao_balance'] + $amount;

                if (!$r) {
                    $res['msg'] = '加款失败';
                    throw new Exception();
                }

                $data = array(
                    'uid' => $zc_id,
                    'money_type' => '报单币',
                    'money' => $amount,
                    'type' => '返还',
                    'time' => time(),
                    'intro' => '删除会员返还',
                    'cod' => 'bao',
                    'old_amount' => $old_amount,
                    'new_amount' => $new_amount
                );

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
    public function activation_user($uid, $from)
    {
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

    public function retrunMsg($from, $type, $msg)
    {
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

    public function doActive($net_id, $userid, $biz_id, $lsk, $rid)
    {
        $record = Model('record');
        $net = Model('net');

        $UserInfo = $this->getMemberInfoByID($userid);
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
            $data['bao_balance'] = array('exp', "bao_balance - $amount");
            $where = array('id' => $userid);

            $r = $this->editMember($where, $data);
            $old_amount = $UserInfo['bao_balance'];
            $new_amount = $UserInfo['bao_balance'] - $amount;

            if (!$r) {
                $res['msg'] = '扣款失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $userid,
                'money_type' => '报单币',
                'money' => (0 - $amount),
                'type' => '激活会员',
                'time' => time(),
                'intro' => '激活新会员',
                'cod' => 'bao',
                'old_amount' => $old_amount,
                'new_amount' => $new_amount
            );

            $r = $this->table('trans')->insert($data);
            if (!$r) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }

            $data = array(
                'formid' => 'A',
                'formname' => '系统提示',
                'totype' => 1,
                'toid' => $userid,
                'title' => '激活新会员，扣除' . $amount . '报单币',
                'addtime' => time(),
                'content' => '扣除' . $amount . '报单币',
            );
            $this->table('message')->insert($data);


            $newUserid = $netInfo['uid'];

            $data = array('status' => 1, 'login_status' => 1, login_time => time());
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
            //更新layerlist用于层碰
            $this->addLayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], 1);
            //
            //更新服务站总业绩
            $data = array('total' => array('exp', "total + $lsk"), 'count' => array('exp', "count + 1"));
            $where = array('id' => $biz_id);
            $this->biz_model->editBiz($where, $data);
            $data = array('uid' => $newUserid, 'add_time' => time(), 'net_id' => $net_id);

            $r = $this->table("jiang")->insert($data);
            if (!$r) {
                $res['msg'] = '写入奖金记录失败';
                throw new Exception();
            }
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;

        return $res;
    }

    public function doActiveAdmin($net_id, $userid, $biz_id, $lsk, $rid)
    {
        $record = Model('record');
        $net = Model('net');

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

            $newUserid = $netInfo['uid'];

            $data = array('status' => 1, 'login_status' => 1, login_time => time());
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
            //更新layerlist用于层碰
            $this->addLayerList($netInfo['pid'], $netInfo['area'], $netInfo['uid'], 1);
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
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }
        $res['status'] = 1;

        return $res;
    }

    /**给上级增加业绩
     * @param $id
     * @param $treepalce 区域
     * @param $lsk
     * @param $from index前台 admin后台空单操作
     */
    public function addArea($id, $treeplace, $lsk, $from = 'index')
    {
        if ($us = $this->net_model->getNetByUser($id)) {
            if ($fman = $this->net_model->getNetByUser($us['pid'])) {
                switch ($treeplace) {
                    case 1:
                        if ($from == 'index') {
                            $fman_update['all_l_count'] = $fman['all_l_count'] + $lsk; #总业绩
                            $fman_update['l_count'] = $fman['l_count'] + $lsk; #结余业绩
                        }
                        $fman_update['l_num'] = $fman['l_num'] + 1;
                        break;
                    case 2:
                        if ($from == 'index') {
                            $fman_update['all_r_count'] = $fman['all_r_count'] + $lsk; #总业绩
                            $fman_update['r_count'] = $fman['r_count'] + $lsk; #结余业绩
                        }
                        $fman_update['r_num'] = $fman['r_num'] + 1;
                        break;
                }
                $this->net_model->editNet(array('uid' => $fman['uid']), $fman_update);
                $userInfo = $this->getMemberInfoByID($fman['uid']);
                $userNet = $this->net_model->getNetByUser($fman['uid']);
                //星级升级
                $this->updateSatrLevel($userInfo, $userNet);
                //升级为报单中心
                $this->updateBiz($userInfo, $userNet);
                $this->addArea($fman['uid'], $fman['area'], $lsk, $from);
            }
        }
    }

    /**更新layerlist用于层碰 该碰法每层只碰1次
     * @param $pid 目标用户
     * @param $area 区域
     * @param $ysid 关联用户-本次激活用户
     * @param $layer
     */
    public function addLayerList($pid, $area, $ysid, $layer)
    {
        $fman = $this->net_model->getNetByUser($pid);
        $layerlist = $this->layerlist_model->getLayerlist(array('uid' => $pid, 'layer' => $layer, 'area' => $area));
        if (empty($layerlist) && $fman) {
            $insert_data = array(
                'uid' => $fman['uid'],
                'area' => $area,
                'layer' => $layer,
                'ysid' => $ysid
            );
            $check = $this->layerlist_model->addMessage($insert_data);
            if ($check) {
                $layer++;
                $this->addLayerList($fman['pid'], $fman['area'], $ysid, $layer);
            }
        }
    }

    /**
     * 星级升级
     */
    public function updateSatrLevel($userInfo, $userNet)
    {
        $starSetting = unserialize(C('con_star'));
        foreach ($starSetting as $key => $value) {
            if ($userNet['l_num'] >= $value && $userNet['r_num'] >= $value) {
                $level = $key;
            }
        }
        if ($level > $userInfo['satr']) {
            $check = $this->editMember(array('id' => $userInfo['id']), array('star' => $level));
            if ($check) {
                $data = array(
                    'formid' => 'A',
                    'formname' => '系统提示',
                    'totype' => 1,
                    'toid' => $userInfo['id'],
                    'title' => '升级为' . $level . '星会员',
                    'addtime' => time(),
                    'content' => '升级为' . $level . '星会员',
                );
                $this->table('message')->insert($data);
            }
        }
    }

    /**
     * 升级为报单中心
     */
    public function updateBiz($userInfo, $userNet)
    {
        if (!$userInfo['is_biz']) {
            $bizSetting = C('con_ss_reach');
            if ($userNet['l_num'] >= $bizSetting && $userNet['r_num'] >= $bizSetting) {
                $insert_data = array(
                    'uid' => $userInfo['id'],
                    'addtime' => time(),
                    'status' => 1,
                    'username' => $userInfo['username']
                );
                $result = $this->biz_model->add($insert_data);
                if ($result) {
                    $check = $this->editMember(array('id' => $userInfo['id']), array('is_biz' => 1));
                    if ($check) {
                        $data = array(
                            'formid' => 'A',
                            'formname' => '系统提示',
                            'totype' => 1,
                            'toid' => $userInfo['id'],
                            'title' => '恭喜您,成为服务站',
                            'addtime' => time(),
                            'content' => '恭喜您,成为服务站',
                        );
                        $this->table('message')->insert($data);
                    }
                }
            }
        }
    }
}
