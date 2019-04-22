<?php
/**
 * 会员管理
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class memberControl extends SystemControl
{
    const EXPORT_SIZE = 1000;
    private $group_model;
    private $user_model;
    private $biz_model;
    private $net_model;
    private $trans_model;
    private $bonus_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('member');
        $this->group_model = Model('group');
        $this->user_model = Model('member');
        $this->biz_model = Model('biz');
        $this->net_model = Model('net');
        $this->trans_model = Model('trans');
        $this->bonus_model = Model('bonus');
    }

    public function indexOp()
    {
        $this->memberOp();
    }

    /**
     * 会员管理
     */
    public function memberOp()
    {
        Tpl::setDirquna('member');/**/
        Tpl::showpage('member.index');
    }

    /**
     * 会员修改
     */
    public function member_editOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('member');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $update_array = array();
            $do = false;

            $userid = $uid = intval($_POST['id']);
            $username = trim($_POST['username']);

            if (!empty($_POST['password'])) {
                $update_array['password'] = md5($_POST['password']);
                $do = true;
            }
            if (!empty($_POST['jy_pwd'])) {
                $update_array['jy_pwd'] = md5($_POST['jy_pwd']);
                $do = true;
            }

            if ($_POST['amount'] + 0 != 0) {
                $amount = $_POST['amount'] + 0;
                $mt = $_POST['type'];

                $r = Model("money")->AdminChange($userid, $mt, $amount);
                $do = true;
            }
            $where = array('id' => $userid);
            $memberInfo = $model_member->getMemberInfo($where);
            if ($memberInfo['username'] != $username) {
                $where = array('username' => $username, 'no_id' => $userid);
                $have = $model_member->infoMember($where);

                if (!$have) {
                    $do = true;
                    $update_array['username'] = $username;
                }
            }

            $result = $model_member->editMember(array('id' => $userid), $update_array);
            if ($result || $r['status']) {
                $url = array(
                    array(
                        'url' => 'index.php?act=member&op=member',
                        'msg' => $lang['member_edit_back_to_list'],
                    ),
                    array(
                        'url' => 'index.php?act=member&op=member_edit&member_id=' . intval($uid),
                        'msg' => $lang['member_edit_again'],
                    ),
                );
                $this->log(L('nc_edit,member_index_name') . '[ID:' . $uid . ']', 1);
                showMessage($lang['member_edit_succ'], $url);
            } else {
                if ($do) showMessage($lang['member_edit_fail']);
                showMessage('未作修改');
            }
        }
        $condition['id'] = intval($_GET['id']);
        $member_array = $model_member->getMemberInfo($condition);
        $net = Model("net")->getNetByUser($condition['id']);
        $groupList = $this->group_model->getGroupList(array());
        Tpl::output('groupList', $groupList);
        Tpl::output('member_array', $member_array);
        Tpl::output('net', $net);
        Tpl::setDirquna('member');/**/
        Tpl::showpage('member.edit');
    }

    public function member_delOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('member');

        $uid = intval($_GET['id']);
        $res = $model_member->adminDel($uid);

        if ($res['status']) {
            $url = array(
                array(
                    'url' => 'index.php?act=member&op=member',
                    'msg' => $lang['member_edit_back_to_list'],
                )
            );
            $this->log("删除会员" . '[ID:' . $uid . ']', 1);
            showMessage("删除会员成功", $url);
        } else {
            showMessage($res['msg']);
        }
    }

    /**
     * 新增会员
     */
    public function member_addOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('member');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["username"], "require" => "true", "message" => $lang['member_add_name_null']),
                array("input" => $_POST["password"], "require" => "true", "message" => '密码不能为空'),
                array("input" => $_POST["jymm"], "require" => "true", "message" => '交易密码不能为空'),
                array("input" => $_POST["tjr"], "require" => "true", "message" => '推荐人不能为空'),
                array("input" => $_POST["ssname"], "require" => "true", "message" => '服务在不能为空'),
                array("input" => $_POST["member_email"], "require" => "true", 'validator' => 'Email', "message" => $lang['member_edit_valid_email'])
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $username = trim($_POST['username']);
                $tjr = trim($_POST['tjr']);
                $ssname = trim($_POST['ssname']);
                $group_id = trim($_POST['group_id']);
                $jdr = trim($_REQUEST['jdr']);
                $scwz = $_REQUEST['scwz'];

                //验证推荐人是否存在
                $tjr_info = $this->select_tjr($tjr);
                //验证服务站是否存在
                $ssInfo = $this->select_ss($ssname);
                //验证接点人左右区存在情况
                $jdr_uid = $this->check_wz($jdr, $scwz);

                //报单费
                $lsk = $this->group_model->getOneGroupReField($group_id, 'lsk');
                //添加用户到表
                $Have = $model_member->getMemberInfo(array('username' => $username));
                if ($Have) showMessage('该用户名已被注册');

                $insert_array = array();
                $insert_array['username'] = $username;
                $insert_array['password'] = md5(trim($_POST['password']));
                $insert_array['jy_pwd'] = md5(trim($_POST['jymm']));
                $insert_array['rid'] = $tjr_info['id'];
                $insert_array['rname'] = $tjr_info['username'];
                $insert_array['ssid'] = $ssInfo['id'];
                $insert_array['ssuid'] = $ssInfo['uid'];
                $insert_array['ssname'] = $ssname;
                $insert_array['group_id'] = $group_id;
                $insert_array['begin_group'] = $group_id;
                $insert_array['lsk'] = $lsk;
                $insert_array['name'] = trim($_POST['name']);
                $insert_array['sex'] = trim($_POST['sex']);
                $insert_array['pid'] = $jdr_uid;
                $insert_array['email'] = trim($_POST["member_email"]);
                $insert_array['time'] = time();
                $result = $model_member->addMember($insert_array);

                if ($result) {
                    //系统自动选择安置人
                    $id = $result;
                    $res = $model_member->register(array('uid' => $id, 'pid' => $jdr_uid, 'tjr' => $tjr_info, 'scwz' => $scwz, 'userid' => $ssInfo['uid'], 'lsk' => $lsk));

                    if ($res) {
                        $Net_info = $this->net_model->getNetByUser($id);
                        $re = $model_member->doActiveAdmin($Net_info['id'], $ssInfo['uid'], $ssInfo['id'], $lsk, $tjr_info['id']);

                        if ($re['status']) {
                            $url = array(
                                array(
                                    'url' => 'index.php?act=member&op=member',
                                    'msg' => $lang['member_add_back_to_list'],
                                ),
                                array(
                                    'url' => 'index.php?act=member&op=member_add',
                                    'msg' => $lang['member_add_again'],
                                ),
                            );
                            $this->log(L('nc_add,member_index_name') . '[ ' . $_POST['username'] . ']', 1);
                            showMessage($lang['member_add_succ'], $url);
                        }
                    } else {
                        showMessage('用户激活失败，请刷新后重试');
                    }
                } else {
                    showMessage($lang['member_add_fail']);
                }
            }
        }
        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        $condition['id'] = intval($_GET['id']);
        $member_array = $model_member->getMemberInfo($condition);
        Tpl::output('groupList', $groupList);
        Tpl::output('member_array', $member_array);
        Tpl::setDirquna('member');/**/
        Tpl::showpage('member.add');
    }

    //检测用户是否存在
    private function select_tjr($tjr)
    {
        $net = Model('net');
        $re = $net->select_tjr($tjr);
        if ($re) {
            return $re;
        } else {
            showMessage('推荐人不存在,请检查!');
        }

    }

    //检查服务站是否存在
    private function select_ss($name)
    {
        $re = $this->biz_model->getOneBizByUsername($name);
        if ($re) {
            return $re;
        } elseif ($re['is_show'] == 0) {
            showMessage('该服务站已停用');
        } else {
            showMessage('服务站不存在,请检查!');
        }
        exit;
    }

    private function auto_chose_area()
    {
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
    private function check_wz($username, $wz)
    {
        $net = Model('net');
        $info = $net->select_wz($username);
        if ($info == 0) {
            showMessage('该接点用户尚未激活,不能添加下级');
        } elseif ($info == -1) {
            showMessage('该接点用户不存在,请检查');
        } else {
            if ($wz == 1) {
                if (empty($info['l_id'])) {
                    return $info['uid'];
                } else {
                    showMessage('该接点用户的市场位置左区已满!请重新选择');
                }
            } else {
                if (empty($info['r_id'])) {
                    return $info['uid'];
                } else {
                    showMessage('该接点用户的市场位置右区已满!请重新选择');
                }
            }
        }
    }

    /**
     * 会员升级
     */
    public function upLevelOp()
    {
        $uid = $_GET['id'];
        $userinfo = $this->user_model->getMemberInfoByID($uid);
        if (chksubmit()) {
            $uid = trim($_POST['uid']);
            $group_id = trim($_POST['group_id']);
            $group_info = $this->group_model->getOneGroup($group_id);
            $userinfo = $this->user_model->GetMemberInfoByID($uid);
            if ($group_id <= $userinfo['group_id']) {
                showMessage('升级等级不能低于当前等级！');
            }
            $lsk = $group_info['lsk'] - $userinfo['lsk'];
            $user_update['group_id'] = $group_id;
            $user_update['lsk'] = $group_info['lsk'];
            $user_where = array('id' => $uid);
            $r = $this->user_model->editMember($user_where, $user_update);
            if ($r) {
                $this->log('会员' . $userinfo['username'] . '升级为' . $group_info['name'], 1);
                //更新服务站业绩
                $biz_update = array(
                    'total' => array('exp', "total + $lsk")
                );
                $biz_where = array(
                    'id' => $userinfo['ssid']
                );
                $this->biz_model->editBiz($biz_where, $biz_update);
                //更新上级业绩
                $netInfo = $this->net_model->getNetByUser($uid);
                $this->user_model->addArea($netInfo['uid'], $netInfo['area'], $lsk);
                //升级报单配送
                $this->bonus_model->baodanpeisongUplevel($uid, $lsk);
                //销售奖
                $this->bonus_model->xiaoshou($uid, $lsk, '会员升级销售奖');
                //对碰奖
                // $this->bonus_model->dpj($uid);
                $this->bonus_model->liangpengjiangjisuan($uid);
                showMessage('升级成功!');
            } else {
                showMessage('升级失败!');
            }
        }
        $groupList = $this->group_model->getGroupList(array());
        foreach ($groupList as $item) {
            if ($userinfo['group_id'] == $item['group_id']) {
                $userinfo['group_name'] = $item['name'];
            }
        }
        Tpl::output('userinfo', $userinfo);
        Tpl::output('groupList', $groupList);
        Tpl::setDirquna('member');/**/
        Tpl::showpage('member.uplevel');

    }

    /**
     * 会员升星
     */
    public function upLevelStarOp()
    {
        $uid = intval($_GET['id']);
        $userinfo = $this->user_model->getMemberInfoByID($uid);
        $starSetting = unserialize(C('con_star'));
        if (chksubmit()) {
            $uid = trim($_POST['uid']);
            $star = trim($_POST['star']);
            if ($star <= $userinfo['star']) {
                showMessage('升级等级不能低于当前等级！');
            }
            $r = $this->user_model->editMember(array('id' => $uid), array('star' => $star));
            if ($r) {
                $this->log('会员' . $userinfo['username'] . '升星为' . $star, 1);
                showMessage('升级成功!');
            } else {
                showMessage('升级失败!');
            }

        }

        Tpl::output('userinfo', $userinfo);
        Tpl::output('starSetting', $starSetting);
        Tpl::setDirquna('member');/**/
        Tpl::showpage('member.uplevelstar');
    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        switch ($_GET['branch']) {
            /**
             * 验证会员是否重复
             */
            case 'check_user_name':
                $model_member = Model('member');
                $condition['username'] = $_GET['username'];
                $list = $model_member->getMemberInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 验证邮件是否重复
             */
            case 'check_email':
                $model_member = Model('member');
                $condition['email'] = $_GET['member_email'];
                // $condition['id'] = array('neq', intval($_GET['member_id']));
                $list = $model_member->getMemberInfo($condition);
                if (empty($list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_member = Model('member');
        $model_group = Model('group');
        //$member_grade = $model_member->getMemberGradeArr();
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'username',
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $member_list = $model_member->getMemberList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_member->shownowpage();
        $data['total_num'] = $model_member->gettotalnum();
        foreach ($member_list as $value) {
            $param = array();

            if ($value['status'] != 1) {
                $param['operation'] .= "<a class='btn green' href='index.php?act=member&op=activation&id=" . $value['id'] . "' >
                <i class='fa fa-key'></i>激活</a>";
            }else{
            $param['operation'] = "<a class='btn blue' href='index.php?act=member&op=member_edit&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>编辑</a> 
			
            <a class='btn red' href='index.php?act=member&op=member_del&id=" . $value['id'] . "' ><i class='fa fa-ban'></i>删除</a>
            <a class='btn red' href='index.php?act=member&op=upLevel&id=" . $value['id'] . "' ><i class='fa fa-level-up'></i>升级</a>
            <a class='btn red' href='index.php?act=member&op=upLevelStar&id=" . $value['id'] . "' ><i class='fa fa-level-up'></i>升星</a>";				
				
			}
            $param['id'] = $value['id'];
            $param['username'] = $value['username'];
            $param['group_name'] = $model_group->getOneGroupReField($value['group_id'], 'name');
            $param['star'] = $value['star'];
            $param['name'] = $value['name'];
            $param['tel'] = $value['tel'];
            $param['is_actived'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['bao_balance'] = $value['bao_balance'];
            $param['ji_balance'] = $value['ji_balance'];
            $param['zhang_balance'] = $value['zhang_balance'];
            $param['time'] = date("Y-m-d H:i:s", $value['time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

    /**
     * 性别
     * @return multitype:string
     */
    private function get_sex()
    {
        $array = array();
        $array[1] = '男';
        $array[2] = '女';
        $array[3] = '保密';
        return $array;
    }

    public function activationOp()
    {
        $uid = intval($_GET['id']);
        $result = $this->user_model->activation_user($uid, 'admin');
        if ($result) {
            $jihuouserinfo = $this->user_model->getMemberInfoByID($uid);
            $Net_info = $this->net_model->getNetByUser($uid);
            //触发奖项
            //直推奖
            $recommendInfo = $this->user_model->getMemberInfoByID($jihuouserinfo['rid']);
            $groupInfo = $this->group_model->getOneGroup($jihuouserinfo['group_id']);
            $this->bonus_model->recommendReward($groupInfo,$jihuouserinfo,
                $recommendInfo,1);
            //报单配送
//            $this->bonus_model->baodanpeisong($jihuouserinfo['id'], $jihuouserinfo['group_id']);
            //服务站补贴
            $this->bonus_model->ssSubsidy($jihuouserinfo['ssid'], $jihuouserinfo['id'], $jihuouserinfo['username'], $jihuouserinfo['lsk']);
//            //销售奖
//            $this->bonus_model->xiaoshou($jihuouserinfo['id'], $jihuouserinfo['lsk'], '销售奖');
//            //见点奖
//            $this->bonus_model->jiandian($jihuouserinfo['pid'], 1, $jihuouserinfo['id'], $jihuouserinfo['username']);
            //对碰奖
            $this->bonus_model->liangpengjiangjisuan($jihuouserinfo['id']);
            //层碰
            $this->bonus_model->cpj();
            showMessage('激活成功！');
        } else {
            showMessage('激活失败！', '', 'html', 'error');
        }
    }

    /**
     * csv导出
     */
    public function export_csvOp()
    {
        $model_member = Model('member');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['member_id'] = array('in', $id_array);
        }
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'member_avatar', 'member_email', 'member_mobile', 'member_sex', 'member_truename', 'member_birthday'
        , 'member_time', 'member_login_time', 'member_login_ip', 'member_points', 'member_exppoints', 'member_grade', 'available_predeposit'
        , 'freeze_predeposit', 'available_rc_balance', 'freeze_rc_balance', 'inform_allow', 'is_buy', 'is_allowtalk', 'member_state'
        );
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])) {
            $count = $model_member->getMemberCount($condition);
            if ($count > self::EXPORT_SIZE) {   //显示下载链接
                $array = array();
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('murl', 'index.php?act=member&op=index');
                Tpl::setDirquna('shop');/**/
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 . ',' . $limit2;
        }

        $member_list = $model_member->getMemberList($condition, '*', null, $order, $limit);
        $this->createCsv($member_list);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($member_list)
    {
        $model_member = Model('member');
        $member_grade = $model_member->getMemberGradeArr();
        // 性别
        $sex_array = $this->get_sex();
        $data = array();
        foreach ($member_list as $value) {
            $param = array();
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            $param['member_avatar'] = getMemberAvatarForID($value['member_id']);
            $param['member_email'] = $value['member_email'];
            $param['member_mobile'] = $value['member_mobile'];
            $param['member_sex'] = $sex_array[$value['member_sex']];
            $param['member_truename'] = $value['member_truename'];
            $param['member_birthday'] = $value['member_birthday'];
            $param['member_time'] = date('Y-m-d', $value['member_time']);
            $param['member_login_time'] = date('Y-m-d', $value['member_login_time']);
            $param['member_login_ip'] = $value['member_login_ip'];
            $param['member_points'] = $value['member_points'];
            $param['member_exppoints'] = $value['member_exppoints'];
            $param['member_grade'] = ($t = $model_member->getOneMemberGrade($value['member_exppoints'], false, $member_grade)) ? $t['level_name'] : '';
            $param['available_predeposit'] = ncPriceFormat($value['available_predeposit']);
            $param['freeze_predeposit'] = ncPriceFormat($value['freeze_predeposit']);
            $param['available_rc_balance'] = ncPriceFormat($value['available_rc_balance']);
            $param['freeze_rc_balance'] = ncPriceFormat($value['freeze_rc_balance']);
            $param['inform_allow'] = $value['inform_allow'] == '1' ? '是' : '否';
            $param['is_buy'] = $value['is_buy'] == '1' ? '是' : '否';
            $param['is_allowtalk'] = $value['is_allowtalk'] == '1' ? '是' : '否';
            $param['member_state'] = $value['member_state'] == '1' ? '是' : '否';
            $data[$value['member_id']] = $param;
        }

        $header = array(
            'member_id' => '会员ID',
            'member_name' => '会员名称',
            'member_avatar' => '会员头像',
            'member_email' => '会员邮箱',
            'member_mobile' => '会员手机',
            'member_sex' => '会员性别',
            'member_truename' => '真实姓名',
            'member_birthday' => '出生日期',
            'member_time' => '注册时间',
            'member_login_time' => '最后登录时间',
            'member_login_ip' => '最后登录IP',
            'member_points' => '会员积分',
            'member_exppoints' => '会员经验',
            'member_grade' => '会员等级',
            'available_predeposit' => '可用预存款(元)',
            'freeze_predeposit' => '冻结预存款(元)',
            'available_rc_balance' => '可用充值卡(元)',
            'freeze_rc_balance' => '冻结充值卡(元)',
            'inform_allow' => '允许举报',
            'is_buy' => '允许购买',
            'is_allowtalk' => '允许咨询',
            'member_state' => '允许登录'
        );
        array_unshift($data, $header);
        $csv = new Csv();
        $export_data = $csv->charset($data, CHARSET, 'gbk');
        $csv->filename = $csv->charset('member_list', CHARSET) . $_GET['curpage'] . '-' . date('Y-m-d');
        $csv->export($data);
    }
}
