<?php
/**
 * 模型
 * *
 
*
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class dayModel extends Model {

    public function __construct() {
        parent::__construct('day');
    }
	
	public function getDayInfo($condition, $field = '*', $master = false) {
		return $this->table('day')->field($field)->where($condition)->master($master)->find();
    }
	
	 public function addDay($param) {
        if(empty($param)) {
            return false;
        }
		$insert_id  = $this->table('day')->insert($param);
		return $insert_id;
	 }
	 
	 public function editDay($condition, $data) {
        $update = $this->table('day')->where($condition)->update($data);
        return $update;
    }

    public function AddRecord($uid,$level){
			$y = date("Y");
			$m = date("m");
			$d = date("d");
			$fromtime= mktime(0,0,0,$m,$d,$y);
		$map=array(
			'uid'=>$uid,
			'max'=>$level,
			'time'=>$fromtime,
			);
		$re=$this->getDayInfo($map);
		if($re){
			return $re['id'];
		}else{
			return $this->addDay($map);
		}
	}
	//记录数据 用户id 级别 金额
	public function AddData($uid,$level,$jine){
		//text('日收入传金额day1'.$jine);
		$id=$this->AddRecord($uid,$level);
		$info=$this->getDayInfo(array('id'=>$id));
		if($info['balance']>=$level){
			return -1;
		}elseif(($info['balance']+$jine)>$level){
			$this->editDay(array('id'=>$id),array('balance'=>$level));
			return ($info['balance']+$jine)-$level;
		}elseif(($info['balance']+$jine)==$level){
			$this->editDay(array('id'=>$id),array('balance'=>$level));
			return 1;
		}
		if($this->editDay(array('id'=>$id),array('balance'=>array('exp'=>'balance+' . $jine)))){
			return 1;
		}
		return -1;
	}
	//统计天数
	public function CountNum($uid,$max,$fromtime,$totime){
		$map=array(
			'uid'=>$uid,
			'max'=>$max,
			'balance'=>$max,
			'time'=>array('between',array($fromtime,$totime)),
			);
		return $this->getDayInfo($map);
	}

}
