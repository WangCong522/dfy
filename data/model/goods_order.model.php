<?php
/**
 * 文章管理
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class goods_orderModel extends Model
{

    public function __construct()
    {
        parent::__construct('goods_order');
    }

    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getGoodsOrderList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'goods_order';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'order_id desc' : $condition['order']);
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
        $param['table'] = 'goods,goods_type';
        $param['field'] = empty($condition['field']) ? '*' : $condition['field'];;
        $param['join_type'] = empty($condition['join_type']) ? 'left join' : $condition['join_type'];
        $param['join_on'] = array('goods.ac_id=goods_class.ac_id');
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order']) ? 'goods.goods_sort' : $condition['order'];
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
            $condition_str .= " and goods_order.uid = '" . $condition['uid'] . "'";
        }
        if ($condition['typeids'] != '') {
            $condition_str .= " and goods_order.typeid in(" . $condition['typeids'] . ")";
        }
        if ($condition['like_goods_name'] != '') {
            $condition_str .= " and goods_order.goods_name like '%" . $condition['like_goods_name'] . "%'";
        }
        if ($condition['home_index'] != '') {
            $condition_str .= " and (goods_type.id <= 7)";
        }
        if ($condition['status'] == 'cart') {
            $condition_str .= " and goods_order.status = 0";
        } elseif ($condition['status'] == 'order') {
            $condition_str .= " and goods_order.status in(1,2)";
        }

        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneGoodsOrder($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'goods_order';
            $param['field'] = 'order_id';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
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
            $result = Db::insert('goods_order', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 新增领奖记录
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addUserPrize($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $result = Db::insert('is_prize', $tmp);
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
            $where = " order_id = '" . $param['order_id'] . "'";
            $result = Db::update('goods_order', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 购物车商品购买成功,更新全部商品
     */
    public function clear_cart($uid,$sbuy_type,$xiaofei)
    {
        return Db::query("update user_goods_order set status = 1,sbuy_type='".$sbuy_type."',xiaofei=$xiaofei where uid = $uid and status = 0");
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
            $where = " order_id = '" . intval($id) . "'";
            $result = Db::delete('goods_order', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取得文章数量
     * @param unknown $condition
     */
    public function getCount($condition = array())
    {
        return $this->table('goods_order')->where($condition)->count();
    }
}
