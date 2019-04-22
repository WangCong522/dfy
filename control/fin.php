<?php
/**
 *
 ***/

defined('InShopBN') or exit('Access Invalid!');

class finControl extends BaseMemberControl
{
    private $bonuslaiyuan_model;
    private $bonuslog_model;

    public function __construct()
    {
        parent::__construct();
        $this->bonuslaiyuan_model = Model('bonuslaiyuan');
        $this->bonuslog_model = Model('bonuslog');
        Tpl::output('Money_index_active', "active");
    }

    /**
     * 我的
     */
    public function hesuanOp()
    {
        $info = Model("money")->HeSuan($this->userid);

        Tpl::output('info', $info);
        Tpl::output('Money_hesuan_dian', "active");

        Tpl::showpage('fin.hesuan');
    }

    public function changeOp()
    {
        $this->check_jy_pwd();
        $level = Model("member")->GetUserLevel($this->userid);
        Tpl::output('ulevel', $level['status']);
        Tpl::output('Money_Money_dian', "active");

        Tpl::showpage('fin.change');
    }

    //货币转换
    public function HbzhOp()
    {
        $bao = Model('bao');
        $user = Model('member');
        $money = Model('money');

        $level = $user->GetUserLevel($this->userid);
        $level = $level['status'];

        $zc = $_POST['FromCurrencyId'];
        $zr = $_POST['ToCurrencyId'];
        $jine = $_POST['Amount'];

        $UserInfo = $user->getMemberInfoByID($this->userid);

        if ((int)$jine > 0) {
            if ($UserInfo[$zc . '_balance'] - $jine < 0) {
                showDialog('账户余额不足!', '', 'error', '');
            }
            if ($zr == $zc) {
                showDialog('转出转入相同!', '', 'error', '');
            } else {
                $res = $money->change($zc, $zr, $this->userid, $jine);
                //
                if ($res['status']) {
                    showDialog('转换成功!', '', 'succ', '');
                } else {
                    showDialog($res['msg'], '', 'error', '');
                }
            }
        } else {
            showDialog('请输入要转出的金额', '', 'error', '');
        }
    }

    public function tranOp()
    {
        $this->check_jy_pwd();
        $level = Model("member")->GetUserLevel($this->userid);

        Tpl::output('ulevel', $level['status']);

        Tpl::output('Money_Hyzz_dian', "active");

        Tpl::showpage('fin.tran');
    }

    public function CheckInfoOp()
    {
        $username = trim($_REQUEST['username']);
        //$pwd= trim($_REQUEST['pwd']);
        $type = $_REQUEST['type'];
        $amount = $_REQUEST['amount'] + 0;
        $net = Model('net');
        $user = Model('member');
        $money = Model("money");

        $UserInfo = $user->getMemberInfo(array('username' => $username));
        if (!$UserInfo) {
            $this->error('用户不存在,请查询后输入');
        }
        $to = $UserInfo['id'];

        $res = $money->trans($this->userid, $to, $type, $amount);

        if ($res['status']) {
            $this->success('');
        } else $this->error($res['msg']);
    }

    public function cwmxOp()
    {
        $this->ListT(array('uid' => $this->userid));
    }

    public function change_listOp()
    {
        $this->ListT(array('uid' => $this->userid, 'type_like' => '货币转换'));
    }

    public function tran_listOp()
    {
        $this->ListT(array('uid' => $this->userid, 'type_like' => '会员转账'));
    }

    private function ListT($map)
    {
        $trans = Model('trans');

        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');

        $info = $trans->getTransList($map, $page);

        Tpl::output('show_page', $page->show());

        Tpl::output('list', $info);

        Tpl::output('Money_Cwmx_dian', "active");

        Tpl::showpage('fin.cwmx');
    }

    public function hkOp()
    {
        $arr = array(
            'icbc' => C('con_icbc_account'),
            'abc' => C('con_abc_account'),
            'boc' => C('con_boc_account'),
            'ccb' => C('con_ccb_account'),
            'alipay' => C('con_alipay_account'),
            'wx' => C('con_wx_account'),
            'aliimg' => C('con_aliimg_account'),
            'wximg' => C('con_wximg_account'),
        );

        Tpl::output('info', $arr);

        Tpl::output('Money_Hk_dian', "active");

        Tpl::showpage('fin.hk');
    }

