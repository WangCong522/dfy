<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 ***/

defined('InShopBN') or exit('Access Invalid!');

class userControl extends BaseMemberControl
{
    private $group_model;
    private $user_model;
    private $trans_model;
    private $biz_model;
    private $net_model;
    private $bonus_model;

    public function __construct()
    {
        parent::__construct();
        $this->group_model = Model('group');
        $this->user_model = Model('member');
        $this->trans_model = Model('trans');
        $this->biz_model = Model('biz');
        $this->net_model = Model('net');
        $this->bonus_model = Model('bonus');
        Tpl::output('Zl_index_active', "active");
    }

    /**
     * 我的
     */
    public function viewOp()
    {
        Tpl::output('Zl_UserInfo_dian', "active");

        $user = Model('member');
        $parent = Model('parent');
        $net = Model('net');
        $record = Model('record');

        $UserInfo = $user->getMemberInfoByID($this->userid);
        $NetInfo = $net->getNetByUser($this->userid);

        $RecordInfo = $record->getRecordInfo(array('new_id' => $NetInfo['id']));

        $date = $user->GetXDate($UserInfo['overdue_time']);
        $group_name = $this->group_model->getOneGroupReField($UserInfo['group_id'], 'name');
        $recount = $user->getMemberCount(array('rid' => $this->userid));
        $UserInfo['recount'] = $recount;
        $myTeam = $parent->getParentList(array('parent' => $this->userid));
        $UserInfo['myTeam'] = count($myTeam);
        if ($this->userid == 1) {
            $UserInfo['myTeam'] += 1;
        }
        Tpl::output('group_name', $group_name);
        $TjrUserInfo = $user->getMemberInfoByID($RecordInfo['tjr_id']);
        $Jdr_net = $net->getNetByID($RecordInfo['jdr_id']);

        $JdrUserInfo = $user->getMemberInfoByID($Jdr_net['uid']);
        $UserInfo['tjr'] = $TjrUserInfo['username'] . "(" . $TjrUserInfo['id'] . ")";
        $UserInfo['jdr'] = $JdrUserInfo['username'] . "(" . $JdrUserInfo['id'] . ")";

        $PidNetInfo = $net->getNetByID($NetInfo['pid']);

        if ($NetInfo['id'] == $PidNetInfo['l_id']) {
            $wz = '左区';
        } else {
            $wz = '右区';
        }

        $UserInfo['wz'] = $wz;

        Tpl::output('info', $UserInfo);
        Tpl::output('NetInfo', $NetInfo);
        Tpl::output('date', $date);
        Tpl::output('tjr', $RecordTjr);

        Tpl::showpage('user.view');
    }

    public function editOp()
    {
        Tpl::output('Zl_Edit_dian', "active");

        $user = Model('member');

        $UserInfo = $user->getMemberInfoByID($this->userid);

        Tpl::output('info', $UserInfo);

        Tpl::showpage('user.edit');
    }

    public function doEditOp()
    {
        $user = Model('member');
        $data = array(
            'name' => $_POST['RealName'],
            'idcard' => $_POST['ID_Number'],
            'address' => $_POST['Address'],
            'tel' => $_POST['Mobile'],
            'qq' => $_POST['QQ'],
            'email' => $_POST['email'],
        );

        $res = $user->editMember(array('id' => $this->userid), $data);
        if ($res) showDialog('修改成功', '', 'succ');
        else showDialog('修改失败', '', 'error');

    }

    public function CheckPwdOp()
    {
        Tpl::output('Zl_CheckPwd_dian', "active");
        $user = Model('member');

        $UserInfo = $user->getMemberInfoByID($this->userid);

        $mb = empty($_POST['MIBaoKey']) ? "" : trim($_POST['MIBaoKey']);
        if ($mb) {
            if ($mb == $UserInfo['answer']) {
                header("Location:index.php?act=user&op=changePwd");
            } else {
                showDialog('错误', '', 'error');
            }
        }

        Tpl::output('info', $UserInfo);

        Tpl::showpage('user.CheckPwd');
    }

