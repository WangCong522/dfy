<?php
/**
 * 模型
 */
defined('InShopBN') or exit('Access Invalid!');

class layerlistModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getLayerlist($condition, $field = '*', $master = false)
    {
        return $this->table('layerlist')->field($field)->where($condition)->master($master)->find();
    }


    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function get_lp_Lay_list_find($condition, $field = '*', $master = false)
    {
        return $this->table('lp_laylist')->field($field)->where($condition)->master($master)->find();
    }


    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getLp_Layerlist($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('lp_laylist')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    public function get_Lp_lay_List($condition, $page = '')
    {
        $condition_str = $this->getCondition($condition);
        $param = array();
        $param['table'] = 'lp_laylist';
        $param['field'] = $condition['field'];
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $param['group'] = $condition['group'];
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 取得详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $message_id
     * @param string $field 需要取得的缓存键值, 例如：'*','message_name,message_sex'
     * @return array
     */
    public function getLaylistById($message_id, $fields = '*')
    {
        $message_info = $this->getLay(array('id' => $message_id), '*', true);
        return $message_info;
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getLayList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('layerlist')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    public function getList($condition, $page = '')
    {
        $condition_str = $this->getCondition($condition);
        $param = array();
        $param['table'] = 'layerlist';
        $param['field'] = $condition['field'];
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'id desc' : $condition['order']);
        $param['group'] = $condition['group'];
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
            $result = Db::delete('layerlist', $where);
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
    public function getLayCount($condition)
    {
        return $this->table('layerlist')->where($condition)->count();
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */
    public function editLayerlist($condition, $data)
    {
        $update = $this->table('layerlist')->where($condition)->update($data);
        return $update;
    }

    /**
     * 编辑量碰树编辑
     * @param array $condition
     * @param array $data
     */
    public function edit_lp_Layerlist($condition, $data)
    {
        $update = $this->table('lp_laylist')->where($condition)->update($data);
        return $update;
    }

    /**
     *
     * @param   array $param 信息
     * @return  array 数组格式的返回结果
     */
    public function addMessage($param)
    {
        if (empty($param)) {
            return false;
        }

        $insert_id = $this->table('layerlist')->insert($param);

        return $insert_id;
    }

    private function getCondition($conditon_array)
    {
        $condition_sql = '';
        if ($conditon_array['id'] != '') {
            $condition_sql .= " and id= '" . intval($conditon_array['id']) . "'";
        }
        if ($conditon_array['status'] !== '') {
            $condition_sql .= " and status= '" . intval($conditon_array['status']) . "'";
        }
        if ($conditon_array['uid'] != '') {
            $condition_sql .= " and uid= '" . intval($conditon_array['uid']) . "'";
        }
        return $condition_sql;
    }
}
