<?php
	//分配奖金
	$prix = "user_";
	include_once("util.php");
	$msg = "";
	//$base_url = "http://localhost/ys/";
	
	try {
	    mysql_query("BEGIN");
		mysql_query("SET AUTOCOMMIT=0"); 
		
		//位于父节点的市场位置
		$wz = "";
		
		$sql = "select j.*,u.num from " . $prix . "jiang as  j left join " . $prix . "users as u on u.id=j.uid where j.status='0' order by j.id asc limit 1";
		$Jiang = getRow($sql);
		
		if(!$Jiang){
			$msg = "empty";
			throw new Exception();
		}
		
		$userid = $Jiang['uid'];
		$net_id = $Jiang['net_id'];
		$sn = $Jiang['num'];
		$Jtime = $Jiang['add_time'];
		
		$sql = "select * from " . $prix . "parent where uid='$userid'  limit 1";
		$pRow = getRow($sql);
		$parent = trim($pRow['parent'],',');
		$parent_id = $pRow['parent_id'] + 0;
		
		$sql = "select id,l_id,r_id from " . $prix . "net where uid='$parent_id'  limit 1";
		$pRow = getRow($sql);
		if($pRow['l_id'] == $net_id){
		    $wz = "L";
		}
		else if($pRow['r_id'] == $net_id){
		    $wz = "R";
		}
		else{
			$msg = "数据不完整"; //既不是左节点，又不是右节点，PID却是
			//$url = $base_url . "x_pid.php?id=$net_id";
			//$html = file_get_contents($url);
			
			throw new Exception();
		}
		
		//echo "userid : $userid , net_id : $net_id , parent_id : $parent_id , parent : $parent";
		
		//没有第0层，就加入，其实在其他程序已经加入了，在此仅仅是考虑数据完整性
		$sql = "select id from " . $prix . "lays where uid='$userid' and lay='0' limit 1";
		$r = getOne($sql);
		if(!$r){
			$sql = "insert into " . $prix . "lays set uid='$uid',lay='0',l_count='0',r_count='0'";
			query($sql);
		}
		
		if($parent_id > 0){
			//父节点如果没有第一层，就加入
			$sql = "select id from " . $prix . "lays where uid='$parent_id' and lay='1' limit 1";
			$r = getOne($sql);
			
			if(!$r){
				$l_count = $r_count = 0;
				if($wz == "R") $r_count = 1;
				else $l_count = 1;
				
				$sql = "insert into " . $prix . "lays set uid='$parent_id',lay='1',l_count='$l_count',r_count='$r_count'";
				
				$re = query($sql);
				if(!$re){
					$msg = "写入第一层失败"; //
					throw new Exception();
				}
			}
			else{
				//已有第一层了，写入左右数
				if($wz == "R") $str = "r_count=r_count+1";
				else $str = "l_count=l_count+1";
				
				$sql = "update " . $prix . "lays set $str where id='$r'";
				
				$re = query($sql);
				if(!$re){
					$msg = "更新第一层失败"; //
					throw new Exception();
				}
			}
		}
		
		$sql = "select n.*,u.state,u.bao_balance,u.dian_balance,u.num from " . $prix . "net as n left join " . $prix . "users as u on u.id=n.uid where n.uid in ($parent)";
		$list = getAll($sql);
		$nets = array();
		
		foreach($list as $val){
		    $nets[$val['uid']] = $val;
		}
		
		$arr = explode(",",$parent);
		foreach($arr as $key=>$pids){
		    //echo $val . '<br>';
			$pWz = "";
			$next_id = $arr[$key + 1] + 0;
			if($nets[$pids]['l_id'] == $nets[$next_id]['id']) $pWz = "l";
			else if($nets[$pids]['r_id'] == $nets[$next_id]['id']) $pWz = "r";
			else{
				if($key<count($arr)-1){
					$msg = "位置错"; //
					//$url = $base_url . "x_pid.php?id=" . $nets[$next_id]['id'];
					//$html = file_get_contents($url);
					
					throw new Exception();
				}
			}
			
			if($pids != $parent_id){ //第一层不用了，其他的
			    $floor = count($arr)-$key;
				$sql = "select id from " . $prix . "lays where uid='$pids' and lay='$floor'";
				$re = getOne($sql);
				if(!$re){
					$sql = "insert into " . $prix . "lays set uid='$pids',lay='$floor',l_count='0',r_count='0'";
					$re = query($sql);
					
					if(!$re){
						$msg = "加入新层失败"; //
						throw new Exception();
					}
				}
				
				$sql = "update " . $prix . "lays set " . $pWz . "_count=" . $pWz . "_count+1 where uid='$pids' and lay='$floor'";
				//echo $sql . "<br>";
				
				$re = query($sql);
				if(!$re){
					$msg = "更新层失败"; //
					throw new Exception();
				}
				
				//更新左右市场人数
				$sql = "select sum(" . $pWz . "_count) as r from " . $prix . "lays where uid='$pids' and lay>0";
				$count = getOne($sql) + 0;
				$sql = "update " . $prix . "net set " . $pWz . "_count='$count' where uid='$pids'";
				$re = query($sql);
				if(!$re){
					$msg = "更新市场人数失败"; //
					throw new Exception();
				}
				//
			}
			else{
			    //更新左右市场人数
				$pWz = strtolower($wz);
				$sql = "select sum(" . $pWz . "_count) as r from " . $prix . "lays where uid='$pids' and lay>0";
				$count = getOne($sql) + 0;
				$sql = "update " . $prix . "net set " . $pWz . "_count='$count' where uid='$pids'";
				$re = query($sql);
				if(!$re){
					$msg = "更新1层市场人数失败"; //
					throw new Exception();
				}
				
				break;
			}
		}
		
		$sql = "select * from " . $prix . "setting";
		$list = getAll($sql);
		if(!$list){
			$msg = "获取设置失败"; //
			throw new Exception();
		}
		
		$setting = array();
		foreach($list as $val){
		    $setting[trim($val['name'])] = trim($val['value']);
		}
		$sql = "select * from " . $prix . "account order by id asc limit 1";
		$Maxs = getRow($sql);
		
		$today = strtotime(date("Y-m-d 00:00:00",$Jtime));
		$days = array();
		
		//推荐人
		$sql = "select tjr_id from " . $prix . "record where new_id='$net_id'";
		$tjr_id = getOne($sql) + 0;
		
		if(!$tjr_id){
			$msg = "获取推荐人失败"; //
			throw new Exception();
		}
		
		$sql = "select * from " . $prix . "day where uid in (" . $parent . ',' . $tjr_id . ") and time='$today'";
		$list = getAll($sql);
		
		if($list){
			foreach($list as $val){
				$days[$val['uid']] = $val;
			}
		}
		
		$max = $Maxs[$nets[$tjr_id]['state']] + 0;
		$balance = 0;
		
		if(!$max){
		    $sql = "select n.*,u.state,u.bao_balance,u.dian_balance,u.num from " . $prix . "net as n left join " . $prix . "users as u on u.id=n.uid where n.uid='$tjr_id'";
			$nets[$tjr_id] = getRow($sql);
			$max = $Maxs[$nets[$tjr_id]['state']] + 0;
		}
		
		if(!$max){
		    $msg = "获取最大值失败"; //
			throw new Exception();
		}
		
		if(!$days[$tjr_id]){
		    $sql = "insert into " . $prix . "day set max='$max',balance='0',time='$today',uid='$tjr_id'";
			$re = query($sql);
			if(!$re){
				$msg = "写入当天配置失败"; //
				throw new Exception();
			}
			$balance = 0;
			$days[$tjr_id]['max'] = $max;
			$days[$tjr_id]['balance'] = 0;
		}
		else{
		    $max = $days[$tjr_id]['max'];
			$balance = $days[$tjr_id]['balance'];
		}
		
		//推荐奖
		$amount = 500*$setting['con_tui_rate'];
		$old_amount = $nets[$tjr_id]['dian_balance'];
		
		if($balance >= $max){
		    //达到封顶数了
			$amount = 0;
		}
		
		if($balance + $amount > $max) $amount = $max - $balance; //封顶
		$sql = "update " . $prix . "users set dian_balance=dian_balance+$amount where id='$tjr_id'";
		$re = query($sql);
		if(!$re){
			$msg = "加推荐奖失败"; //
			throw new Exception();
		}
		
		$new_amount = $old_amount + $amount;
		$sql = "insert into " . $prix . "trans set uid='$tjr_id',money_type='电子币',money='$amount',type='推荐奖',time='" .time() . "',intro='推荐奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
		$re = query($sql);
		if(!$re){
			$msg = "写入推荐奖明细失败"; //
			throw new Exception();
		}
		
		$sql = "update " . $prix . "day set balance=balance+$amount where time='$today' and uid='$tjr_id'";
		$re = query($sql);
		if(!$re){
			$msg = "写入今天t记录失败"; //
			throw new Exception();
		}
		$days[$tjr_id]['balance'] += $amount;
		$nets[$tjr_id]['dian_balance'] += $amount;
		
		$str = "电子币增加：$amount";
		if($amount == 0) $str = "奖金已经封顶";
		$sql = "insert into " . $prix . "message set formid='A',formname='系统提示',totype='1',toid='$tjr_id',title='推荐奖',addtime='" .time() . "',content='$str',status='1'";
		
		$re = query($sql);
		if(!$re){
			$msg = "发推荐奖信息失败"; //
			throw new Exception();
		}
		
		//开始层碰奖与见点奖
		foreach($arr as $key=>$pids){
		    $floor = count($arr) - $key;
			
			$pWz = "";
			$next_id = $arr[$key + 1] + 0;
			if($nets[$pids]['l_id'] == $nets[$next_id]['id']) $pWz = "l";
			else if($nets[$pids]['r_id'] == $nets[$next_id]['id']) $pWz = "r";
			else{
				if($key<count($arr)-1){
					$msg = "位置错了"; //
					throw new Exception();
				}
			}
			
			//5层以内，层碰
			if($floor <= 5){
			    //层碰条件
				$sql = "select * from " . $prix . "lays where lay='$floor' and uid='$pids' limit 1";
				$ceng = getRow($sql);
				if(!$ceng){
					//$msg = "获取层信息失败"; //
					//throw new Exception();
					$sql = "insert into " . $prix . "lays set lay='$floor',uid='$pids',l_count=0,r_count=0";
					$re = query($sql);
					if(!$re){
						$msg = "补充层信息失败"; //
						throw new Exception();
					}
				}
				
				$do = false;
				if($pWz == 'l'){
				    if($ceng['r_count'] >= $ceng['l_count'] && $ceng['l_count'] == 1) $do = true;
				}
				else{
					if($ceng['l_count'] >= $ceng['r_count'] && $ceng['r_count'] == 1) $do = true;
				}
				
				if(!$do) continue;
				
				$max = $Maxs[$nets[$pids]['state']] + 0;
				$balance = 0;
				
				if(!$max){
					$msg = "获取c最大值失败"; //
					throw new Exception();
				}
				
				if(!$days[$pids]){
					$sql = "insert into " . $prix . "day set max='$max',balance='0',time='$today',uid='$pids'";
					$re = query($sql);
					if(!$re){
						$msg = "写入c当天配置失败"; //
						throw new Exception();
					}
					$balance = 0;
					
					$days[$pids]['max'] = $max;
					$days[$pids]['balance'] = $balance;
				}
				else{
					$max = $days[$pids]['max'];
					$balance = $days[$pids]['balance'];
				}
				
				$amount = 500*$setting['con_ceng_rate'];//层碰金额
				$old_amount = $nets[$pids]['dian_balance'];
				
				if($balance >= $max){
					$amount = 0;
				}
				
				if($balance + $amount > $max) $amount = $max - $balance; //封顶
				$sql = "update " . $prix . "users set dian_balance=dian_balance+$amount where id='$pids'";
				$re = query($sql);
				if(!$re){
					$msg = "加层碰奖失败"; //
					throw new Exception();
				}
				
				$new_amount = $old_amount + $amount;
				$sql = "insert into " . $prix . "trans set uid='$pids',money_type='电子币',money='$amount',type='层碰奖',time='" .time() . "',intro='层碰奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
				$re = query($sql);
				if(!$re){
					$msg = "写入层碰奖明细失败"; //
					throw new Exception();
				}
				
				$sql = "update " . $prix . "day set balance=balance+$amount where time='$today' and uid='$pids'";
				$re = query($sql);
				if(!$re){
					$msg = "写入今天c记录失败"; //
					throw new Exception();
				}
				$days[$pids]['balance'] += $amount;
				$nets[$pids]['dian_balance'] += $amount;
				
				$str = "电子币增加：$amount";
				if($amount == 0) $str = "奖金已经封顶";
				$sql = "insert into " . $prix . "message set formid='A',formname='系统提示',totype='1',toid='$pids',title='层碰奖',addtime='" .time() . "',content='$str',status='1'";
				$re = query($sql);
				if(!$re){
					$msg = "发层碰奖信息失败"; //
					throw new Exception();
				}
				//层碰结束
			}
			else{
			    //见点奖
				/*
				$sql = "select * from " . $prix . "lays where lay='$floor' and uid='$pids' limit 1";
				$ceng = getRow($sql);
				if(!$ceng){
					//$msg = "获取层信息失败"; //
					//throw new Exception();
					$sql = "insert into " . $prix . "lays set lay='$floor',uid='$pids',l_count=0,r_count=0";
					$re = query($sql);
					if(!$re){
						$msg = "补充层信息失败"; //
						throw new Exception();
					}
				}
				
				//规则：新加的市场小于等于另一侧
				$do = false;
				if($pWz == 'l'){
				    if($ceng['r_count'] >= $ceng['l_count']) $do = true;
				}
				else{
					if($ceng['l_count'] >= $ceng['r_count']) $do = true;
				}
				//*////
				$sql = "select sum(l_count) as l_count,sum(r_count) as r_count  from " . $prix . "lays where uid='$pids' and lay>=6 limit 1";
				$ceng = getRow($sql);
				
				$do = false;
				
				if($pWz == 'l'){
				    if($ceng['r_count'] >= $ceng['l_count']) $do = true;
				}
				else{
					if($ceng['l_count'] >= $ceng['r_count']) $do = true;
				}
				
				if(!$do) continue;
				
				$max = $Maxs[$nets[$pids]['state']] + 0;
				$balance = 0;
				
				if(!$max){
					$msg = "获取j最大值失败"; //
					throw new Exception();
				}
				
				if(!$days[$pids]){
					$sql = "insert into " . $prix . "day set max='$max',balance='0',time='$today',uid='$pids'";
					$re = query($sql);
					if(!$re){
						$msg = "写入j当天配置失败"; //
						throw new Exception();
					}
					$balance = 0;
					
					$days[$pids]['max'] = $max;
					$days[$pids]['balance'] = $balance;
				}
				else{
					$max = $days[$pids]['max'];
					$balance = $days[$pids]['balance'];
				}
				
				$amount = 500*$setting['con_xiaoqu_rate'];//见点金额
				$old_amount = $nets[$pids]['dian_balance'];
				
				if($balance >= $max){
					$amount = 0;
				}
				
				if($max == 30000) $high = true;
				else $high = false;
				
				if($balance + $amount > $max) $amount = $max - $balance; //封顶
				$sql = "update " . $prix . "users set dian_balance=dian_balance+$amount where id='$pids'";
				if($high){
				    $dian_balance = $setting['con_30000_dian_rate']*$amount;
					$yun_balance = $setting['con_30000_yun_rate']*$amount;
					$sheng_balance = $setting['con_30000_sheng_rate']*$amount;
					
					$sql = "update " . $prix . "users set dian_balance=dian_balance+$dian_balance,yun_balance=yun_balance+$yun_balance,sheng_balance=sheng_balance+$sheng_balance where id='$pids'";
				}
				$re = query($sql);
				if(!$re){
					$msg = "加见点奖失败"; //
					throw new Exception();
				}
				
				if($high){
				    $new_amount = $old_amount + $dian_balance;
					$sql = "insert into " . $prix . "trans set uid='$pids',money_type='电子币',money='$dian_balance',type='见点奖',time='" .time() . "',intro='见点奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
					$re = query($sql);
					if(!$re){
						$msg = "写入见点奖明细失败"; //
						throw new Exception();
					}
					
					$sql = "update " . $prix . "day set balance=balance+$amount where time='$today' and uid='$pids'";
					$re = query($sql);
					if(!$re){
						$msg = "写入今天j记录失败"; //
						throw new Exception();
					}
					$days[$pids]['balance'] += $amount;
					$nets[$pids]['dian_balance'] += $dian_balance;
					
					$str = "电子币增加：$dian_balance，云货币增加：$yun_balance，生活币增加：$sheng_balance";
					if($amount == 0) $str = "奖金已经封顶";
					$sql = "insert into " . $prix . "message set formid='A',formname='系统提示',totype='1',toid='$pids',title='见点奖',addtime='" .time() . "',content='$str',status='1'";
					$re = query($sql);
					if(!$re){
						$msg = "发见点奖信息失败"; //
						throw new Exception();
					}
				}
				else{
					$new_amount = $old_amount + $amount;
					$sql = "insert into " . $prix . "trans set uid='$pids',money_type='电子币',money='$amount',type='见点奖',time='" .time() . "',intro='见点奖',old_amount='$old_amount',new_amount='$new_amount',cod='dian'";
					$re = query($sql);
					if(!$re){
						$msg = "写入见点奖明细失败"; //
						throw new Exception();
					}
					
					$sql = "update " . $prix . "day set balance=balance+$amount where time='$today' and uid='$pids'";
					$re = query($sql);
					if(!$re){
						$msg = "写入今天j记录失败"; //
						throw new Exception();
					}
					$days[$pids]['balance'] += $amount;
					$nets[$pids]['dian_balance'] += $amount;
					
					$str = "电子币增加：$amount";
					if($amount == 0) $str = "奖金已经封顶";
					$sql = "insert into " . $prix . "message set formid='A',formname='系统提示',totype='1',toid='$pids',title='见点奖',addtime='" .time() . "',content='$str',status='1'";
					$re = query($sql);
					if(!$re){
						$msg = "发见点奖信息失败"; //
						throw new Exception();
					}
				}
				
				//见点奖结束
			}
			//
		}
		
		//标记奖金已处理过
		$sql = "update " . $prix . "jiang set status=1,do_time='" . time() . "' where id='" . $Jiang['id'] . "'";
		$re = query($sql);
		if(!$re){
			$msg = "修改奖金状态失败"; //
			throw new Exception();
		}
		
		mysql_query("COMMIT");
		echo "succ";
	} catch (Exception $e) {
		mysql_query("ROLLBACK");
		echo "err : " . $msg;
		file_put_contents("err/" . $Jiang['id'] . '.txt',$msg);
	}
	
	mysql_query("SET AUTOCOMMIT=1");
	if(file_exists("err/" . $Jiang['id'] . '.txt')){
	    $sql = "update " . $prix . "jiang set status=1,err=1 where id='" . $Jiang['id'] . "'";
		query($sql);
	}
	
	//删除24小时仍未激活的会员
	
	$time = time() - 86400;
	$sql = "select u.id,n.id as net_id from " . $prix . "users as u left join " . $prix . "net as n on u.id=n.uid where u.status=0 and n.status=0 and u.time<$time order by u.id asc limit 1";
	$row = getRow($sql);
	
	if($row){
		$id = $row['id'];
		$net_id = $row['net_id'] + 0;
		try {
			mysql_query("BEGIN");
			mysql_query("SET AUTOCOMMIT=0"); 
			
			$sql = "update " . $prix . "net set l_id=0 where l_id='$net_id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "update " . $prix . "net set r_id=0 where r_id='$net_id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "net where id='$net_id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "users where id='$id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "record where new_id='$net_id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "iplist where netid='$net_id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "lays where uid='$id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			$sql = "delete from " . $prix . "parent where uid='$id'";
			$r = query($sql);
			
			if(!$r){
				throw new Exception();
			}
			
			mysql_query("COMMIT");
		} catch (Exception $e) {
			mysql_query("ROLLBACK");
		}
		
		mysql_query("SET AUTOCOMMIT=1");
	}
	
	mysql_close($dbh);
	//*///
 ?>