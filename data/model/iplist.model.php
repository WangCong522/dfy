<?php
/**
 * NET模型
 */
defined('InShopBN') or exit('Access Invalid!');
class iplistModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getIpInfo($condition, $field = '*', $master = false) {
		return $this->table('iplist')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得详细信息（优先查询缓存）
     * @return array
     */
    public function getIpById($ip_id, $fields = '*') {
        $Ip_info = $this->getIpInfo(array('id'=>$ip_id),'*',true);
        return $Ip_info;
    }
	
	 public function getIpByUser($ip_id, $fields = '*') {
        $Ip_info = $this->getIpInfo(array('uid'=>$ip_id),'*',true);
        return $Ip_info;
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getIpList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '') {
       return $this->table('iplist')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
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
			$result = Db::delete('iplist',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	public function del_by($uid,$netid){
		if (intval($uid) > 0){
			$where = " uid = '". intval($id) ."' and netid='$netid'";
			$result = Db::delete('iplist',$where);
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
    public function getIpCount($condition) {
        return $this->table('iplist')->where($condition)->count();
    }
	
	public function addIp($para) {
        $insert_id  = $this->table('iplist')->insert($para);
		return $insert_id;
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */
    public function editIp($condition, $data) {
        $update = $this->table('iplist')->where($condition)->update($data);
        return $update;
    }
	
	/**
	 * 获取信息
	 *
	 * @param	array $param 条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoIp($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'iplist';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$Net_list	= Db::select($param);
		$Ip_info	= $Net_list[0];
		
		return $Ip_info;
	}
}