    public function hklogOp()
    {
        $remit = Model('remit');
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $condition = array(
            'uid' => $this->userid,
            'optype' => 1
        );
        $txList = $remit->getRemitListJs($condition, $page);
        if ($txList) {
            foreach ($txList as &$item) {
                $item['czfs'] = $this->fsExcn($item['czfs']);
            }
        }
        Tpl::output('show_page', $page->show());
        Tpl::output('txList', $txList);
        Tpl::output('Money_Hk_dian', "active");
        Tpl::showpage('fin.hklog');
    }

    public function DoHkOp()
    {
        $remit = Model('remit');
        if ($_POST['type'] + 0 == 1) {
            $str = $_POST['changebank'] . $_POST['bankzh'] . $_POST['hkzh'];
        }
        $data = array(
            'uid' => $this->userid,
            'czfs' => $_POST['type'],
            'zhanghao' => $_POST['bankzh'],
            'hkzh' => $_POST['hkzh'],
            'kaihuiming' => $_POST['zhm'],
            'jine' => $_POST['jine'],
            'time' => $_POST['time'],
            'tel' => $_POST['tel'],
            'intro' => $_POST['intro'],
            'shouinfo' => $str,
            'status' => 0,
            'optype' => 1
        );
        if ($remit->AddRecord($data)) {
            showDialog('提交成功', '', 'succ', '');
        } else {
            showDialog('提交失败', '', 'error', '');
        }
    }

    public function txOp()
    {
        $this->check_jy_pwd();
        $user = Model('member');
        $userInfo = $user->getMemberInfoByID($this->userid);
        $arr = array(
            'icbc' => C('con_icbc_account'),
            'abc' => C('con_abc_account'),
            'boc' => C('con_boc_account'),
            'ccb' => C('con_ccb_account'),
            'alipay' => C('con_alipay_account'),
            'wx' => C('con_wx_account'),
            'aliimg' => C('con_aliimg_account'),
            'wximg' => C('con_wximg_account'),
            'tx_jine' => C('con_tx_jine'),
            'userjifen' => $userInfo['ji_balance']
        );

        Tpl::output('info', $arr);

        Tpl::output('Money_Tx_dian', "active");

        Tpl::showpage('fin.tx');
    }

    public function detailOp()
    {
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');

        $info = $this->bonuslog_model->getBonusLogList(array('uid' => $this->userid), $page);

        Tpl::output('show_page', $page->show());

        Tpl::output('list', $info);

        Tpl::output('Money_Jjmx_dian', "active");

        Tpl::showpage('fin.detail');
    }

    public function dotxOp()
    {
        $user = Model('member');
        $trans = Model('trans');

        $userInfo = $user->getMemberInfoByID($this->userid);
        $tx_jine = 100;//C('con_tx_jine');
        $remit = Model('remit');
        if ($_POST['type'] + 0 == 1) {
            $str = $_POST['changebank'];//银行名称
            $bank = $this->bank_cn($_POST['changebank']);
        }
        $data = array(
            'uid' => $this->userid,
            'czfs' => $_POST['type'],//提现方式
            'zhanghao' => $_POST['bankzh'],//收款账号
            'hkzh' => $_POST['hkzh'],//汇款账号
            'kaihuiming' => $_POST['zhm'],//账户名
            'jine' => $_POST['jine'],//提现金额
            'time' => date('Y-m-d', time()),
            'tel' => $_POST['tel'],
            'intro' => $bank,
            'shouinfo' => $str,
            'status' => 0,
            'optype' => 2
        );
        if ($userInfo['ji_balance'] < $data['jine']) {
            showDialog('积分余额不足', '', 'error', '');
        }
        
        if ($data['jine'] % $tx_jine != 0) {
            showDialog('转出金额必须是' . $tx_jine . '的整数倍', '', 'error', '');
        }
        
        if ($remit->AddRecord($data)) {
            $member_where = array(
                'id' => $this->userid
            );
            $member_updata = array(
                'ji_balance' => $userInfo['ji_balance'] - $data['jine'],
            );
            $user->editMember($member_where, $member_updata);
            $money = '-' . $data['jine'];
            $trans->recorde($this->userid, $money, '积分', '申请提现', '申请提现');
            showDialog('提交成功', '', 'succ', '');
        } else {
            showDialog('提交失败', '', 'error', '');
        }
    }

