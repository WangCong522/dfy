<?php
/**
 *
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class xuControl extends SystemControl
{
    private $user_model;
    private $bonuslog_model;
    private $links = array(
        array('url' => 'act=fin&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=fin&op=fin_tpl', 'lang' => 'fin_tpl'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,fin');
        $this->user_model = Model('member');
        $this->bonuslog_model = Model('bonuslog');
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
        $sum_lsk_re = $this->user_model->getMemberInfo(array('id' => array('neq', 1), 'status' => 1), 'sum(lsk) as sum');
        $sum_lsk = $sum_lsk_re['sum'] ? $sum_lsk_re['sum'] : 0;
        $sum_award_list = $this->bonuslog_model->getBonusLogsList(array('id' => array('neq', 1)));
        $sum_award = 0;
        if ($sum_award_list) {
            foreach ($sum_award_list as $item) {
                $sum_award += ($item['b0'] - $item['b8']);
            }
        }
        //拔比
        if ($sum_lsk > 0) {
            $bili = round($sum_award / $sum_lsk, 4) * 100;
        } else {
            $bili = 0;
        }

        Tpl::setDirquna('fin');
        Tpl::output('act', 'xu');
        Tpl::output('sum_lsk', $sum_lsk);
        Tpl::output('sum_award', $sum_award);
        Tpl::output('bili', $bili);
        Tpl::showpage('xu.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_fin = Model('trans');

        $condition = array('money_type' => '电子股');
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
            $param['username'] = $value['username'];
            $param['money_type'] = $value['money_type'];
            $param['money'] = $value['money'];
            $param['old_amount'] = $value['old_amount'];
            $param['new_amount'] = $value['new_amount'];
            $param['date'] = date("Y-m-d H:i:s", $value['time']);
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
