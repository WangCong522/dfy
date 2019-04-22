<?php
/**
 * 菜单
 *
 */
defined('InShopBN') or exit('Access Invalid!');
$_menu['fin'] = array(
    'name' => '财务',
    'child' => array(
        array(
            'name' => "财务",
            'child' => array(
                'fin' => '财务记录',
                'bao' => '奖金记录',
                'dian' => '奖金详情',
                'xu' => '奖金拨比',
                'tx_set' => '提现设置',
                'remit' => '汇款管理',
                'remit_tx' => '提现管理',
				'txsuccess' => '提现成功',
                'txfail' => '提现失败',
				// 'jfzk' => '积分消费折扣',
            )
        ),
    ));