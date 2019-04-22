<?php
/**
 * 提现设置
 */
defined('InShopBN') or exit('Access Invalid!');

class txsetModel extends Model
{

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOne($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'withdraw_set';
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
            $result = Db::update('tx_set', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }
    public function updateData($updateData,$id){
        $result = Db::update('withdraw_set',$updateData," id = '" . $id . "'");
        return $result;
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

}
