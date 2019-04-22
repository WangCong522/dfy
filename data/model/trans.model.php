<?php
/**
 *报单币
 */
defined('InShopBN') or exit('Access Invalid!');

class transModel extends Model
{

    public function __construct()
    {
        parent::__construct('trans');
    }

    //记录收入
    public function recorde($uid, $num, $money_type, $type, $intro = null)
    {
        $data = array(
            'uid' => $uid,
            'money_type' => $money_type,
            'money' => $num,
            'type' => $type,
            'time' => time(),
            'intro' => $intro,
        );
        $insert_id = $this->table('trans')->insert($data);
        return $insert_id;
    }

    //删除记录
    public function delTrans($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('trans', $where);
            return $result;
        } else {
            return false;
        }
    }

    //获取一天数据
    public function getshouru($loginid, $day = 1)
    {
        $money = 0;
        $where['uid'] = $loginid;
        $where['money_type'] = '电子币';
        $time = strtotime(date('Y-m-d'));
        $one = 24 * 60 * 60;
        $end = $time - (($day - 1) * $one);
        $start = $time - ($day * $one);
        $where['time'] = array('gt', $start);
        $where['time'] = array('lt', $end);
        $all = $this->table('trans')->field($field)->where($where)->master($master)->select();
        foreach ($all as $c) {
            $money += $c['money'];
        }
        return $money;
    }

    public function getTransCount($condition)
    {
        return $this->table('trans')->where($condition)->count();
    }

    //明细
    public function getmingxi($loginid, $day = 1, $type = "电子币")
    {
        $money = 0;
        $where['uid'] = $loginid;
        $where['money_type'] = $type;
        $time = strtotime(date('Y-m-d'));
        $one = 24 * 60 * 60;
        //$end=$time-(($day-1)*$one);
        $start = $time - ($day * $one);
        $where['time'] = array('gt', $start);
        $where['time'] = array('lt', $time);
        $all = $this->table('trans')->field($field)->where($where)->master($master)->select();
        foreach ($all as $c) {
            $money += $c['money'];
        }
        return $money;
    }

    public function month($type = "电子币")
    {
        $startY = strtotime(date('Y'));
        $endN = time();
        $whcar['time'] = array('gt', $startY);
        $whcar['time'] = array('lt', $endN);
        $whcar['money_type'] = $type;
        //$userccc=$this->where($whcar)->field(" sum(`money`) as p,time")->group("FROM_UNIXTIME(time,'%Y-%m')")->select();
        $userccc = $this->table('trans')->field(" sum(`money`) as p,time")->where($where)->group("FROM_UNIXTIME(time,'%Y-%m')")->master($master)->select();
        foreach ($userccc as &$m) {
            $array[date('m', $m['time'])] = $m;
        }
        if (empty($array['01'])) {
            $array['01']['p'] = 0;
        }
        if (empty($array['02'])) {
            $array['02']['p'] = 0;
        }
        if (empty($array['03'])) {
            $array['03']['p'] = 0;
        }
        if (empty($array['04'])) {
            $array['04']['p'] = 0;
        }
        if (empty($array['05'])) {
            $array['05']['p'] = 0;
        }
        if (empty($array['06'])) {
            $array['06']['p'] = 0;
        }
        if (empty($array['07'])) {
            $array['07']['p'] = 0;
        }
        if (empty($array['08'])) {
            $array['08']['p'] = 0;
        }
        if (empty($array['09'])) {
            $array['09']['p'] = 0;
        }
        if (empty($array['10'])) {
            $array['10']['p'] = 0;
        }
        if (empty($array['11'])) {
            $array['11']['p'] = 0;
        }
        if (empty($array['12'])) {
            $array['12']['p'] = 0;
        }
        ksort($array);
        return $array;
    }

    //获取指定类型记录
    public function GetByType($uid, $type)
    {
        $map = array(
            'uid' => $uid,
            'type' => $type,
        );
        return $this->table('trans')->field($field)->where($map)->master($master)->select();
    }

    public function getTransList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'trans';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }

    //获取全部记录
    public function GetRecord($uid)
    {
        $map = array(
            'uid' => $uid,
        );
        return $this->table('trans')->field($field)->where($map)->order('time desc')->master($master)->select();
    }

    //获取全部记录
    public function getTrans($map, $field = "trans.*,users.username", $page = null, $order = " id desc")
    {
        return $this->table('trans,users')->field($field)->join('inner')->on('users.id = trans.uid')->where($map)->page($page)->order($order)->limit($limit)->select();
    }

    //奖金明细
    public function GetRecordByTypes($uid)
    {

    }

    //获取指定时间段记录数据
    public function GetRecordByTime($uid, $fromtime, $totime)
    {
        $map = array(
            'time' => array('between', array($fromtime, $totime)),
            'uid' => $uid,
            'type' => array('like', array('推荐奖', '见点奖', '层碰奖', '量碰奖', '见点奖日收入')),
        );
        return $this->table('trans')->field($field)->where($map)->master($master)->select();
    }

    private function _condition($condition)
    {
        $condition_str = '';

        if ($condition['uid'] != '') {
            $condition_str .= " and trans.uid = '" . $condition['uid'] . "'";
        }

        if ($condition['type_like'] != '') {
            $condition_str .= " and trans.type like '%" . $condition['type_like'] . "%'";
        }

        if ($condition['time_between']) {
            $condition_str .= " and trans.time>=" . $condition['time_between'][0] . " and trans.time<=" . $condition['time_between'][1];
        }

        return $condition_str;
    }

}
