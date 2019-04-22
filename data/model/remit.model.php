<?php
/**
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class remitModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //增加
    public function AddRecord($data)
    {
        return $this->table("remit")->insert($data);
    }

    public function getRemitInfo($condition, $field = '*', $master = false)
    {
        return $this->table('remit')->field($field)->where($condition)->master($master)->find();
    }

    public function getRemitInfoByID($Remit_id, $fields = '*')
    {
        $Remit_info = $this->getRemitInfo(array('id' => $Remit_id), '*', true);
        return $Remit_info;
    }

    public function getRemitList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('remit')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    public function editRemit($condition, $data)
    {
        $update = $this->table('remit')->where($condition)->update($data);
        return $update;
    }

    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " id = '" . intval($id) . "'";
            $result = Db::delete('remit', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getRemitListJs($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'remit';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
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
        if ($condition['uid'] != '') {
            $condition_str .= " and remit.uid = '" . $condition['uid'] . "'";
        }
        if ($condition['optype'] != '') {
            $condition_str .= " and remit.optype = '" . $condition['optype'] . "'";
        }
        if ($condition['status'] != '') {
            $condition_str .= " and remit.status = '" . $condition['status'] . "'";
        }
        return $condition_str;
    }

    /**
     * 取得总数量
     * @param unknown $condition
     */
    public function getRemitCount($condition) {
        return $this->table('remit')->where($condition)->count();
    }


}
