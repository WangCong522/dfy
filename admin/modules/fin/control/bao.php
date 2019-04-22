<?php
/**
 *奖金明细
 */
//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class baoControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=fin&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=fin&op=fin_tpl', 'lang' => 'fin_tpl'),
    );

    private $bonuslog_model;
    private $bonuslaiyuan_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,fin');
        $this->bonuslog_model = Model('bonuslog');
        $this->bonuslaiyuan_model = Model('bonuslaiyuan');
    }

    public function indexOp()
    {
        $this->finOp();
    }

    /**
     * 财务
     */
    public function finOp()
    {
        Tpl::setDirquna('fin');
        Tpl::output('act', 'bao');
        Tpl::showpage('bao.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $condition = array();
        if ($_POST['query'] != '') {
            //$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
            $condition['where'] = $_POST['qtype'] . " like '%" . $_POST['query'] . "%'";
        }
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        $fin_list = $this->bonuslog_model->getBonusLogList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ($fin_list as $value) {
            $param = array();
            //$param['operation'] = "<a class='btn blue' href='index.php?act=fin&op=read&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
            $param['uid'] = $value['uid'];
            $param['bdate'] = $value['bdate'];
            $param['username'] = $value['username'];
            $param['b1'] = $value['b1'];
            $param['b3'] = $value['b3'];
            $param['b2'] = $value['b2'];
            $param['b11'] = $value['b11'];
            $param['b12'] = $value['b12'];
            //$param['b4'] = $value['b4'];
            //$param['b10'] = $value['b10'];
            $param['b7'] = $value['b7'];
            $param['b5'] = $value['b5'];
            //$param['b6'] = $value['b6'];
            //$param['b8'] = $value['b8'];
            //$param['b9'] = $value['b9'];
            $param['b0'] = $value['b0'];
            //
            //$param['is_actived'] = $value['status'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            //$param['time'] = date("Y-m-d H:i:s",$value['time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }


}
