<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 */

error_reporting(E_ALL & ~E_NOTICE);
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));
define('BASE_CORE_PATH',BASE_ROOT_PATH.'/core');
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define("BASE_UPLOAD_PATH", BASE_ROOT_PATH . "/data/upload");
define("BASE_RESOURCE_PATH", BASE_ROOT_PATH . "/data/resource");

/**
 * 初始化
 */

define('DS','/');
define('InShopBN',true);
define('StartTime',microtime(true));
define('TIMESTAMP',time());
define('DIR_SHOP','shop');
define('DIR_MBMBER','member');
define('DIR_ADMIN','admin');
define('DIR_RESOURCE','data/resource');
define('DIR_UPLOAD','data/upload');

define('ATTACH_PATH','shop');
define('ATTACH_COMMON','shop/common');
define('ATTACH_AVATAR','shop/avatar');
define('ATTACH_EDITOR','shop/editor');
define('ATTACH_LOGIN','shop/login');
define('ATTACH_ARTICLE','shop/article');
define('ATTACH_ARTICLE_GOODS','shop/goods');

define('ATTACH_ADMIN_AVATAR','admin/avatar');
define('TPL_SHOP_NAME','default');
define('TPL_ADMIN_NAME', 'default');
define('ADMIN_MODULES_SYSTEM', 'modules/system');
define('ADMIN_MODULES_MEMBER', 'modules/member');
define('ADMIN_MODULES_FIN', 'modules/fin');
define('ADMIN_MODULES_STAT', 'modules/stat');


/**
 * 初始化
 */
if (!@include(BASE_DATA_PATH.'/config/config.ini.php')) exit('config.ini.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.ini.php')){
	include(BASE_PATH.'/config/config.ini.php');
}
global $config;


define('URL_MODEL',$config['url_model']);
//$auto_site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].implode('/',$tmp_array));
define(SUBDOMAIN_SUFFIX, $config['subdomain_suffix']);
define('BASE_SITE_URL', $config['base_site_url']);
define('SHOP_SITE_URL', $config['shop_site_url']);
define('ADMIN_SITE_URL', $config['admin_site_url']);
define('ADMIN_modules_URL', $config['admin_modules_url']);
define('RESOURCE_SITE_URL',$config['resource_site_url']);
define('LOGIN_SITE_URL',$config['member_site_url']);
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define('BASE_UPLOAD_PATH',BASE_DATA_PATH.'/upload');
define('BASE_RESOURCE_PATH',BASE_DATA_PATH.'/resource');
define('RESOURCE_SITE_URL_HTTPS',$config['resource_site_url']);
define('LOGIN_RESOURCE_SITE_URL',MEMBER_SITE_URL.'/resource');
define('UPLOAD_SITE_URL', SHOP_SITE_URL."/data/upload");


define('CHARSET',$config['db'][1]['dbcharset']);
define('DBDRIVER',$config['dbdriver']);
define('SESSION_EXPIRE',$config['session_expire']);
define('LANG_TYPE',$config['lang_type']);
define('COOKIE_PRE',$config['cookie_pre']);

define('DBPRE',$config['tablepre']);
define('DBNAME',$config['db'][1]['dbname']);
define('DBHOST',$config['db'][1]['dbhost']);
$_GET['act'] = is_string($_GET['act']) ? strtolower($_GET['act']) : (is_string($_POST['act']) ? strtolower($_POST['act']) : null);
$_GET['op'] = is_string($_GET['op']) ? strtolower($_GET['op']) : (is_string($_POST['op']) ? strtolower($_POST['op']) : null);

if (empty($_GET['act'])){
    require_once(BASE_CORE_PATH.'/framework/core/route.php');
    new Route($config);
}
//统一ACTION
$_GET['act'] = preg_match('/^[\w]+$/i',$_GET['act']) ? $_GET['act'] : 'index';
$_GET['op'] = preg_match('/^[\w]+$/i',$_GET['op']) ? $_GET['op'] : 'index';

//对GET POST接收内容进行过滤,$ignore内的下标不被过滤
$ignore = array('article_content','pgoods_body','doc_content','content','sn_content','g_body','store_description','p_content','groupbuy_intro','remind_content','note_content','adv_pic_url','adv_word_url','adv_slide_url','appcode','mail_content', 'message_content','member_gradedesc');
if (!class_exists('Security')) require(BASE_CORE_PATH.'/framework/libraries/security.php');
$_GET = !empty($_GET) ? Security::getAddslashesForInput($_GET,$ignore) : array();
$_POST = !empty($_POST) ? Security::getAddslashesForInput($_POST,$ignore) : array();
$_REQUEST = !empty($_REQUEST) ? Security::getAddslashesForInput($_REQUEST,$ignore) : array();
$_SERVER = !empty($_SERVER) ? Security::getAddSlashes($_SERVER) : array();
//启用ZIP压缩
if ($config['gzip'] == 1 && function_exists('ob_gzhandler') && $_GET['inajax'] != 1){
	ob_start('ob_gzhandler');
}else {
	ob_start();
}


require_once(BASE_CORE_PATH.'/framework/libraries/queue.php');
require_once(BASE_CORE_PATH.'/framework/function/core.php');
require_once(BASE_CORE_PATH.'/framework/core/base.php');

if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Base', 'autoload'));
} else {
	function __autoload($class) {
		return Base::autoload($class);
	}
}
