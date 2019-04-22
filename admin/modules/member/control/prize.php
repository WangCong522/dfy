<?php
/**
 * 奖品
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class prizeControl extends SystemControl
{
    const EXPORT_SIZE = 1000;
    private $group_model;
    private $user_model;
    private $biz_model;
    private $net_model;
    private $trans_model;
    private $bonus_model;
    private $prize_model;

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
        $this->prize_model = Model('prize');
    }

        public function indexOp()
    {
        $this->memberOp();
    }

    /**
     * 奖品管理
     */
    public function memberOp()
    {
        Tpl::setDirquna('member');/**/
        Tpl::showpage('prize.index');
    }

    /**
     * 启用
     */
    public function activationOp()
    {
        $id = intval($_GET['id']);
        $result = Model('prize')->activation_prize($id);
        if ($result) {
            showMessage('启用成功！');
        } else {
            showMessage('启用失败！', '', 'html', 'error');
        }
    }

    /**
     * 删除
     */
    public function member_delOp()
    {
        $id = intval($_GET['id']);
        $result = Model('prize')->member_del_prize($id);
        if ($result) {
            showMessage('删除成功！');
        } else {
            showMessage('删除失败！', '', 'html', 'error');
        }
    }


    /**
     * 真删除
     */
    public function cedi_delOp()
    {
        $id = intval($_GET['id']);
        $result = Model('prize')->cedi_del_prize($id);
        if ($result) {
            showMessage('删除成功！');
        } else {
            showMessage('删除失败！', '', 'html', 'error');
        }
    }

    /**
     * 添加
     */
    public function shareOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('prize');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["group_id"], "require" => "true", "message" => '缺少会员等级'),
                array("input" => $_POST["prize_name"], "require" => "true", "message" => '缺少奖品名称'),
                array("input" => $_POST["prize_num"], "require" => "true", "message" => '缺少数量'),
                
            );  
            
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $groud_id = trim($_POST['group_id']);
                $prize_name = trim($_POST['prize_name']);
                $prize_num = trim($_POST['prize_num']);
                $prize_price = trim($_POST['prize_price']);
                $prize_money = trim($_POST['prize_money']);

                $insert_array = array();
                $insert_array['group_id'] = $groud_id;
                $insert_array['prize_name'] = $prize_name;
                $insert_array['prize_num'] = $prize_num;
                $insert_array['prize_price'] = $prize_price;
                $insert_array['prize_money'] = $prize_money;
                $insert_array['createtime'] = time();
                $insert_array['status'] = 1;

                $result = $model_member->addPrizeMsg($insert_array);

                if ($result) {
                    showMessage('信息添加成功', 'index.php?act=prize&op=index');
                } else {
                    showMessage('信息添加失败');
                }
            }
        }
        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        Tpl::setDirquna('member');
        Tpl::output('groupList', $groupList);
        Tpl::output('act', 'prize');
        Tpl::showpage('prize.add');
    }
    



    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_member = Model('prize');
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
        $member_list = $model_member->getPrizeList($condition, $page, $order, '*');

        $data = array();
        $data['now_page'] = $model_member->shownowpage();
        $data['total_num'] = $model_member->gettotalnum();
        foreach ($member_list as $value) {
            $param = array();

            if ($value['status'] != 1) {
                $param['operation'] .= "<a class='btn green' href='index.php?act=prize&op=activation&id=" . $value['id'] . "' >
                <i class='fa fa-key'></i>启用</a>";
            }else{
            $param['operation'] = "
            <a class='btn red' href='index.php?act=prize&op=member_del&id=" . $value['id'] . "' ><i class='fa fa-ban'></i>删除</a>
            <a class='btn red' href='index.php?act=prize&op=cedi_del&id=" . $value['id'] . "' ><i class='fa fa-ban'></i>彻底删除</a>
            ";               
                
            }
            $param['id'] = $value['id'];
            $param['group_id'] = $value['group_id'];
            $param['group_name'] = $model_group->getOneGroupReField($value['group_id'], 'name');
            $param['prize_name'] = $value['prize_name'];
            $param['prize_num'] = $value['prize_num'];
            $param['prize_price'] = $value['prize_price'];
            $param['prize_money'] = $value['prize_money'];
            $param['time'] = date("Y-m-d H:i:s", $value['createtime']);
            $param['status'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }
}