    public function changePwdOp()
    {
        $this->check_jy_pwd();
        Tpl::output('Zl_CheckPwd_dian', "active");
        Tpl::showpage('user.changePwd');
    }

    public function doChangePwdOp()
    {
        $user = Model('member');

        $UserInfo = $user->getMemberInfoByID($this->userid);

        $oldpwd = $_POST['oldpwd'];
        $newpwd = $_POST['newpwd'];
        $repwd = $_POST['repwd'];

        if (empty($newpwd)) {
            showDialog('密码不能为空', '', 'error');
        }
        if ($newpwd != $repwd) {
            showDialog('两次输入的密码不一样', '', 'error');
        }
        if ($UserInfo['password'] != md5($oldpwd)) {
            showDialog('旧密码错误', '', 'error');
        }

        $data = array('password' => md5($newpwd));
        $res = $user->editMember(array('id' => $this->userid), $data);

        if ($res) showDialog('修改成功，请使用新密码登陆', '', 'succ');
        else showDialog('修改失败', '', 'error');
    }

    public function ChangeErpwdOp()
    {
        Tpl::output('Zl_CheckPwd_dian', "active");
        Tpl::showpage('user.ChangeErPwd');
    }

    public function doChangeErpwdOp()
    {
        $user = Model('member');

        $UserInfo = $user->getMemberInfoByID($this->userid);

        $oldpwd = $_POST['oldpwd'];
        $newpwd = $_POST['newpwd'];
        $repwd = $_POST['repwd'];

        if (empty($newpwd)) {
            showDialog('密码不能为空', '', 'error');
        }
        if ($newpwd != $repwd) {
            showDialog('两次输入的密码不一样', '', 'error');
        }
        if ($UserInfo['jy_pwd'] != md5($oldpwd)) {
            showDialog('旧密码错误', '', 'error');
        }

        $data = array('jy_pwd' => md5($newpwd));
        $res = $user->editMember(array('id' => $this->userid), $data);

        if ($res) showDialog('修改成功', '', 'succ');
        else showDialog('修改失败', '', 'error');
    }

    public function ChangeMbOp()
    {
        Tpl::output('Zl_CheckPwd_dian', "active");
        Tpl::showpage('user.ChangeMb');
    }

    public function checkJuPwdOp()
    {
        $user = Model('member');
        if (chksubmit()) {
            $pwd = $_POST['L2Pwd'];
            $UserInfo = $user->getMemberInfoByID($this->userid);
            if ($UserInfo['jy_pwd'] != md5($pwd)) {
                showDialog('二级密码错误', '', 'error');
            } else {
                $_SESSION['pass_jy_pwd'] = 1;
                showDialog('验证成功！', '', 'succ');
            }
        }
    }

    public function doChangeMbOp()
    {
        $user = Model('member');

        $mbwt = $_POST['mbwt'];
        $mbda = trim($_POST['mbda']);

        if (empty($mbda)) {
            showDialog('答案不能为空', '', 'error');
        }

        $data = array('problem' => $mbwt, 'answer' => $mbda);
        $res = $user->editMember(array('id' => $this->userid), $data);

        if ($res) showDialog('修改成功', '', 'succ');
        else showDialog('修改失败', '', 'error');
    }

    public function UPLevelOp()
    {
        $this->check_jy_pwd();
        $userinfo = $this->user_model->getMemberInfoByID($this->userid);
        $groupList = $this->group_model->getGroupList(array());
        foreach ($groupList as $item) {
            if ($userinfo['group_id'] == $item['group_id']) {
                $userinfo['group_name'] = $item['name'];
            }
        }
        Tpl::output('Zl_UPLevel_dian', "active");
        Tpl::output('userinfo', $userinfo);
        Tpl::output('groupList', $groupList);
        Tpl::showpage('user.UPLevel');
    }

