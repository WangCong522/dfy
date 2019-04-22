<?php
/**
 */
defined('InShopBN') or exit('Access Invalid!');

class moneyModel extends Model
{
    private $mtype = array('bao' => '报单币', 'dian' => '电子币', 'xu' => '电子股', 'sheng' => '升级币', 'zhang' => '购物币', 'yun' => '云货币', 'zhu' => '消费币', 'ji' => '积分');

    public function __construct()
    {
        parent::__construct('money');
    }

    public function change($from, $to, $userid, $amount)
    {
        $res = array('status' => 0);
        $mtype = $this->mtype;

        $trans = Model('trans');
        $users = Model("member");
        $message = Model('Message');

        $UserInfo = $users->getMemberInfoByID($userid);
        if ($UserInfo[$from . '_balance'] < $amount) {
            $res = array('status' => 0, 'msg' => '余额不足');
            return $res;
        }
        $from_old_mount = $UserInfo[$from . '_balance'];
        $to_old_mount = $UserInfo[$to . '_balance'];
        $from_new_mount = $UserInfo[$from . '_balance'] - $amount;
        $to_new_mount = $UserInfo[$to . '_balance'] + $amount;

        try {
            $this->beginTransaction();

            $data = array();
            $data[$from . '_balance'] = array('exp', $from . "_balance - $amount");
            $data[$to . '_balance'] = array('exp', $to . "_balance+$amount");
            $where = array('id' => $userid);

            $re = $users->editMember($where, $data);

            if (!$re) {
                $res['msg'] = '更改失败';
                throw new Exception();
            }
            //*
            $data = array(
                'uid' => $userid,
                'money_type' => $mtype[$from],
                'money' => (0 - $amount),
                'type' => '货币转换',
                'time' => time(),
                'intro' => '兑换' . $mtype[$to],
                'cod' => $from,
                'old_amount' => $from_old_mount,
                'new_amount' => $from_new_mount
            );

            $re = $this->table('trans')->insert($data);
            if (!$re) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $userid,
                'money_type' => $mtype[$to],
                'money' => $amount,
                'type' => '货币转换',
                'time' => time(),
                'intro' => '用' . $mtype[$from] . '兑换',
                'cod' => $to,
                'old_amount' => $to_old_mount,
                'new_amount' => $to_new_mount
            );

            $re = $this->table('trans')->insert($data);
            if (!$re) {
                $res['msg'] = '记录失败';
                throw new Exception();
            }

            $this->commit();
            $res['status'] = 1;
            return $res;
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }

