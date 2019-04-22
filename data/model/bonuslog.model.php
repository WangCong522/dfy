<?php
/**
 *奖金来源明细
 */
defined('InShopBN') or exit('Access Invalid!');

class bonuslogModel extends Model
{

    public function __construct()
    {
        parent::__construct('bonuslog');
    }

    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getBonusLogList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'bonuslog';
        $param['where'] = $condition_str ? $condition_str : $condition['where'];
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $param['field'] = $condition['field'];
        $param['group'] = $condition['group'];
        $param['specialkey'] = $condition['specialkey'] ? $condition['specialkey'] : '';
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition)
    {
        $condition_str = '';

        if ($condition['id'] != '') {
            $condition_str .= " and bonuslog.id = '" . $condition['id'] . "'";
        }
        if ($condition['uid'] != '') {
            $condition_str .= " and bonuslog.uid = '" . $condition['uid'] . "'";
        }
        return $condition_str;
    }

    public function getBonusLogsList($condition, $page = null, $order = 'id desc', $field = '*')
    {
        return $this->table('bonuslog')->field($field)->where($condition)->page($page)->order($order)->select();
    }

    //奖金记录
    public function log($uid, $username, $type, $money)
    {
        $bdate = $this->now();
        $have = $this->getOneByUidAndBdate($uid, $bdate);
        if ($have) {
            $update_array = array(
                'b' . $type => array('exp', "b" . $type . "+$money"),
                'b0' => array('exp', "b0+$money")
            );
            $condition = array(
                'uid' => $uid,
                'bdate' => $bdate,
            );
            $this->editBonusLog($condition, $update_array);
        } else {
            $insert_array = array(
                'b' . $type => $money,
                'b0' => $money,
                'bdate' => $bdate,
                'uid' => $uid,
                'username' => $username
            );
            $this->add($insert_array);
        }
        return true;
    }


    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
//            $result = Db::insert('bonuslog', $tmp);
              $result =  $this->table('bonuslog')->insertTran($tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editBonusLog($condition, $data)
    {
        $update = $this->where($condition)->update($data);
        return $update;
    }

    public function getOneByUidAndBdate($uid, $bdate)
    {
        $condition = array(
            'uid' => $uid,
            'bdate' => $bdate
        );
        return $this->where($condition)->find();
    }

    //奖项收入总和
    public function sum($uid, $type)
    {
        return $this->field("sum($type) as sum")->where("uid = $uid")->find();
    }

    private function now()
    {
        return date('Y-m-d', time());
    }
}