    //会员升级
    public function DoUpLevelOp()
    {
        $uid = $this->userid;
        $group_id = trim($_POST['group_id']);
        $group_info = $this->group_model->getOneGroup($group_id);
        $userinfo = $this->user_model->GetMemberInfoByID($uid);
        if ($group_id <= $userinfo['group_id']) {
            $this->error('升级等级不能低于当前等级！');
        }
        $lsk = $group_info['lsk'] - $userinfo['lsk'];
        if ($userinfo['bao_balance'] < $lsk) {
            $this->error('报单币余额不足！');
        }
        if ($lsk > 0) {
            //扣除报单费
            $user_update['bao_balance'] = array('exp', "bao_balance - $lsk");
            $user_update['group_id'] = $group_id;
            $user_update['lsk'] = $group_info['lsk'];
            $user_where = array('id' => $uid);
            $r = $this->user_model->editMember($user_where, $user_update);
            if ($r) {
/*                $this->trans_model->recorde($uid, (0 - $lsk), '报单币', '会员升级', '会员升级为' . $group_info['name']);
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
                // $this->bonus_model->dpj();
                $this->bonus_model->liangpengjiangjisuan($uid);*/
                $this->success('升级成功!');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    public function CheckNumOp()
    {
        $num = $_REQUEST['num'];

        $net = Model('net');
        $users = Model("member");

        $UserInfo = $users->getMemberInfo(array('num' => $num));

        if (!$UserInfo) {
            $UserInfo = $users->getMemberInfo(array('username' => $num));
            if (!$UserInfo) {
                exit(json_encode(array('info' => '该用户不存在!', 'status' => 0)));
            }
        }

        $NetInfo = $net->getNetByUser($UserInfo['id']);
        $MNetInfo = $net->getNetByUser($this->userid);
        if (!$NetInfo) {
            exit(json_encode(array('info' => '该用户不存在您的网络中!', 'status' => 0)));
        }

        $status = $net->CheckPid($NetInfo['id'], $MNetInfo['id']);

        $id = $UserInfo['id'] + 0;

        if ($status) {
            exit(json_encode(array('info' => $id, 'status' => 1)));
        } else {
            exit(json_encode(array('info' => '该用户不存在您的网络中!', 'status' => 0)));
        }

    }

    public function GetUserNameOp()
    {

        $username = $_REQUEST['username'];

        $users = Model("member");

        $UserInfo = $users->getMemberInfo(array('username' => $username));

        if (!$UserInfo) {
            $this->error('该用户不存在!');
        }
        $this->success($UserInfo['username']);
    }
	
    public function GetNameOp()
    {

        $username = $_POST['username'];

		 $users = Model("member");
        $UserInfo = $users->getMemberInfo(array('username' => $username));
	

        if ($UserInfo) {
          $this->success($UserInfo['name']);   
        }
		 $this->error('该用户不存在!');
        
    }	

    public function MaxCengOp()
    {
        $wz = $_REQUEST['wz'] + 0;

        $net = Model('net');
        $NetInfo = $net->getNetByUser($this->userid);
        $re = $net->MaxCeng($NetInfo['id'], $wz);
        if ($re) {
            exit(json_encode(array('info' => $re, 'status' => 1)));
        } else {
            exit(json_encode(array('info' => '错误,请刷新重试!', 'status' => 0)));
        }
    }

    public function UserStatusOp()
    {
        $id = $_REQUEST['id'] + 0;

        $net = Model('net');
        $re = $net->NetUserStatus($id);
        if ($re) {
            exit(json_encode(array('info' => $re, 'status' => 1)));
        } else {
            exit(json_encode(array('info' => $re, 'status' => 0)));
        }
    }

    public function XftimeOp()
    {
        $uid = $this->userid;

        $re = Model("bao")->XuFei($uid);

        if ($re['status']) {
            $this->success("");
        } else {
            $this->error($re['msg']);
        }
    }

}
