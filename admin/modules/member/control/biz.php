<?php
/**
 * 服务站管理
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class bizControl extends SystemControl
{
    const EXPORT_SIZE = 1000;

    private $biz_model;
    private $bonus_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('member');
        $this->biz_model = Model('biz');
        $this->bonus_model = Model('bonus');
    }

    public function indexOp()
    {
        $this->bizOp();
    }

    /**
     * 会员管理
     */
    public function bizOp()
    {
        Tpl::setDirquna('member');/**/
        Tpl::showpage('biz.index');
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
     * 添加报单中心
     */
    public function biz_addOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('member');
        /**
         * 保存
         */
        if (chksubmit()) {
            $username = trim($_POST["username"]);
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $username, "require" => "true", "message" => $lang['member_add_name_null'])
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $userInfo = $model_member->getMemberInfoByUsername($username);
                if ($userInfo['status']) {
                    if (!$userInfo['is_biz']) {
                        $insert_data = array(
                            'uid' => $userInfo['id'],
                            'addtime' => time(),
                            'status' => 1,
                            'username' => $userInfo['username']
                        );
                        $result = $this->biz_model->add($insert_data);
                        if ($result) {
                            $model_member->editMember(array('id' => $userInfo['id']), array('is_biz' => 1));
                            showMessage('添加成功!');
                        } else {
                            showMessage('添加失败!', '', 'html', 'error');
                        }
                    } else {
                        showMessage('该用户已是报单中心!', '', 'html', 'error');
                    }
                } else {
                    showMessage('用户不存在或未激活!', '', 'html', 'error');
                }
            }
        }
        Tpl::setDirquna('member');/**/
        Tpl::showpage('biz.add');
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
                $condition['member_name'] = $_GET['member_name'];
                $condition['member_id'] = array('neq', intval($_GET['member_id']));
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
                $condition['member_email'] = $_GET['member_email'];
                $condition['member_id'] = array('neq', intval($_GET['member_id']));
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
        $condition = array();
        if (!empty($_POST['qtype'])) {
            $condition['typeid'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['like_title'] = $_POST['query'];
        }
        if (!empty($_POST['sortname']) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $condition['order'] = ltrim($condition['order'] . ',id desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $condition['field'] = 'biz.*,users.username';
        $biz_list = $this->biz_model->getJoinList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ($biz_list as $value) {
            $param = array();
            $param['operation'] = '';
            if ($value['status'] == 0) {
                $param['operation'] = "<a class='btn blue' href='index.php?act=biz&op=adopt&id=" . $value['id'] . "&uid=" . $value['uid'] . "'>
                <i class='fa fa-pencil-square-o'></i>通过</a>";
            }
            if ($value['status'] == 1 && $value['is_show'] == 1) {
                $param['operation'] = "<a class='btn blue' href='index.php?act=biz&op=trunOff&is_show=0&id=" . $value['id'] . "&uid=" . $value['uid'] . "'>
                <i class='fa fa-pause'></i>停用</a>";
            } elseif ($value['status'] == 1 && $value['is_show'] == 0) {
                $param['operation'] = "<a class='btn blue' href='index.php?act=biz&op=trunOff&is_show=1&id=" . $value['id'] . "&uid=" . $value['uid'] . "'>
                <i class='fa fa-play'></i>启用</a>";
            }
            $param['id'] = $value['id'];
            $param['uid'] = $value['uid'];
            $param['username'] = $value['username'];
            $param['count'] = $value['count'];
            $param['total'] = $value['total'];
            $param['addtime'] = date("Y-m-d H:i:s", $value['addtime']);
            $param['trun_off'] = $value['is_show'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $param['is_actived'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
    }

    public function adoptOp()
    {
        $id = $_GET['id'];
        $uid = $_GET['uid'];
        $url = 'index.php?act=biz';
        if ($id && $uid) {
            $update_array = array(
                'id' => $id,
                'status' => 1
            );
            $result = $this->biz_model->updates($update_array);
            if ($result) {
                //服务站推荐奖
                $this->bonus_model->ssTuijian($uid);
                showMessage('审核成功！', $url);
            } else {
                showMessage('审核失败！', $url);
            }
        } else {
            showMessage('参数错误！', $url);
        }
    }

    public function trunOffOp()
    {
        $id = intval($_GET['id']);
        $is_show = intval($_GET['is_show']);
        $url = 'index.php?act=biz';
        $bizInfo = $this->biz_model->getOneBiz($id);
        if ($bizInfo['status'] == 1) {
            $check = $this->biz_model->updates(array('id' => $id, 'is_show' => $is_show));
            if ($check) {
                showMessage('操作成功！');
            } else {
                showMessage('操作失败！', $url, 'html', 'error');
            }
        } else {
            showMessage('该服务站未激活！', $url, 'html', 'error');
        }
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


}
