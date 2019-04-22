<?php
/**
 *奖金来源明细
 */
defined('InShopBN') or exit('Access Invalid!');

class bonuslaiyuanModel extends Model
{

    public function __construct()
    {
        parent::__construct('bonuslaiyuan');
    }

    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getBonusLaiyuanList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'bonuslaiyuan';
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
            $condition_str .= " and bonuslaiyuan.id = '" . $condition['id'] . "'";
        }
        if ($condition['uid'] != '') {
            $condition_str .= " and bonuslaiyuan.uid = '" . $condition['uid'] . "'";
        }
        if ($condition['username'] != '') {
            $condition_str .= " and bonuslaiyuan.username = '" . $condition['username'] . "'";
        }
        if ($condition['username_like']) {
            $condition_str .= " and bonuslaiyuan.username like '%" . $condition['username_like'] . "%'";
        }
        if ($condition['money_type'] != '') {
            $condition_str .= " and bonuslaiyuan.money_type = '" . $condition['money_type'] . "'";
        }
        if ($condition['type'] != '') {
            $condition_str .= " and bonuslaiyuan.type = '" . $condition['type'] . "'";
        }
        if ($condition['time_between']) {
            $condition_str .= " and bonuslaiyuan.time>=" . $condition['time_between'][0] . " and bonuslaiyuan.time<=" . $condition['time_between'][1];
        }
        return $condition_str;
    }

    //记录收入
    public function recorde($uid, $num, $money_type, $type, $intro = null, $rel_uid, $rel_username, $username)
    {
        $data = array(
            'uid' => $uid,
            'money_type' => $money_type,
            'money' => $num,
            'type' => $type,
            'time' => time(),
            'intro' => $intro,
            'rel_uid' => $rel_uid,
            'rel_username' => $rel_username,
            'username' => $username,
        );
        $insert_id = $this->table('bonuslaiyuan')->insertTran($data);
        return $insert_id;
    }

    //收入和
    public function sum($uid, $type)
    {
        return $this->table('bonuslaiyuan')->field('sum(money) as sum')->where("money_type = '$type' and uid = $uid")->find();
    }

    //删除记录
    public function delbonuslaiyuan($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('bonuslaiyuan', $where);
            return $result;
        } else {
            return false;
        }
    }
}
