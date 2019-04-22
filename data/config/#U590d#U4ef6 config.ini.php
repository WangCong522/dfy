<?php

define('G_HTTP_HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));

define('G_HTTP', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://');

define("G_WEB_PATH", dirname(G_HTTP . G_HTTP_HOST . $_SERVER['SERVER_NAME']));


$config = array();

$config['shop_site_url'] = G_HTTP . $_SERVER['SERVER_NAME'];

$config['admin_site_url'] = G_HTTP . $_SERVER['SERVER_NAME'] . '/admin';


$config['upload_site_url'] = G_HTTP . $_SERVER['SERVER_NAME'] . '/data/upload';

$config['resource_site_url'] = G_HTTP . $_SERVER['SERVER_NAME'] . '/data/resource';

$config['mobile_modules_url'] = 'http://posw.12qc.cn/admin/modules/mobile';

$config['version'] = '201602150887S';
$config['setup_date'] = '2016-05-20 15:05:17';
$config['gip'] = 0;
$config['dbdriver'] = 'mysql';
$config['tablepre'] = 'user_';
$config['db']['1']['dbhost'] = 'localhost';
$config['db']['1']['dbport'] = '3306';
$config['db']['1']['dbuser'] = 'posw';
$config['db']['1']['dbpwd'] = 'posw';
$config['db']['1']['dbname'] = 'posw';
$config['db']['1']['dbcharset'] = 'UTF-8';
$config['db']['slave'] = $config['db']['master'];
$config['session_expire'] = 3600;
$config['lang_type'] = 'zh_cn';
$config['cookie_pre'] = '9A55_';
$config['cache_open'] = false;
//$config['redis']['prefix']        = 'nc_';
//$config['redis']['master']['port']        = 6379;
//$config['redis']['master']['host']        = '127.0.0.1';
//$config['redis']['master']['pconnect']    = 0;
//$config['redis']['slave']             = array();
//$config['fullindexer']['open']      = false;
//$config['fullindexer']['appname']   = 'shopnc';
$config['debug'] = false;
$config['url_model'] = false;
$config['subdomain_suffix'] = '';
//$config['session_type'] = 'redis';
//$config['session_save_path'] = 'tcp://127.0.0.1:6379';

$config['https'] = false;
return $config;