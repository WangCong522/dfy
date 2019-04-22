<?php
/**
 *日奖金明细
 */
//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class dianControl extends SystemControl
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
        Tpl::output('act', 'dian');
        Tpl::showpage('dian.index');
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
        $fin_list = $this->bonuslaiyuan_model->getBonusLaiyuanList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        foreach ($fin_list as $value) {
            $param = array();
            //$param['operation'] = "<a class='btn blue' href='index.php?act=fin&op=read&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
            $param['time'] = date("Y-m-d H:i:s", $value['time']);
            $param['uid'] = $value['uid'];
            $param['username'] = $value['username'];
            $param['money_type'] = $value['money_type'];
            $param['money'] = $value['money'];
            $param['intro'] = $value['intro'];
            $param['rel_username'] = $value['rel_username'];
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }


}
