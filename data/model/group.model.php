<?php
/**
 * 用户组管理
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class groupModel extends Model
{
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getGroupList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'group';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'group_id asc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 连接查询列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getJoinList($condition, $page = '')
    {
        $result = array();
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'group,users';
        $param['field'] = empty($condition['field']) ? '*' : $condition['field'];
        $param['join_type'] = empty($condition['join_type']) ? 'left join' : $condition['join_type'];
        $param['join_on'] = array('group.group_id=users.group_id');
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order']) ? 'users.id' : $condition['order'];
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

        if ($condition['group_id'] != '') {
            $condition_str .= " and group.group_id = '" . $condition['group_id'] . "'";
        }
        if ($condition['name'] != '') {
            $condition_str .= " and group.name = '" . $condition['name'] . "'";
        }
        if ($condition['g_status'] != '') {
            $condition_str .= " and group.status = '" . $condition['g_status'] . "'";
        }
        if ($condition['u_status'] == 'no') {
            $condition_str .= " and users.status = 0";
        } elseif ($condition['u_status'] == 'yes') {
            $condition_str .= " and users.status = 1";
        }
        if ($condition['u_ssid'] != '') {
            $condition_str .= " and users.ssid = '" . $condition['u_ssid'] . "'";
        }
        if ($condition['u_ssuid'] != '') {
            $condition_str .= " and users.ssuid = '" . $condition['u_ssuid'] . "'";
        }
        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneGroup($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'group';
            $param['field'] = 'group_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }


    /**
     * 取单个内容返回字段
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneGroupReField($id, $field)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'group';
            $param['field'] = 'group_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result[$field];
        } else {
            return false;
        }
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
            $result = Db::insert('group', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updates($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $where = " group_id = '" . $param['group_id'] . "'";
            $result = Db::update('group', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " group_id = '" . intval($id) . "'";
            $result = Db::delete('group', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取得所有数据
     * @param unknown $condition
     */
    public function getAll($condition = array())
    {
        return $this->table('group')->where($condition)->select();
    }

    /**
     * 取得用户组
     * @param unknown $condition
     */
    public function getCount($condition = array())
    {
        return $this->table('group')->where($condition)->count();
    }

}
