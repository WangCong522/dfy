<?php
/**
 * 汇款
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class remitControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=remit&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=remit&op=remit_tpl', 'lang' => 'remit_tpl'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,remit');
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
        Tpl::setDirquna('member');
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
            $param['intro'] = $value['intro'];
            //
            //$param['is_actived'] = $value['status'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            //$param['time'] = date("Y-m-d H:i:s",$value['time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }


}
