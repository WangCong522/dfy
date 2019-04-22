<?php
/**
 * 前台control父类,店铺control父类,会员control父类
 *
 ***/


defined('InShopBN') or exit('Access Invalid!');

class Control
{

    /**
     *  输出头部的公用信息
     *
     */
    protected function showLayout()
    {

    }

    /**
     * 验证会员是否登录
     *
     */
    protected function checkLogin()
    {
        if (cookie('is_login') !== '1') {
            $ref_url = request_uri();
            if ($_GET['inajax']) {
                showDialog('', '', 'js', "login_dialog();", 200);
            } else {
                @header("location: index.php?act=login&ref_url=" . urlencode($ref_url));
            }
            exit;
        }
    }
}

/********************************** 前台control父类 **********************************************/
class BaseHomeControl extends Control
{
    public function __construct()
    {
        //输出头部的公用信息
        $this->showLayout();

        Language::read('common,home_layout');

        Tpl::setDir('home');

        Tpl::setLayout('home_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK') {
            $_GET = Language::getGBK($_GET);
        }
        if (!C('site_status')) halt(C('closed_reason'));
    }
    public function error($msg)
    {
        exit(json_encode(array('status' => 0, 'info' => $msg)));
    }
    public function success($msg)
    {
        exit(json_encode(array('status' => 1, 'info' => $msg)));
    }
}

class BaseMemberControl extends Control
{
    protected $member_info = array();   // 会员信息
    protected $quicklink = array();       // 常用菜单

    public function __construct()
    {

        if (!C('site_status')) halt(C('closed_reason'));

        Language::read('common,member_layout');

        if ($_GET['column'] && strtoupper(CHARSET) == 'GBK') {
            $_GET = Language::getGBK($_GET);
        }
        //会员验证
        $this->checkLogin();
        //输出头部的公用信息
        $this->showLayout();
        Tpl::setDir('member');
        Tpl::setLayout('member_layout');

        //获得会员信息
        $this->userid = cookie('uid');
        $this->username = cookie('username');

    }

    public function check_jy_pwd()
    {
        if ($_SESSION['pass_jy_pwd']) {
            return true;
        } else {
            Tpl::setDir('member');
            Tpl::showpage('fin.jy_pwd');
        }
    }

    public function success($msg)
    {
        exit(json_encode(array('status' => 1, 'info' => $msg)));
    }

    public function error($msg)
    {
        exit(json_encode(array('status' => 0, 'info' => $msg)));
    }

    /**
     * 常用操作
     *
     * @param string $act
     * 如果菜单中的切换卡不在一个菜单中添加$act参数，值为当前菜单的下标
     *
     */
    protected function _getCommonOperationsAndNavLink($act = '')
    {

    }

    /**
     * 左侧导航
     * 菜单数组中child的下标要和其链接的act对应。否则面包屑不能正常显示
     * @return array
     */
    private function _getMenuList()
    {
        return $menu_list;
    }
}