        return $res;
    }

    //转账
    public function trans($from, $to, $cod, $amount)
    {
        $res = array('status' => 0);
        $mtype = $this->mtype;

        if ($from == $to) {
            $res = array('status' => 0, 'msg' => '你没事吧？转给自己？');
            return $res;
        }
        if ($from * $to == 0) {
            $res = array('status' => 0, 'msg' => '参数错误');
            return $res;
        }
        if ($amount <= 0) {
            $res = array('status' => 0, 'msg' => '金额错误');
            return $res;
        }

        $trans = Model('trans');
        $users = Model("member");
        $message = Model('Message');

        $UserInfo = $users->getMemberInfoByID($from);

        if ($UserInfo[$cod . '_balance'] < $amount) {
            $res = array('status' => 0, 'msg' => '没那么多钱了');
            return $res;
        }

        $toUserInfo = $users->getMemberInfoByID($to);

        $from_old_mount = $UserInfo[$cod . '_balance'];
        $to_old_mount = $toUserInfo[$cod . '_balance'];
        $from_new_mount = $UserInfo[$cod . '_balance'] - $amount;
        $to_new_mount = $toUserInfo[$cod . '_balance'] + $amount;

        try {
            $this->beginTransaction();

            $data = array();
            $data[$cod . '_balance'] = array('exp', $cod . "_balance - $amount");
            $where = array('id' => $from);

            $r = $users->editMember($where, $data);

            if (!$r) {
                $res['msg'] = '转出失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $from,
                'money_type' => $mtype[$cod],
                'money' => (0 - $amount),
                'type' => '会员转账',
                'time' => time(),
                'intro' => '转给会员' . $toUserInfo['id'] . '（' . $toUserInfo['username'] . '）',
                'cod' => $cod,
                'old_amount' => $from_old_mount,
                'new_amount' => $from_new_mount
            );

            $r = $this->table('trans')->insert($data);
            if (!$r) {
                $res['msg'] = '转出记录失败';
                throw new Exception();
            }

            $data = array(
                'formid' => 'A',
                'formname' => '系统提示',
                'totype' => 1,
                'toid' => $from,
                'title' => $mtype[$cod] . '转出',
                'addtime' => time(),
                'content' => '转给会员' . $toUserInfo['id'] . '（' . $toUserInfo['username'] . '）' . $amount . $mtype[$cod],
            );
            $this->table('message')->insert($data);

            $data = array();
            $data[$cod . '_balance'] = array('exp', $cod . "_balance + $amount");
            $where = array('id' => $to);

            $r = $users->editMember($where, $data);

            if (!$r) {
                $res['msg'] = '转入失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $to,
                'money_type' => $mtype[$cod],
                'money' => $amount,
                'type' => '会员转账',
                'time' => time(),
                'intro' => '会员' . $UserInfo['id'] . '（' . $UserInfo['username'] . '）转给你',
                'cod' => $cod,
                'old_amount' => $to_old_mount,
                'new_amount' => $to_new_mount
            );

            $r = $this->table('trans')->insert($data);
            if (!$r) {
                $res['msg'] = '转入记录失败';
                throw new Exception();
            }

            $data = array(
                'formid' => 'A',
                'formname' => '系统提示',
                'totype' => 1,
                'toid' => $to,
                'title' => $mtype[$cod] . '转入',
                'addtime' => time(),
                'content' => '会员' . $UserInfo['id'] . '（' . $UserInfo['username'] . '）转入' . $amount . $mtype[$cod] . '给你',
            );
            $this->table('message')->insert($data);

            $this->commit();
            $res['status'] = 1;
            return $res;

        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }

        return $res;
    }

    //管理员操作
    public function AdminChange($userid, $cod, $amount)
    {
        $res = array('status' => 0);
        $mtype = $this->mtype;

        $trans = Model('trans');
        $users = Model("member");
        $message = Model('Message');

        $UserInfo = $users->getMemberInfoByID($userid);

        $old_amount = $UserInfo[$cod . '_balance'];
        $new_amount = $UserInfo[$cod . '_balance'] + $amount;

        try {
            $this->beginTransaction();

            $data = array();
            $data[$cod . '_balance'] = array('exp', $cod . "_balance + $amount");
            $where = array('id' => $userid);

            $r = $users->editMember($where, $data);

            if (!$r) {
                $res['msg'] = '操作失败';
                throw new Exception();
            }

            $data = array(
                'uid' => $userid,
                'money_type' => $mtype[$cod],
                'money' => $amount,
                'type' => '管理员操作',
                'time' => time(),
                'intro' => '管理员操作',
                'cod' => $cod,
                'old_amount' => $old_amount,
                'new_amount' => $new_amount
            );

            $r = $this->table('trans')->insert($data);
            if (!$r) {
                $res['msg'] = '转出记录失败';
                throw new Exception();
            }

            $data = array(
                'formid' => 'A',
                'formname' => '系统提示',
                'totype' => 1,
                'toid' => $userid,
                'title' => $mtype[$cod] . '变化',
                'addtime' => time(),
                'content' => '管理员后台操作：' . $amount . $mtype[$cod],
            );
            $this->table('message')->insert($data);

            $this->commit();
            $res['status'] = 1;
            return $res;

        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }

        return $res;
    }

    public function HeSuan($userid)
    {
        $info = array();

        $do = 0;

        $users = Model("member");
        $record = Model("record");
        $trans = Model("trans");
        $lay = Model("lay");

        $yd = array('xu' => '1');
        $sd = array('xu' => '0');

        $UserInfo = $users->getMemberInfoByID($userid);
        $lays = $lay->getLayList(array('uid' => $userid));

        $c = 0;
        $j = 0;
        foreach ($lays as $key => $val) {
            if ($val['lay'] > 0) {
                if ($val['lay'] <= 5) {
                    if ($val['l_count'] > 0 && $val['r_count'] > 0) $c++;
                } else {
                    $num = ($val['l_count'] > $val['r_count']) ? $val['r_count'] : $val['l_count'];
                    $j += $num;
                }
            }
        }

        $yd['ceng'] = $c;
        $yd['jian'] = $j;

        $xu = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%赠送5000电子股%')));
        $sd['xu'] = $xu;

        $yrec = $record->getRecordCount(array('tjr_id' => $userid, 'status' => 1));
        $srec = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%推荐奖%')));
        $yd['tui'] = $yrec;
        $sd['tui'] = $srec;

        $sc = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%层碰奖%')));
        $sj = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%见点奖%')));
        $sd['ceng'] = $sc;
        $sd['jian'] = $sj;

        if ($yd['ceng'] > $sd['ceng'] || $yd['tui'] > $sd['tui'] || $yd['jian'] > $sd['jian'] || $yd['xu'] > $sd['xu']) {
            $do = 1;
        } else $do = 0;

        $info['yd'] = $yd;
        $info['sd'] = $sd;
        $info['do'] = $do;
        return $info;
    }

    public function AddBlance($userid)
    {
        $res = array('status' => 0, 'msg' => '结算失败');

        $users = Model("member");
        $record = Model("record");
        $trans = Model("trans");
        $lay = Model("lay");

        $yd = array('xu' => '1');
        $sd = array('xu' => '0');

        $UserInfo = $users->getMemberInfoByID($userid);
        $lays = $lay->getLayList(array('uid' => $userid));

        $c = 0;
        $j = 0;
        foreach ($lays as $key => $val) {
            if ($val['lay'] > 0 && $val['lay'] <= 5) {
                if ($val['l_count'] > 0 && $val['r_count'] > 0) $c++;
            } else {
                $num = ($val['l_count'] > $val['r_count']) ? $val['r_count'] : $val['l_count'];
                $j += $num;
            }
        }

        $yd['ceng'] = $c;
        $yd['jian'] = $j;

        $sd['ceng'] = $sc;

        $xu = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%赠送5000电子股%')));
        $sd['xu'] = $xu;

        $yrec = $record->getRecordCount(array('tjr_id' => $userid, 'status' => 1));
        $srec = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%推荐奖%')));
        $yd['tui'] = $yrec;
        $sd['tui'] = $srec;

        $sc = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%层碰奖%')));
        $sj = $trans->getTransCount(array('uid' => $userid, 'type' => array('like', '%见点奖%')));
        $sd['ceng'] = $sc;
        $sd['jian'] = $sj;

        $yes = 0;
        $do = array();

        if ($yd['ceng'] > $sd['ceng']) $do['ceng'] = $yd['ceng'] - $sd['ceng'];
        if ($yd['tui'] > $sd['tui']) $do['tui'] = $yd['tui'] - $sd['tui'];
        if ($yd['jian'] > $sd['jian']) $do['jian'] = $yd['jian'] - $sd['jian'];
        if ($yd['xu'] > $sd['xu']) $do['xu'] = $yd['xu'] - $sd['xu'];

        if ($yd['ceng'] > $sd['ceng'] || $yd['tui'] > $sd['tui'] || $yd['jian'] > $sd['jian'] || $yd['xu'] > $sd['xu']) {
            $yes = 1;
        } else $yes = 0;

        if (!$yes) {
            $res['msg'] = "无须校对";
            return $res;
        }

        $type = array('xu' => '补偿赠送5000电子股', 'tui' => '推荐奖补偿', 'ceng' => '层碰奖补偿', 'jian' => '见点奖补偿');
        $cod = array('xu' => 'xu', 'tui' => 'dian', 'ceng' => 'dian', 'jian' => 'dian');
        $names = array('xu' => '电子股', 'dian' => '电子币');
        $amount = array('xu' => 5000, 'tui' => 100, 'ceng' => 300, 'jian' => 50);

        try {
            $this->beginTransaction();

            foreach ($do as $key => $val) {
                for ($i = 0; $i < $val + 0; $i++) {
                    $data = array();
                    $data[$cod[$key] . "_balance"] = array('exp', $cod[$key] . "_balance + " . $amount[$key]);
                    $where = array('id' => $userid);

                    $r = $users->editMember($where, $data);

                    if (!$r) {
                        $res['msg'] = '加款失败';
                        throw new Exception();
                    }

                    $data = array(
                        'uid' => $userid,
                        'money_type' => $names[$cod[$key]],
                        'money' => $amount[$key],
                        'type' => $type[$key],
                        'time' => time(),
                        'intro' => $type[$key],
                        'cod' => $cod[$key],
                        'old_amount' => $UserInfo[$cod[$key] . '_balance'],
                        'new_amount' => ($UserInfo[$cod[$key] . '_balance'] + $amount[$key])
                    );

                    $UserInfo[$cod[$key] . '_balance'] += $amount[$key];

                    $r = $this->table('trans')->insert($data);
                    if (!$r) {
                        $res['msg'] = '记录失败';
                        throw new Exception();
                    }

                    $data = array(
                        'formid' => 'A',
                        'formname' => '系统提示',
                        'totype' => 1,
                        'toid' => $userid,
                        'title' => $type[$key],
                        'addtime' => time(),
                        'content' => '增加' . $amount[$key] . $names[$cod[$key]],
                    );
                    $this->table('message')->insert($data);

                }
            }

            $this->commit();
            $res['status'] = 1;
        } catch (Exception $e) {
            $this->rollback();
            return $res;
        }

        return $res;
    }

    public function check_jiang($uid, $time = "")
    {
        $res['status'] = 1;

        $begin = strtotime("2016-08-14");
        $time = strtotime("2016-08-26");
        $uid = 7386;

        $yd = $sd = array('tui' => 0, 'ceng' => 0, 'jian' => 0);

        $net = $this->table('net')->field("*")->where(array('uid' => $uid))->master($master)->find();
        $net_id = $net['id'];

        $l_id = $net['l_id'] + 0;
        $r_id = $net['r_id'] + 0;

        $row = $this->table('record')->field("count(*) as c")->where(array('tjr_id' => $uid, 'jh_time' => array('between', array($time, $time + 86400 - 1))))->master($master)->find();
        $yd['tui'] = $row['c'] + 0;

        $row = $this->table('trans')->field("count(*) as c")->where(array('uid' => $uid, 'time' => array('between', array($time, $time + 86400 - 1)), 'type' => array('like', '%推荐奖%')))->master($master)->find();
        $sd['tui'] = $row['c'] + 0;


        if ($l_id * $r_id <= 0) {
            //没有层碰，也没有见点
            exit();
        }

        if ($l_id) {
            //$sql = "select n.uid,u.login_time from user_net as n left join user_users as u on u.id=n.uid where n.id='$l_id'";
            $row = $this->table('net,users')->field("net.uid,users.login_time")->join('inner')->on('users.id=net.uid')->where(array('net.id' => $l_id))->master($master)->find();
            $l_id = $row['uid'] + 0;
            $l_time = strtotime(date("Y-m-d", $row['login_time'] + 0));
        }
        if ($r_id) {
            $row = $this->table('net,users')->field("net.uid,users.login_time")->join('inner')->on('users.id=net.uid')->where(array('net.id' => $r_id))->master($master)->find();
            $r_id = $row['uid'] + 0;
            $r_time = strtotime(date("Y-m-d", $row['login_time'] + 0));
        }

        if ($l_time == $time || $r_time == $time) {
            $yd['ceng']++;
        }

        $fields = "length(parent.parent)-length(replace(parent.parent,',',''))-1 as lay,parent.parent";
        $map = array('status' => 1);
        $map['parent.parent'] = array('like', "%,$uid,$l_id,%");
        $map['users.login_time'] = array('between', array(0, $time - 1));
        $order = "  lay desc ";
        $limit = " limit 1";
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $arr = explode(",", trim($row['parent'], ','));
        $k = array_search($uid, $arr);
        $p_l_lay = $row['lay'] - $k;

        $fields = "length(parent.parent)-length(replace(parent.parent,',',''))-1 as lay,parent.parent";
        $map = array('status' => 1);
        $map['parent.parent'] = array('like', "%,$uid,$r_id,%");
        $map['users.login_time'] = array('between', array(0, $time - 1));
        $order = "  lay desc ";
        $limit = " limit 1";
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $arr = explode(",", trim($row['parent'], ','));
        $k = array_search($uid, $arr);
        $p_r_lay = $row['lay'] - $k;

        $fields = "length(parent.parent)-length(replace(parent.parent,',',''))-1 as lay,parent.parent";
        $map = array('status' => 1);
        $map['parent.parent'] = array('like', "%,$uid,$l_id,%");
        $map['users.login_time'] = array('between', array(0, $time + 86400 - 1));
        $order = "  lay desc ";
        $limit = " limit 1";
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();
        $arr = explode(",", trim($row['parent'], ','));
        $k1 = array_search($uid, $arr);
        $n_l_lay = $row['lay'] - $k1;

        $fields = "length(parent.parent)-length(replace(parent.parent,',',''))-1 as lay,parent.parent";
        $map = array('status' => 1);
        $map['parent.parent'] = array('like', "%,$uid,$r_id,%");
        $map['users.login_time'] = array('between', array(0, $time + 86400 - 1));
        $order = "  lay desc ";
        $limit = " limit 1";
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $arr = explode(",", trim($row['parent'], ','));
        $k2 = array_search($uid, $arr);
        $n_r_lay = $row['lay'] - $k2;

        $kk = $k1 == 0 ? $k2 : $k1;

        $p_lay = $p_l_lay > $p_r_lay ? $p_r_lay : $p_l_lay;
        $n_lay = $n_l_lay > $n_r_lay ? $n_r_lay : $n_l_lay;

        if ($p_lay <= 5) {
            $ceng = $n_lay - $p_lay;
            if ($p_lay > 5) $ceng -= $n_lay - 5;
            $yd['ceng'] = $ceng;

            $row = $this->table('trans')->field("count(*) as c")->where(array('uid' => $uid, 'time' => array('between', array($time, $time + 86400 - 1)), 'type' => array('like', '%层碰奖%')))->master($master)->find();
            $sd['ceng'] = $row['c'] + 0;
        }

        //$sql = "select count(*) as c from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$l_id,%' and u.status=1 and u.login_time<" . $time . " and length(p.parent)-length(replace(p.parent,',',''))-1>$kk+5";

        $fields = "length(parent.parent)-length(replace(parent.parent,',',''))-1 as lay";
        $map = array('users.status' => 1);
        $map['parent.parent'] = array('like', "%,$uid,$l_id,%");
        $map['users.login_time'] = array('between', array(0, $time - 1));
        //$map[""] = array('between',array($kk+1+2,999999));
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->select();

        print_r($row);

        $p_l_count = $row['c'] + 0;
        echo $p_l_count;

        /*
        $fields = "count(*) as c";
        $map = array('status'=>1);
        $map['parent.parent'] = array('like',"%,$uid,$r_id,%");
        $map['users.login_time'] = array('between',array(0,$time-1));
        $map["length(parent.parent)-length(replace(parent.parent,',',''))-1"] = array('between',array($kk+1+5,999999));
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $p_r_count = $row['c'] + 0;

        $fields = "count(*) as c";
        $map = array('status'=>1);
        $map['parent.parent'] = array('like',"%,$uid,$l_id,%");
        $map['users.login_time'] = array('between',array(0,$time + 86400 -1));
        $map["length(parent.parent)-length(replace(parent.parent,',',''))-1"] = array('between',array($kk+1+5,999999));
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $n_l_count = $row['c'] + 0;

         $fields = "count(*) as c";
        $map = array('status'=>1);
        $map['parent.parent'] = array('like',"%,$uid,$r_id,%");
        $map['users.login_time'] = array('between',array(0,$time + 86400 -1));
        $map["length(parent.parent)-length(replace(parent.parent,',',''))-1"] = array('between',array($kk+1+5,999999));
        $row = $this->table('parent,users')->field($fields)->join('inner')->on('users.id=parent.uid')->where($map)->master($master)->order($order)->limit($limit)->find();

        $n_r_count = $row['c'] + 0;
        //*////

        $p_count = $p_l_count > $p_r_count ? $p_r_count : $p_l_count;
        $n_count = $n_l_count > $n_r_count ? $n_r_count : $n_l_count;

        $yd['jian'] += $n_count - $p_count;

        $row = $this->table('trans')->field("count(*) as c")->where(array('uid' => $uid, 'time' => array('between', array($time, $time + 86400 - 1)), 'type' => array('like', '%见点奖%')))->master($master)->find();
        $sd['jian'] = $row['c'] + 0;

        print_r($yd);
        print_r($sd);

        return $res;
    }

}
