<?php
/**
 * 模型
 */
defined('InShopBN') or exit('Access Invalid!');
class layModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getLay($condition, $field = '*', $master = false) {
		return $this->table('lays')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $message_id
     * @param string $field 需要取得的缓存键值, 例如：'*','message_name,message_sex'
     * @return array
     */
    public function getLayById($message_id, $fields = '*') {
        $message_info = $this->getLay(array('id'=>$message_id),'*',true);
        return $message_info;
    }
	
	//检查层
	public function CheckLay($uid){
	    //
		$users = Model("member");
		$net = Model("net");
		$childs = $users->getChild($uid);
		$lay = 0;
		
		foreach($childs as $row){
		    $str = $row['parent'];
			$s = explode(",$uid,",$str);
			$arr = explode(",",trim($s[1],','));
			
			if(count($arr) > $lay) $lay = count($arr);
		}
		$lay ++;
		
		$lays = $this->getLayList(array('uid'=>$uid));
		
		$do = false;
		if($lay != count($lays) - 1){
			$do = true;
		}
		
		$arr = array();
		foreach($lays as $val){
		    $arr[$val['lay']] = $val;
		}
		for($i=0;$i<=$lay;$i++){
		    if(!$arr[$i]){
			    $do = true;
				break;
			}
		}
		
		return $do;
	}

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getLayList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '') {
       return $this->table('lays')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
	
	public function getList($condition,$page=''){
        $condition_str = $this->getCondition($condition);
        $param = array();
        $param['table'] = 'lays';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order'])?'id desc':$condition['order']);
        $result = Db::select($param,$page);
        return $result;
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
			$result = Db::delete('lays',$where);
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
    public function getLayCount($condition) {
        return $this->table('lays')->where($condition)->count();
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */
    public function editLay($condition, $data) {
        $update = $this->table('lays')->where($condition)->update($data);
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
		
		$insert_id  = $this->table('lays')->insert($param);
	   
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
		
		if($conditon_array['uid'] != '') {
			$condition_sql	.= " and uid= '" .intval($conditon_array['uid']). "'";
		}
		if($conditon_array['lay'] != '') {
			$condition_sql	.= " and lay= '" .intval($conditon_array['lay']). "'";
		}
		
		return $condition_sql;
	}
}
