<?php
    error_reporting(0);
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	date_default_timezone_set('Asia/Shanghai');
	
	$db_host = "localhost";
	$db_user = "kk";
	$db_pass = "a123456";
	$db_name = "kk";
	
	$dbh = mysql_connect($db_host,$db_user,$db_pass) or die ('连接失败 : ' . mysql_error()); 
	mysql_select_db($db_name);
	
	$sql = "set names 'utf8'";
	query($sql);

	function query($sql, $type = '')
    {
        $dbh = $GLOBALS['dbh'];
		
		$res = mysql_query($sql,$dbh);
		$err = mysql_error();
		
		return $res;
    }
		
	/////
	function getOne($sql)
    {
        $dbh = $GLOBALS['dbh'];
		
		$res = mysql_query($sql,$dbh);
		$err = mysql_error();
		
		if(!$err){
            $row = mysql_fetch_row($res);

            if ($row !== false)
            {
                return $row[0];
            }
            else
            {
                return '';
            }
        }
        else
        {
            return false;
        }
    }
	
	
	///////////////
	function getAll($sql)
    {
        $dbh = $GLOBALS['dbh'];
		$res = mysql_query($sql,$dbh);
        if ($res !== false)
        {
            $arr = array();
            while ($row = mysql_fetch_assoc($res))
            {
                $arr[] = $row;
            }

            return $arr;
        }
        else
        {
            return false;
        }
    }
	
	
	/////
	function getRow($sql, $limited = false)
    {
        if ($limited == true)
        {
            $sql = trim($sql . ' LIMIT 1');
        }

        $dbh = $GLOBALS['dbh'];
		$res = mysql_query($sql,$dbh);
        if ($res !== false)
        {
            return mysql_fetch_assoc($res);
        }
        else
        {
            return false;
        }
	}	
	
  function html2code($str){
    $str = str_replace("<","&lt;",$str);
    $str = str_replace(">","&gt;",$str);
    $str = str_replace("\"","&quot;",$str);
    $str = str_replace("\'","&#39;",$str);
	$str = str_replace("&nbsp;","&n&bsp;",$str);
    $str = str_replace(" ","&nbsp;",$str);
    $str = str_replace("\n","<br>",$str);
	
	return $str;
  }
  
  function code2html($str){
    $str = str_replace("&lt;", "<",$str);
    $str = str_replace("&gt;", ">",$str);
    $str = str_replace("&quot;","\"",$str);
    $str = str_replace("&#39;","\'",$str);
    $str = str_replace("&nbsp;"," ",$str); 
	$str = str_replace("&n-*-b*-*sp;","&nbsp;",$str);  
	$str = str_replace("&n&bsp;","&nbsp;",$str); 
    $str = str_replace("<br>","\n",$str);
	return $str;
  }
  
  function code2html2($str){
    $str = str_replace("&nbsp;"," ",$str); 
	$str = str_replace("&n-*-b*-*sp;","&nbsp;",$str);
	$str = str_replace("&n&bsp;","&nbsp;",$str); 
	return $str;
  }
  
  function checksql($str){
    $str = str_replace(";", "",$str);
    $str = str_replace(" ", "",$str);
    $str = str_replace("-","",$str);
    $str = str_replace("'","",$str);
    $str = str_replace("\'","",$str);
    $str = str_replace("\""," ",$str); 
	return $str;
  }
  
  function cont($str){
    $str = str_replace("&nbsp;","",$str);
	$str = str_replace(" ","",$str);
	$str = str_replace("<","",$str);
	$str = str_replace(">","",$str);
	
	return $str;
  }

  function c_substr($string, $from, $length = null){
    preg_match_all('/[x80-xff]?./', $string, $match);
    if(is_null($length)){
        $result = implode('', array_slice($match[0], $from));
    }else{
        $result = implode('', array_slice($match[0], $from, $length));
    }
    return $result;
  }
  /**
  $str = "zhon?aè?min12oíguo";
  $from = 3;
  $length = 7;
  echo(c_substr($str, $from, $length));
  */
  function utf8_substr($str,$start) {
    $null = "";
    preg_match_all("/./u", $str, $ar);
    if(func_num_args() >= 3) {
       $end = func_get_arg(2);
       return join($null, array_slice($ar[0],$start,$end));
    } 
	else {
       return join($null, array_slice($ar[0],$start));
    }
  }

  
  function csubstr($str, $start=0, $length, $charset="gbk", $suffix=true){
	//if(function_exists("mb_substr")) return mb_substr($str, $start, $length, $charset);
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	
	$slice = join("",array_slice($match[0], $start, $length));
	
	if(count($match[0])>$length && $suffix){
	    $slice = join("",array_slice($match[0], $start, $length-1))."..";
	}
	
	//if($suffix) return $slice."..";
	
	return $slice;
}

function clen($str, $charset="gbk"){
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = count($match[0]);
	return $slice;
}
?>
