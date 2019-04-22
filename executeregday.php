<?php

header("content-type:text/html;charset=utf-8");
$conn=@mysql_connect("localhost","root","123456") or die ("链接错误");
mysql_select_db("posw",$conn);
mysql_query("set names 'utf8'");


//当日注册量清零
$sql = "UPDATE user_net SET l_num_today=0 , r_num_today=0 ";
mysql_query($sql);

echo "ok";