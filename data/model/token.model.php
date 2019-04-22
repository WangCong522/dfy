<?php
/**
 * Token模型
*
*
 *
 */
defined('InShopBN') or exit('Access Invalid!');
class tokenModel extends Model {

    public function __construct(){
        parent::__construct('token');
    }
	
    public function getToken($condition, $field = '*', $master = false) {
		return $this->table('token')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getTokenByID($id, $fields = '*') {
        $token_info = $this->getToken(array('id'=>$id),'*',true);
        return $token_info;
    }

	
	/**
	 * 删除Token
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('token',$where);
			return $result;
		}else {
			return false;
		}
	}

    /**
     * 编辑Token
     * @param array $condition
     * @param array $data
     */
    public function editToken($condition, $data) {
        $update = $this->table('token')->where($condition)->update($data);
        return $update;
    }

    /**
     * add Token
     *
     * @param   array $param Token信息
     * @return  array 数组格式的返回结果
     */
    public function addToken($param) {
        if(empty($param)) {
            return false;
        }

		$insert_id  = $this->table('token')->insert($param);
		return $insert_id;
    }
}