    private function bank_cn($id)
    {
        $bank_arr = array(
            '1' => '中国银行',
            '2' => '中国农业银行',
            '3' => '中国建设银行',
            '4' => '中国工商银行',
            '6' => '中国交通银行',
            '7' => '中国浦发银行',
            '8' => '中国光业银行',
            '9' => '中国邮政储蓄',
            '10' => '中国华夏银行',
        );
        return $bank_arr[$id];
    }

    public function txlogOp()
    {
        $remit = Model('remit');
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $tx_set_model = Model('txset');
        $txSetInfo = $tx_set_model->getOne(1);
        $feeRate = $txSetInfo['fee_rate'];//管理费
        $handlingFeeRate = $txSetInfo['handling_fee_rate'];//手续费
        $condition = array(
            'uid' => $this->userid,
            'optype' => 2
        );
        $txList = $remit->getRemitListJs($condition, $page);
        if ($txList) {
            foreach ($txList as &$item) {
                $item['jine'] = $item['jine'].'元';
                $item['czfs'] = $this->fsExcn($item['czfs']);
                $item['sjjine'] = $item['jine'] * (1 - $handlingFeeRate / 100
                        - $feeRate / 100).'元';
                $item['con_tx_jine'] = $handlingFeeRate . '%';
                $item['guanlifei'] = $feeRate .'%';
            }
        }
        Tpl::output('show_page', $page->show());
        Tpl::output('txList', $txList);
        Tpl::output('Money_Tx_dian', "active");
        Tpl::showpage('fin.txlog');
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

    public function ddtOp()
    {

        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');

        $fromtime = $_REQUEST['order_date_from'];
        $totime = $_REQUEST['order_date_to'];
        if (empty($_SESSION['ddt']['fromtime']) || empty($_SESSION['ddt']['totime'])) {
            $y = date("Y");
            $m = date("m");
            $d = date("d");
            $fromtime = mktime(0, 0, 0, $m, $d, $y);
            $totime = mktime(23, 59, 59, $m, $d, $y);
            $_SESSION['ddt']['fromtime'] = $fromtime;
            $_SESSION['ddt']['totime'] = $totime;
        } else {
            if ($fromtime && $totime) {
                $fromtime = strtotime($fromtime . ' ' . '0:' . '0:' . '0');
                $totime = strtotime($totime . ' ' . '23:' . '59:' . '59');
                if ($fromtime != $_SESSION['ddt']['fromtime']) {
                    $_SESSION['ddt']['fromtime'] = $fromtime;
                }
                if ($totime != $_SESSION['ddt']['totime']) {
                    $_SESSION['ddt']['totime'] = $totime;
                }
            }
        }

        $info = $this->bonuslaiyuan_model->getBonusLaiyuanList(array('uid' => $this->userid, 'time_between' => array($_SESSION['ddt']['fromtime'], $_SESSION['ddt']['totime'])), $page);

        Tpl::output('show_page', $page->show());

        Tpl::output('list', $info);

        Tpl::output('fromtime', $_SESSION['ddt']['fromtime']);
        Tpl::output('totime', $_SESSION['ddt']['totime']);

        Tpl::output('Money_Rjjmx_dian', "active");

        Tpl::showpage('fin.ddt');
    }

    public function AddBlanceOp()
    {
        /*
        $res = Model("money")->AddBlance($this->userid);
        if($res['status']){
            $this->success("ok");
        }
        else $this->error($res['msg']);
        //*////
    }

    public function check_jiangOp()
    {
        Tpl::output('Money_hesuan_dian', "active");
        Tpl::showpage('fin.check_jiang');
    }

    public function query_jiangOp()
    {
        $res = array('status' => 0);
        $time = strtotime($_REQUEST['d']);
        $url = "http://" . DBHOST . "/ys/jiang.php?id=" . $this->userid . "&t=" . $time;
        $json = file_get_contents($url);

        echo $json;
    }
}
