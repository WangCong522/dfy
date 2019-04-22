<?php
	//列表
	date_default_timezone_set('Asia/Shanghai');
	$uid = empty($_REQUEST['id'])?0:$_REQUEST['id'] + 0;
	$act = empty($_REQUEST['act'])?"":"bu";
	$res = array('status'=>1);
	
	if(!$uid){
	    $res['status'] = 0;
		$res['msg'] = "参数有误";
		
		exit(json_encode($res));
	}
	
	$begin = strtotime("2016-08-24");
	$time = empty($_REQUEST['t'])?0:$_REQUEST['t'] + 0;
	
	if(!$time){
	    $res['status'] = 0;
		$res['msg'] = "参数有误";
		
		exit(json_encode($res));
	}
	
	$res['date'] = date("Y-m-d",$time);
	
	$time = date("Y-m-d",$time);
	$time = strtotime($time);
	
	if($time<$begin){
	    $res['status'] = 0;
		$res['msg'] = "只能查询 " . date("Y-m-d",$begin) . " 之后的";
		
		exit(json_encode($res));
	}
	
	if($time>time()){
	    $res['status'] = 0;
		$res['msg'] = "日期有误";
		
		exit(json_encode($res));
	}
	
	$prix = "user_";
	include_once("util.php");
	
	$yd = $sd = array('tui'=>0,'ceng'=>0,'jian'=>0);
	
	$sql = "select * from user_users where id='$uid'";
	$UserInfo = getRow($sql);
	if(!$UserInfo){
		$res['status'] = 0;
		$res['msg'] = "此用户不存在";
		
		mysql_close($dbh);
		exit(json_encode($res));
	}
	
	$sql = "select * from user_net where uid='$uid'";
	$net = getRow($sql);
	$net_id = $net['id'];
	
	$l_id = $net['l_id'] + 0;
	$r_id = $net['r_id'] + 0;
	
	$sql = "select count(*) as c from user_record where tjr_id='$uid' and jh_time>=$time and jh_time<" . ($time+86400);
	$yd['tui'] = getOne($sql) + 0;
	
	$sql = "select count(*) as c from user_trans where uid='$uid' and type like '%推荐奖%' and time>=$time and time<" . ($time+86400);
	$sd['tui'] = getOne($sql) + 0;
	
	if($l_id){
		$sql = "select n.uid,u.login_time from user_net as n left join user_users as u on u.id=n.uid where n.id='$l_id'";
		$row = getRow($sql);
		$l_id = $row['uid']+ 0;
		$l_time = strtotime(date("Y-m-d",$row['login_time'] + 0));
	}
	if($r_id){
		$sql = "select n.uid,u.login_time from user_net as n left join user_users as u on u.id=n.uid where n.id='$r_id'";
		$row = getRow($sql);
		$r_id = $row['uid']+ 0;
		$r_time = strtotime(date("Y-m-d",$row['login_time'] + 0));
	}
	
	if($l_time == $time || $r_time == $time){
	    $yd['ceng'] ++;
	}
	
	$sql = "select length(p.parent)-length(replace(p.parent,',',''))-1 as lay,p.parent from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$l_id,%' and u.status=1 and u.login_time<$time order by lay desc limit 1";
	$row = getRow($sql);
	$arr = explode(",",trim($row['parent'],','));
	$k = array_search($uid,$arr);
	$p_l_lay = $row['lay'] - $k;
	
	$sql = "select length(p.parent)-length(replace(p.parent,',',''))-1 as lay,p.parent from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$r_id,%' and u.status=1 and u.login_time<$time order by lay desc limit 1";
	$row = getRow($sql);
	$arr = explode(",",trim($row['parent'],','));
	$k1 = array_search($uid,$arr);
	$p_r_lay = $row['lay'] - $k1;
	
	$sql = "select length(p.parent)-length(replace(p.parent,',',''))-1 as lay,p.parent from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$l_id,%' and u.status=1 and  u.login_time<" . ($time+86400) . " order by lay desc limit 1";
	$row = getRow($sql);
	$arr = explode(",",trim($row['parent'],','));
	$k2 = array_search($uid,$arr);
	$n_l_lay = $row['lay'] - $k2;
	
	$kk = $k1==0?$k2:$k1;
	
	$sql = "select length(p.parent)-length(replace(p.parent,',',''))-1 as lay,p.parent from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$r_id,%' and u.status=1 and u.login_time<" . ($time+86400) . " order by lay desc limit 1";
	$row = getRow($sql);
	$arr = explode(",",trim($row['parent'],','));
	$k = array_search($uid,$arr);
	$n_r_lay = $row['lay'] - $k;
	
	$p_lay = $p_l_lay>$p_r_lay?$p_r_lay:$p_l_lay;
	$n_lay = $n_l_lay>$n_r_lay?$n_r_lay:$n_l_lay;
	
	if($p_lay <=5){
		$ceng = $n_lay - $p_lay;
		if($p_lay > 5) $ceng -= $n_lay - 5;
		$yd['ceng'] += $ceng;
		
		$sql = "select count(*) as c from user_trans where time>=$time and time<" . ($time+86400) . " and type like '%层碰奖%' and uid=$uid";
		$sd['ceng'] = getOne($sql) + 0;
	}
	
	 $sql = "select count(*) as c from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$l_id,%' and u.status=1 and u.login_time<" . $time . " and length(p.parent)-length(replace(p.parent,',',''))-1>$kk+5";
	$p_l_count = getOne($sql);
	
	 $sql = "select count(*) as c from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$r_id,%' and u.status=1 and u.login_time<" . $time . " and length(p.parent)-length(replace(p.parent,',',''))-1>$kk+5";
	$p_r_count = getOne($sql);
	
	$sql = "select count(*) as c from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$l_id,%' and u.status=1 and u.login_time<" . ($time+86400) . " and length(p.parent)-length(replace(p.parent,',',''))-1>$kk+5";
	$n_l_count = getOne($sql);
	
	 $sql = "select count(*) as c from user_parent as p left join user_users as u on u.id=p.uid where p.parent like '%,$uid,$r_id,%' and u.status=1 and u.login_time<" . ($time+86400) . " and length(p.parent)-length(replace(p.parent,',',''))-1>$kk+5";
	$n_r_count = getOne($sql);
	
	$p_count = $p_l_count>$p_r_count?$p_r_count:$p_l_count;
	$n_count = $n_l_count>$n_r_count?$n_r_count:$n_l_count;
	
	$yd['jian'] += $n_count - $p_count;
	
	$sql = "select count(*) as c from user_trans where time>=$time and time<" . ($time+86400) . " and type like '%见点奖%' and uid=$uid";
	$sd['jian'] = getOne($sql) + 0;
	
	$sql = "select * from user_users where id='$uid'";
	$user = getRow($sql);
		
	$sql = "select * from user_day where time=$time and uid=$uid";
	$row = getRow($sql);
	if(!$row){
	    $maxs = array('low'=>500,'middle'=>3000,'high'=>30000);
		$max = $maxs[$user['state']];
		$balance = 0;
		
		$sql = "insert into user_day set time='$time',uid='$uid',max='$max',balance='$balance'";
		query($sql);
	}
	else{
		$max = $row['max'] + 0;
		$balance = $row['balance'] + 0;
	}
	
	$bu = false;
	$res = array('yd'=>$yd,'sd'=>$sd,'ma'=>$max,'balance'=>$balance,'bu'=>$bu,'status'=>1,'date'=>date("Y-m-d",$time));
	
	if($max <= $balance){
		exit(json_encode($res));
	}
	
	if($yd['tui'] - $sd['tui'] > 0 || $yd['ceng'] - $sd['ceng'] > 0 || $yd['jian'] - $sd['jian'] > 0) $bu = true;
	
	if($act == 'bu' || true){
		$old_amount = $user['dian_balance'];
			
		$all = 0;
		$n = $yd['tui'] - $sd['tui'];
		if($n > 0){
			$bu = true;
			for($j=0;$j<$n;$j++){
				$amount = 100;
				if($balance + $amount > $max){
					$amount = $max - $balance;
				}
				$balance += $amount;
				$all += $amount;
				
				$new_amount = $old_amount + $amount;
				
				$sql = "update user_users set dian_balance=dian_balance+$amount where id='$uid'";
				if($amount > 0) query($sql);
		
				$sql = "insert into " . $prix . "trans set uid='$uid',money_type='电子币',money='$amount',type='补偿推荐奖',time='" . $time . "',intro='补偿推荐奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
				query($sql);
				
				$sql = "update user_day set balance=balance+$amount where uid='$uid' and time='$time'";
				if($amount > 0) query($sql);
				
				$old_amount = $new_amount;
			}
		}
		
		$n = $yd['ceng'] - $sd['ceng'];
		if($n > 0){
			$bu = true;
			for($j=0;$j<$n;$j++){
				$amount = 300;
				if($balance + $amount > $max){
					$amount = $max - $balance;
				}
				$balance += $amount;
				$all += $amount;
				
				$new_amount = $old_amount + $amount;
				
				$sql = "update user_users set dian_balance=dian_balance+$amount where id='$uid'";
				if($amount > 0) query($sql);
				
				$sql = "insert into " . $prix . "trans set uid='$uid',money_type='电子币',money='$amount',type='补偿层碰奖',time='" . $time . "',intro='补偿层碰奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
				query($sql);
				
				$sql = "update user_day set balance=balance+$amount where uid='$uid' and time='$time'";
				if($amount > 0) query($sql);
				
				$old_amount = $new_amount;
				
			}
		}
		
		$n = $yd['jian'] - $sd['jian'];
		if($n > 0){
			$bu = true;
			for($j=0;$j<$n;$j++){
				$amount = 50;
				if($max == 30000) $amount = 25;
				if($balance + $amount > $max){
					$amount = $max - $balance;
				}
				$balance += $amount;
				$all += $amount;
				
				$new_amount = $old_amount + $amount;
				
				$sql = "update user_users set dian_balance=dian_balance+$amount where id='$uid'";
				if($amount > 0) query($sql);
				
				$sql = "insert into " . $prix . "trans set uid='$uid',money_type='电子币',money='$amount',type='补偿见点奖',time='" . $time . "',intro='补偿见点奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
				query($sql);
				
				$sql = "update user_day set balance=balance+$amount where uid='$uid' and time='$time'";
				if($amount > 0) query($sql);
				
				$old_amount = $new_amount;
			}
		}
		
		//
	}
	
	$res['bu']=$bu;
	$res['ma']=$max;
	$res['balance']=$balance;
	$res['date']=date("Y-m-d",$time);
	
	echo json_encode($res);
	mysql_close($dbh);
 ?>