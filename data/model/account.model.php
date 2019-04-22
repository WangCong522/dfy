<?php
/**
 * 管理
 */
defined('InShopBN') or exit('Access Invalid!');
class AccountModel extends Model{
    public function __construct() {
        parent::__construct('account');
    }
	
	 public function getAccountInfo($condition, $field = '*', $master = false) {
		return $this->table('account')->field($field)->where($condition)->master($master)->find();
    }
	
	public function getAccountById($member_id, $fields = '*') {
        $member_info = $this->getAccountInfo(array('id'=>$member_id),'*',true);
        return $member_info;
    }

}