<?php
/**
 * record模型
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class recordModel extends Model
{

    public function __construct()
    {
        parent::__construct('record');
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getRecordInfo($condition, $field = '*', $master = false)
    {
        return $this->table('record')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $record_id
     * @param string $field 需要取得的缓存键值, 例如：'*','record_name,record_sex'
     * @return array
     */
    public function getRecordByID($record_id, $fields = '*')
    {
        $record_info = $this->getRecordInfo(array('id' => $record_id), '*', true);
        return $record_info;
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getRecordList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('record')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getRecList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'record';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }


    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('record', $where);
            return $result;
        } else {
            return false;
        }
    }

    public function del_record_new_id($id)
    {
        if (intval($id) > 0) {
            $where = " new_id = '" . intval($id) . "'";
            $result = Db::delete('record', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 数量
     * @param array $condition
     * @return int
     */
    public function getRecordCount($condition)
    {
        return $this->table('record')->where($condition)->count();
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */
    public function editRecord($condition, $data)
    {
        $update = $this->table('record')->where($condition)->update($data);
        return $update;
    }

    public function addRecord($data)
    {
        $update = $this->table('record')->insert($data);
        return $update;
    }

    /**
     * 获取信息
     *
     * @param    array $param 条件
     * @param    string $field 显示字段
     * @return    array 数组格式的返回结果
     */
    public function infoRecord($param, $field = '*')
    {
        if (empty($param)) return false;

        //得到条件语句
        $condition_str = $this->getCondition($param);
        $param = array();
        $param['table'] = 'record';
        $param['where'] = $condition_str;
        $param['field'] = $field;
        $param['limit'] = 1;
        $record_list = Db::select($param);
        $record_info = $record_list[0];

        return $record_info;
    }

    public function get_info_zc($id)
    {
        return $this->getRecordInfo(array('zc_id' => $id));
    }

    private function _condition($condition)
    {
        $condition_str = '';

        if ($condition['zc_id'] != '') {
            $condition_str .= " and zc_id = '" . $condition['zc_id'] . "'";
        }

        if ($condition['status']) {
            $condition_str .= " and status = '" . ($condition['status'] - 1) . "'";
        }

        return $condition_str;
    }

    /**
     * 取单个内容返回字段
     *
     * @param int $uid ID
     * @return array 数组类型的返回结果
     */
    public function getOneRecordReField($uid, $field)
    {
        if (intval($uid) > 0) {
            $param = array();
            $param['table'] = 'record';
            $param['field'] = 'uid';
            $param['value'] = intval($uid);
            $result = Db::getRow($param);
            return $result[$field];
        } else {
            return false;
        }
    }

}
