<?php
/**
 * 用户组管理
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class starModel extends Model
{
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getStarList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'star';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id asc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }
    /**
     * 星级详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getStarInfo($condition, $field = '*', $master = false)
    {
        return $this->table('star')->field($field)->where($condition)->master($master)->find();
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

        if ($condition['id'] != '') {
            $condition_str .= " and star.id = '" . $condition['id'] . "'";
        }
        if ($condition['name'] != '') {
            $condition_str .= " and star.name = '" . $condition['name'] . "'";
        }
        if ($condition['status'] != '') {
            $condition_str .= " and star.status = '" . $condition['status'] . "'";
        }
        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneStar($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'star';
            $param['field'] = 'id';
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
    public function getOneStarReField($id, $field)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'star';
            $param['field'] = 'id';
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
            $result = Db::insert('star', $tmp);
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
            $where = " id = '" . $param['id'] . "'";
            $result = Db::update('star', $tmp, $where);
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
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('star', $where);
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
        return $this->table('star')->where($condition)->select();
    }
    /**
     * 取得所有数据并排序
     * @param unknown $condition
     */
    public function getAllByOrder($condition = array())
    {
        $param['table'] = 'star';
        $param['where'] = $condition;
        $param['order'] = empty($condition['order']) ? 'level asc' : $condition['order'];
        return Db::select($param);
    }

    /**
     * 取得用户组
     * @param unknown $condition
     */
    public function getCount($condition = array())
    {
        return $this->table('star')->where($condition)->count();
    }
}
