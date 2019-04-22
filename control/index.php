<?php
/**
 * 默认展示页面
 *
 */

defined('InShopBN') or exit('Access Invalid!');
class indexControl extends BaseHomeControl{
	public function indexOp(){
		header("Location:?act=member&op=home");
	}
	
	public function do_jiangOp(){
	    Model('bao')->Jiang();
	}
}