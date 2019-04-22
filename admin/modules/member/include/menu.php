<?php
/**
 * 菜单
 */
defined('InShopBN') or exit('Access Invalid!');

$_menu['member'] = array(
    'name' => '会员',
    'child' => array(
        array(
            'name' => '会员',
            'child' => array(
                'member' => '会员列表',
                'biz' => '服务站列表',
                'inbox' => '收件箱',
                'outbox' => '发件箱',
                'net' => '网络图',
                'pushmap' => '直推图谱',
                'wage' => '分润工资',
                // 'prize' => '会员等级发放奖品',
            )),

    ));
/*
    array(
        'name' => '现金',
        'child' => array(
            'remit' => '汇款管理',
            'remit_tx' => '提现管理',
        )),
*/