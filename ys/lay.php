<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<?php
	//列表
	$uid = $_REQUEST['uid'] + 0;
	include_once("util.php");

	if($uid == 0) $sql = "SELECT u.id,u.username,n.pid,n.id as net_id,n.l_id,n.r_id from user_users as u left join user_net as n on u.id=n.uid where u.id not in (select uid from user_lays) and u.status=1  order by u.id asc limit 1";
	else{
		$sql = "delete from user_lays where uid='$uid'";
		query($sql);

		$sql = "SELECT u.id,u.username,n.pid,n.id as net_id,n.l_id,n.r_id from user_users as u left join user_net as n on u.id=n.uid where u.id='$uid' and u.status=1 limit 1";
	}

	$user = getRow($sql);

	if(empty($user)) exit("OVER");

	$uid = $user['id'] + 0;
	$net_id = $user['net_id'] + 0;
	$l_id = $user['l_id'] + 0;
	$r_id = $user['r_id'] + 0;

	$step = 1000;
	$lays = array();
	$lays[1] = $lays[0] = array('l_count'=>0,'r_count'=>0);
	if($l_id > 0){
		$sql = "select count(*) as r from user_net where id=" . $l_id . " and status=1";
		$r = getOne($sql);

		if($r > 0) $lays[1]['l_count'] ++;
		else $l_id = '';
	}
	if($r_id > 0){
		$sql = "select count(*) as r from user_net where id=" . $r_id . " and status=1";
		$r = getOne($sql);

		if($r > 0) $lays[1]['r_count'] ++;
		else $r_id = '';
	}

	$flag = true;
	$where = $l_id;
	for($i = 2;$i<=$step && $flag;$i++){
	    if($where){
			$sql = "select u.username,n.pid,n.id as net_id,n.l_id,n.r_id from user_users as u left join user_net as n on u.id=n.uid where n.pid in ($where) and u.status=1";

			$list = getAll($sql);

			$arr = array();
			foreach($list as $val){
				$arr[] = $val['net_id'];
			}
			if(!$arr){
				$flag = false;
				break;
			}
			else $lays[$i]['l_count'] = count($list);
			$where = implode(",",$arr);
		}
		else $flag = false;

	}

	$flag = true;
	$where = $r_id;
	for($i = 2;$i<=$step && $flag;$i++){
	    if($where){
			$sql = "select u.username,n.pid,n.id as net_id,n.l_id,n.r_id from user_users as u left join user_net as n on u.id=n.uid where n.pid in ($where) and u.status=1";

			$list = getAll($sql);

			$arr = array();
			foreach($list as $val){
				$arr[] = $val['net_id'];
			}
			if(!$arr){
				$flag = false;
				break;
			}
			else $lays[$i]['r_count'] = count($list);
			$where = implode(",",$arr);
		}
		else $flag = false;

	}
	echo $uid . ' , ' . $user['username'] .  "<br>";
	foreach($lays as $key=>$val){
	   // echo $key .  ' : L , ' . ($val['l_count'] + 0) . " ; R , " . ($val['r_count'] + 0);
		//echo "<br>";
		$sql = "select id from user_lays where uid='$uid' and lay='$key'";
		$id = getOne($sql);
		$sql = "insert into user_lays set uid='$uid',lay='$key',l_count='" . ($val['l_count'] + 0) . "',r_count='" . ($val['r_count'] + 0) . "'";
		//echo $sql . "<br>";
		if(!$id) query($sql);
	}

	$sql = "select sum(l_count) as lc,sum(r_count) as rc from user_lays where uid='$uid' and lay>0";
	$row = getRow($sql);
	$sql = "update user_net set l_count='" . $row['lc'] . "',r_count='" . $row['rc'] . "' where uid='$uid'";
	query($sql);
 ?>
</body>
</html>