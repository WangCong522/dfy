<?php
/**
 * 菜单
 */
defined('InShopBN') or exit('Access Invalid!');
$_menu['system'] = array(
    'name' => '平台',
    'child' => array(
        array(
            'name' => $lang['nc_config'],
            'child' => array(
                'setting' => $lang['nc_web_set'],
                'message' => '邮件设置',
                'cs' => '参数设置',
                'reg_cs' => '注册设置',
                'admin' => '权限设置',
                'admin_log' => $lang['nc_admin_log'],
                'cache' => $lang['nc_admin_clear_cache'],
                'wipe_data' => '清空数据'

            )
        ),
        array(
            'name' => '数据',
            'child' => array(
                'db' => '数据库管理',
            )
        ),
        array(
            'name' => '文章',
            'child' => array(
                'article' => '文章列表',								'article_class' => '文章分类',
            )
        ),
        array(
            'name' => '商品',
            'child' => array(
                'goods' => '商品列表',
                'goods&op=goods_class' => '商品分类',
                'orders' => '订单列表',
            )
        )
    )
);
