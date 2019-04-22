<?php
/**
 * 
*
 */
defined('InShopBN') or exit('Access Invalid!');
class baoModel extends Model {

    public function __construct(){
        parent::__construct('bao');
    }

    public function balance($id){
		$map=array(
			'id'=>$id,
			);
		//return $this->table('bao')->field($field)->where($condition)->master($master)->find();
	}
	//增加报单币
	public function add_balance($id,$uid,$num,$type,$intro='',$puname='',$pnum='',$mt = 'bao'){
		$trans = Model('trans');
		$users = Model("member");
		$message= Model('Message');
		
		$mtype = array('bao'=>'报单币','dian'=>'电子币','xu'=>'电子股','sheng'=>'升级币','zhang'=>'生活币','yun'=>'云货币','zhu'=>'消费币');
		
		$map=array(
			'id'=>$uid,
			);
		
			 
		$res = $users->editMember($map,array($mt . '_balance'=>array('exp',$mt . '_balance+' . $num)));
		if(!$res)  return false;
		
		$TransId = $trans->recorde($uid,$num,$mtype[$mt],$type,$intro);
		
		if($TransId){
			if(!$puname){
				$message->SendJ($uid,$type,$mtype[$mt] . '增加'.$num,$intro);
			}else{
				$message->SendJ($uid,$type,$puname.'('.$pnum.')'.'给你转账,' . $mtype[$mt] . '增加'.$num,$intro);
			}
			
		}
		else{
			return false;
		}
		
		return $TransId;
	}
	//减少报单币
	public function reduce_balance($id,$uid,$num,$type,$intro='',$mt = 'bao'){
		$trans = Model('trans');
		$users = Model("member");
		$message= Model('Message');
		
		$mtype = array('bao'=>'报单币','dian'=>'电子币','xu'=>'电子股','sheng'=>'升级币','zhang'=>'生活币','yun'=>'云货币','zhu'=>'消费币');
		
		$map=array(
			'id'=>$uid,
			);
		$UserInfo = $users->getMemberInfoByID($uid);
		$balance = $UserInfo[$mt . '_balance'] + 0;
		
		if($num>$balance){
			return false;
		}
			
		$data = array();
		$data[$mt . '_balance'] = array('exp',$mt . "_balance - $num");
		
		$res = $users->editMember($map , $data);
		if(!$res){
			return false;
		}
		$num='-'.$num;
		$TransId = $trans->recorde($uid,$num,$mtype[$mt],$type,$intro);
		
		if($TransId){
			if(!$puname){
				$message->SendJ($uid,$type,$mtype[$mt] . '减少'.(0-$num),$intro);
			}else{
				$message->SendJ($uid,$type,'你给' . $puname.'('.$pnum.')'.'转账,' . $mtype[$mt] . '减少'.(0-$num),$intro);
			}
			
		}
		else{
			return false;
		}
		
		return $TransId;
	}
	
	//添加用户点报单币行
	public function add_user_bao($uid){
		$data=array(
			'uid'=>$uid,
			);	
		if($this->create($data)){
			$id=$this->add();
			return $id;
		}else{
			return false;
		}
		
		
	}
	//删除报单币行
	public function del_user_bao($id){
		$map=array(
			'id'=>$id,
			);
		$this->where($map)->delete();
		return true;
	}
	
	//颁奖
	public function Jiang() {
		$jiang = Model("jiang")->where(array('status'=>0))->limit(" limit 1")->find();
		
		print_r($jiang);
		
		try {
		    $this->beginTransaction();
		
			
			$data = array();
			$data['us'] = 'a';
			
			$insert_id	= $this->table('aaa')->insert($data);
			
			echo $insert_id;
			
			if (!$insert_id) {
				throw new Exception();
			}
			
			throw new Exception();
			
			$this->commit();		
		} catch (Exception $e) {
			$this->rollback();
		     return $res;
		}
		
		return $res;
    }
	
	public function XuFei($userid){
	    $res = array('status'=>0,'msg' => "");
		
		$day = C('con_xufei_day');
		$jine = C('con_xufei_jine');
		
		$users = Model("member");
		$UserInfo = $users->getMemberInfoByID($userid);
		
		if($UserInfo['bao_balance'] - $jine < 0){
		    $res['msg'] = "报单币金额不足";
			return $res;
		}
		
		try {
		    $this->beginTransaction();
			
			$data = array();
			$data['bao_balance'] = array('exp','bao_balance-' . $jine);
			$data['overdue_time'] = array('exp','overdue_time+' . $day*86400);
			
			$map = array('id'=>$userid);
			$r = $users->editMember($map,$data);
			
			if (!$r) {
				$res['msg'] = "扣款失败";
				throw new Exception();
			}
			
			$old_amount = $UserInfo['bao_balance'];
			$new_amount = $old_amount - $jine;
			
			$data = array(
				'uid'=>$userid,
				'money_type'=>'报单币',
				'money'=>(0-$jine),
				'type'=>'续费扣除',
				'time'=>time(),
				'intro'=>'报单币减少' . $jine ,
				'cod'=>'bao',
				'old_amount'=>$old_amount,
				'new_amount'=>$new_amount
			);
			
			$re  = $this->table('trans')->insert($data);
			if(!$re){
				$res['msg'] = '记录失败';
				throw new Exception();
			}
			
			$data = array(
			    'formid'=>'A',
				'formname'=>'系统提示',
				'totype'=>1,
				'toid'=>$userid,
				'title'=>'续费成功',
				'addtime'=>time(),
				'content'=>'续费成功，报单币扣除' . $jine,
			);
			$this->table('message')->insert($data);
			$this->commit();		
			$res['status'] = 1;
		} catch (Exception $e) {
			$this->rollback();
		     return $res;
		}
		
		return $res;
	}
	
}
