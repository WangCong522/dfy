<?php
/**
 * 模型
*
 */
defined('InShopBN') or exit('Access Invalid!');
class messageModel extends Model {

    public function __construct(){
        parent::__construct('message');
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMessage($condition, $field = '*', $master = false) {
		return $this->table('message')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $message_id
     * @param string $field 需要取得的缓存键值, 例如：'*','message_name,message_sex'
     * @return array
     */
    public function getMessageById($message_id, $fields = '*') {
        $message_info = $this->getMessage(array('id'=>$message_id),'*',true);
        return $message_info;
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMessageList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '') {
       return $this->table('message')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
	
	public function getMsgList($condition,$page=''){
        $condition_str = $this->getCondition($condition);
        $param = array();
        $param['table'] = 'message';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order'])?'id desc':$condition['order']);
        $result = Db::select($param,$page);
        return $result;
    }
	
	public function SendJ($uid,$type,$msg,$title){
	    $user = Model("member")->getMemberInfoByID($uid);
		if(empty($user)) return false;
		
		$data = array('formid'=>'A','formname'=>'系统提示','totype'=>'1','toid'=>$uid,'title'=>$type,'content'=>$msg,'addtime'=>time());
		$this->addMessage($data);
	}
	
	/**
	 * 删除
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('message',$where);
			return $result;
		}else {
			return false;
		}
	}

    /**
     * 数量
     * @param array $condition
     * @return int
     */
    public function getMessageCount($condition) {
        return $this->table('message')->where($condition)->count();
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */
    public function editMessage($condition, $data) {
        $update = $this->table('message')->where($condition)->update($data);
        return $update;
    }

    
    /**
     *
     * @param   array $param 信息
     * @return  array 数组格式的返回结果
     */
    public function addMessage($param) {
        if(empty($param)) {
            return false;
        }
		
		$insert_id  = $this->table('message')->insert($param);
	   
		return $insert_id;
    }

   private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['id'] != '') {
			$condition_sql	.= " and id= '" .intval($conditon_array['id']). "'";
		}
		
		if($conditon_array['no_id'] != '') {
			$condition_sql	.= " and id<> '" .intval($conditon_array['no_id']). "'";
		}
		
		if($conditon_array['toid'] != '') {
			$condition_sql	.= " and toid= '" .intval($conditon_array['toid']). "'";
		}
		if($conditon_array['fromid'] != '') {
			$condition_sql	.= " and formid= '" .intval($conditon_array['fromid']). "'";
		}
		
		if($conditon_array['username'] != '') {
			$condition_sql	.= " and username='".$conditon_array['username']."'";
		}
		
		return $condition_sql;
	}
}
