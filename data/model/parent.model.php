<?php
/**
 * 用户组管理
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class parentModel extends Model
{
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getParentList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'parent';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'uid desc' : $condition['order']);
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
        $param['table'] = 'biz,users';
        $param['field'] = empty($condition['field']) ? '*' : $condition['field'];;
        $param['join_type'] = empty($condition['join_type']) ? 'left join' : $condition['join_type'];
        $param['join_on'] = array('biz.uid=users.id');
        $param['where'] = $condition_str ? $condition_str : $condition['where'];
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
            $condition_str .= " and parent.id = '" . $condition['id'] . "'";
        }
        if ($condition['uid'] != '') {
            $condition_str .= " and parent.uid = '" . $condition['uid'] . "'";
        }
        if ($condition['parent'] != '') {
            $condition_str .= " and parent.parent like '%," . $condition['parent'] . ",%'";
        }
        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneBiz($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'parent';
            $param['field'] = 'id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }


    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneBizByUsername($username)
    {
        if ($username) {
            $param = array();
            $param['table'] = 'parent';
            $param['field'] = 'username';
            $param['value'] = $username;
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneBizByUid($uid)
    {
        if (intval($uid) > 0) {
            $param = array();
            $param['table'] = 'parent';
            $param['field'] = 'uid';
            $param['value'] = intval($uid);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 编辑服务站
     * @param array $condition
     * @param array $data
     */
    public function editBiz($condition, $data)
    {
        $update = $this->table('parent')->where($condition)->update($data);
        return $update;
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
            $result = Db::insert('parent', $tmp);
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
            $result = Db::update('parent', $tmp, $where);
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
            $result = Db::delete('parent', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取得用户组
     * @param unknown $condition
     */
    public function getCount($condition = array())
    {
        return $this->table('parent')->where($condition)->count();
    }
}
