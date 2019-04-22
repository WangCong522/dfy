<?php
/**
 * 提现
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class txfailControl extends SystemControl
{
    /**
     * 每次导出订单数量
     * @var int
     */
    const EXPORT_SIZE = 1000;

    private $links = array(
        array('url' => 'act=remit&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=remit&op=remit_tpl', 'lang' => 'remit_tpl'),
    );
    private $remit_tx_model;
    private $users_model;
    private $trans;

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,remit');
        $this->remit_tx_model = Model('remit');
        $this->users_model = Model('member');
        $this->trans_model = Model('trans');
    }

    public function indexOp()
    {
        $this->remit_txOp();
    }

    /**
     * 商家消息模板
     */
    public function remit_txOp()
    {
        Tpl::setDirquna('fin');
        Tpl::showpage('txfail.index');
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
        $condition['optype'] = 2;
		$condition['status'] = 2;
        $remit_list = $model_remit->getRemitList($condition, '*', $page, $order);
		
        $data = array();
        $data['now_page'] = $model_remit->shownowpage();
        $data['total_num'] = $model_remit->gettotalnum();
        foreach ($remit_list as $value) {
            $param = array();
            $UserInfo = Model("member")->getMemberInfoByID($value['uid'], 'username');
            $param['username'] = $UserInfo['username'];
            $param['czfs'] = $this->fsExcn($value['czfs']);
            $param['zhanghao'] = $value['zhanghao'];
            $param['kaihuiming'] = $value['kaihuiming'];
            $param['jine'] = $value['jine'];
            $param['con_tx_shouxu'] = C('con_tx_shouxu') / 100 * $value['jine'];
            $param['sjjine'] = $value['jine'] * (1 - C('con_tx_shouxu') / 100);
            $param['date'] = $value['time'];
            $param['tel'] = $value['tel'];
            $param['is_actived'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $param['intro'] = $value['intro'];
            //
            //$param['time'] = date("Y-m-d H:i:s",$value['time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

  

    private function fsExcn($id)
    {
        switch ($id) {
            case 1:
                return "银行转账";
                break;
            case 2:
                return "支付宝";
                break;
            case 3:
                return "微信";
                break;
        }
    }

    /**
     * 导出
     *
     */
    public function export_step1Op()
    {
        $lang = Language::getLangContent();
        $model_remit = Model('remit');
        $condition = array();
        if (preg_match('/^[\d,]+$/', $_GET['id'])) {
            $_GET['id'] = explode(',', trim($_GET['id'], ','));
            $condition['id'] = array('in', $_GET['id']);
        }
        $this->_get_condition($condition);
        $sort_fields = array('username', 'czfs', 'zhanghao', 'kaihuiming', 'jine', 'con_tx_shouxu', 'sjjine', 'date', 'tel', 'is_actived');
        if ($_POST['sortorder'] != '' && in_array($_POST['sortname'], $sort_fields)) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        } else {
            $order = 'id desc';
        }
        $condition['optype'] = 2;
		$condition['status'] = 1;
        if (!is_numeric($_GET['curpage'])) {
            $count = $model_remit->getRemitCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE) {   //显示下载链接
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('murl', 'index.php?act=remit_tx&op=index');
                Tpl::showpage('export.excel');
            } else {  //如果数量小，直接下载
                $data = $model_remit->getRemitList($condition, '', '*', $order, self::EXPORT_SIZE);
                $this->createExcel($data);
            }
        } else {  //下载
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_remit->getRemitList($condition, '', '*', $order, "{$limit1},{$limit2}");
            $this->createExcel($data);
        }
    }


    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array())
    {
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id' => 's_title', 'Font' => array('FontName' => '宋体', 'Size' => '12', 'Bold' => '1')));
        //header
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '提现ID');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '用户名');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '方式');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '账号');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '户名');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '金额');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '手续费');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '实发');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '时间');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '电话');
        $excel_data[0][] = array('styleid' => 's_title', 'data' => '是否通过');
        //data
        foreach ((array)$data as $k => $order_info) {
            $UserInfo = Model("member")->getMemberInfoByID($order_info['uid'], 'username');
            $list = array();
            $list['id'] = $order_info['id'];
            $list['username'] = $UserInfo['username'];
            $list['czfs'] = $this->fsExcn($order_info['czfs']);
            $list['zhanghao'] = $order_info['zhanghao'];
            $list['kaihuiming'] = $order_info['kaihuiming'];
            $list['jine'] = $order_info['jine'];
            $list['con_tx_shouxu'] = C('con_tx_shouxu') / 100 * $order_info['jine'];
            $list['sjjine'] = $order_info['jine'] * (1 - C('con_tx_shouxu') / 100);
            $list['date'] = $order_info['time'];
            $list['tel'] = $order_info['tel'];
            $list['is_actived'] = $order_info['status'] == '1' ? '是' : '否';
            
            $tmp = array();
            $tmp[] = array('data' => $list['id']);
            $tmp[] = array('data' => $list['username']);
            $tmp[] = array('data' => $list['czfs']);
            $tmp[] = array('data' => $list['zhanghao']);
            $tmp[] = array('data' => $list['kaihuiming']);
            $tmp[] = array('data' => $list['jine']);
            $tmp[] = array('data' => $list['con_tx_shouxu']);
            $tmp[] = array('data' => $list['sjjine']);
            $tmp[] = array('data' => $list['date']);
            $tmp[] = array('data' => $list['tel']);
            $tmp[] = array('data' => $list['is_actived']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data, CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('提现', CHARSET));
        $excel_obj->generateXML('tx-' . $_GET['curpage'] . '-' . date('Y-m-d-H', time()));
    }

    /**
     * 处理搜索条件
     */
    private function _get_condition(& $condition)
    {
        if ($_REQUEST['query'] != '' && in_array($_REQUEST['qtype'], array('order_sn', 'store_name', 'buyer_name', 'pay_sn'))) {
            $condition[$_REQUEST['qtype']] = array('like', "%{$_REQUEST['query']}%");
        }
        if ($_GET['keyword'] != '' && in_array($_GET['keyword_type'], array('order_sn', 'store_name', 'buyer_name', 'pay_sn', 'shipping_code'))) {
            if ($_GET['jq_query']) {
                $condition[$_GET['keyword_type']] = $_GET['keyword'];
            } else {
                $condition[$_GET['keyword_type']] = array('like', "%{$_GET['keyword']}%");
            }
        }
        if (!in_array($_GET['qtype_time'], array('add_time', 'payment_time', 'finnshed_time'))) {
            $_GET['qtype_time'] = null;
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_start_date']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_GET['query_end_date']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_date']) : null;
        if ($_GET['qtype_time'] && ($start_unixtime || $end_unixtime)) {
            $condition[$_GET['qtype_time']] = array('time', array($start_unixtime, $end_unixtime));
        }
        if ($_GET['payment_code']) {
            if ($_GET['payment_code'] == 'wxpay') {
                $condition['payment_code'] = array('in', array('wxpay', 'wx_saoma', 'wx_jsapi'));
            } else {
                $condition['payment_code'] = $_GET['payment_code'];
            }
        }
        if (in_array($_GET['order_state'], array('0', '10', '20', '30', '40'))) {
            $condition['order_state'] = $_GET['order_state'];
        }
        if (!in_array($_GET['query_amount'], array('order_amount', 'shipping_fee', 'refund_amount'))) {
            $_GET['query_amount'] = null;
        }
        if (floatval($_GET['query_start_amount']) > 0 && floatval($_GET['query_end_amount']) > 0 && $_GET['query_amount']) {
            $condition[$_GET['query_amount']] = array('between', floatval($_GET['query_start_amount']) . ',' . floatval($_GET['query_end_amount']));
        }
        if (in_array($_GET['order_from'], array('1', '2'))) {
            $condition['order_from'] = $_GET['order_from'];
        }
    }

}
