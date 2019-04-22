<?php
/**
 * 汇款
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class remitControl extends SystemControl
{
    private $remit_tx_model;
    private $links = array(
        array('url' => 'act=remit&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=remit&op=remit_tpl', 'lang' => 'remit_tpl'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,remit');
        $this->remit_tx_model = Model('remit');
    }

    public function indexOp()
    {
        $this->remitOp();
    }

    /**
     * 商家消息模板
     */
    public function remitOp()
    {
        Tpl::setDirquna('fin');
        Tpl::showpage('remit.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_remit = Model('remit');

        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'title',
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $condition ['optype'] = 1;
        $remit_list = $model_remit->getRemitList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_remit->shownowpage();
        $data['total_num'] = $model_remit->gettotalnum();
        foreach ($remit_list as $value) {
            $param = array();
            $UserInfo = Model("member")->getMemberInfoByID($value['uid'], 'username');
            $param['username'] = $UserInfo['username'];
            //$param['operation'] = "<a class='btn blue' href='index.php?act=remit&op=remit_edit&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['czfs'] = $value['czfs'];
            $param['zhanghao'] = $value['zhanghao'];
            $param['kaihuiming'] = $value['kaihuiming'];
            $param['jine'] = $value['jine'];
            $param['hkzh'] = $value['hkzh'];
            $param['date'] = $value['time'];
            $param['tel'] = $value['tel'];
            $param['is_actived'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            if ($value['status'] == 0) {
                $param['operation'] .= "<a class='btn blue' href='index.php?act=remit&op=adopted&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>通过</a>";
                $param['operation'] .= "<a class='btn blue' href='index.php?act=remit&op=refuse&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>拒绝</a>";
            } else {
                $param['operation'] .= "<a class='btn blue' href='" . ADMIN_SITE_URL . "/modules/member/index.php?act=member&op=member_edit&id=" . $value['uid'] . "'><i class='fa fa-pencil-square-o'></i>立即充值</a>";
            }   
            $param['intro'] = $value['intro'];
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

    /**通过
     *
     *
     */
    public function adoptedOp()
    {
        $condition['id'] = intval($_GET['id']);
        $update_array = array(
            'status' => 1
        );
        $check = $this->remit_tx_model->editRemit($condition, $update_array);
        if ($check) {
            showMessage("操作成功");
        } else {
            showMessage("操作失败");
        }
    }

    /*
    * 拒绝
    */
    public function refuseOp()
    {
        $condition['id'] = intval($_GET['id']);
        $update_array = array(
            'status' => 2
        );
        $check = $this->remit_tx_model->editRemit($condition, $update_array);
        if ($check) {
            showMessage("操作成功");
        } else {
            showMessage("操作失败");
        }
    }


}
