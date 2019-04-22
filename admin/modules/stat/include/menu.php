<?php
/**
 * 菜单
 *
 */
defined('InShopBN') or exit('Access Invalid!');
$_menu['stat'] = array (
        'name' => '统计',
        'child' => array(
                array(
                        'name' => "会员",
                        'child' => array(
                                'stat_member' => "新增用户",
                                'stat_member_active' => '激活用户',
                        )
                ),
));