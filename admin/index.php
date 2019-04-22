<?php

/**
 * 商城板块初始化文件
 *
 */



define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

if (!@include(dirname(dirname(__FILE__)).'/shopbn.php')) exit('shopbn.php isn\'t exists!');



define('TPL_NAME',TPL_ADMIN_NAME);

define('ADMIN_TEMPLATES_URL',ADMIN_SITE_URL.'/templates/'.TPL_NAME);

define('ADMIN_RESOURCE_URL',ADMIN_SITE_URL.'/resource');

define('SHOP_TEMPLATES_URL',SHOP_SITE_URL.'/templates/'.TPL_NAME);

define('BASE_TPL_PATH',BASE_PATH.'/templates/'.TPL_NAME);

if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();

