<?php
/**
 * 文章管理
*
*
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class goodsModel extends Model {
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getGoodsList($condition,$page=''){
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'goods';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order'])?'gid desc':$condition['order']);
        $result = Db::select($param,$page);
        return $result;
    }

    /**
     * 连接查询列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getJoinList($condition,$page=''){
        $result = array();
        $condition_str  = $this->_condition($condition);
        $param  = array();
        $param['table'] = 'goods,goods_type';
        $param['field'] = empty($condition['field'])?'*':$condition['field'];;
        $param['join_type'] = empty($condition['join_type'])?'left join':$condition['join_type'];
        $param['join_on']   = array('goods.ac_id=goods_class.ac_id');
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order'])?'goods.goods_sort':$condition['order'];
        $result = Db::select($param,$page);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition){
        $condition_str = '';
		
        if ($condition['typeid'] != ''){
            $condition_str .= " and goods.typeid = '". $condition['typeid'] ."'";
        }
        if ($condition['typeids'] != ''){
            $condition_str .= " and goods.typeid in(". $condition['typeids'] .")";
        }
        if ($condition['like_goods_name'] != ''){
            $condition_str .= " and goods.goods_name like '%". $condition['like_goods_name'] ."%'";
        }
        if ($condition['home_index'] != ''){
            $condition_str .= " and (goods_type.id <= 7)";
        }

        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneGoods($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'goods';
            $param['field'] = 'gid';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 取单个字段
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneGoodsField($id,$field){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'goods';
            $param['field'] = 'gid';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result[$field];
        }else {
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = Db::insert('goods',$tmp);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updates($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " gid = '". $param['gid'] ."'";
            $result = Db::update('goods',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id){
        if (intval($id) > 0){
            $where = " gid = '". intval($id) ."'";
            $result = Db::delete('goods',$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 取得文章数量
     * @param unknown $condition
     */
    public function getCount($condition = array()) {
        return $this->table('goods')->where($condition)->count();
    }

    /**
     * 取得待领取
     * @param unknown $condition
     */
    public function getToReceivePrizeInfo($group_id,$b=null){
        if ($group_id == 0) $group_id = $b;
        return $this->table('goods')->where(array('group_id'=>$group_id))->select();
    }

    /**
     * 该会员领取过得奖品
     * @param unknown $condition
     */
    public function getBantPrizeInof($user_id){
        return $this->table('is_prize')->where(array('user_id'=>$user_id))->select();
    }



}
