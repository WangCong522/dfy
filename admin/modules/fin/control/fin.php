<?php
/**
 * 汇款
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class finControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=fin&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=fin&op=fin_tpl', 'lang' => 'fin_tpl'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,fin');
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
        Tpl::output('act', 'fin');
        Tpl::showpage('fin.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_fin = Model('trans');

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
        $fin_list = $model_fin->getTrans($condition, 'trans.*,users.username', $page, $order);

        $data = array();
        $data['now_page'] = $model_fin->shownowpage();
        $data['total_num'] = $model_fin->gettotalnum();
        foreach ($fin_list as $value) {
            $param = array();
            //$param['operation'] = "<a class='btn blue' href='index.php?act=fin&op=read&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
            $param['date'] = date("Y-m-d H:i:s", $value['time']);
            $param['username'] = $value['username'];
            $param['money_type'] = $value['money_type'];
            $param['money'] = $value['money'];
            $param['type'] = $value['type'];
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
