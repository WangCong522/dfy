<?php
/**
 *
 ***/

defined('InShopBN') or exit('Access Invalid!');

class netControl extends BaseMemberControl
{
    public $net_cache = '';

    public $group = [
        '1' => '银卡会员',
        '2' => '金卡会员',
        '3' => '钻卡会员',
        '4' => '金钻卡会员'
    ];

    public function __construct()
    {
        parent::__construct();
        Tpl::output('Net_index_active', "active");
    }

    /**
     */
    public function networkOp()
    {
       				$biz = Model('biz');
        $userInfo = $biz->getOneBizByUid($this->userid);
        if (!$userInfo['uid']) {
            showMessage('只有服务站能查看网络图！', '', 'html', 'error');
        }
        $r = Model("lay")->CheckLay($this->userid);

        if ($r) {
		    $url = "http://wap.dfy188.cn/ys/lay.php?uid=" . $this->userid . "&r=" . time();         
           file_get_contents($url);
        }

        $id = empty($_REQUEST['id']) ? $this->userid : $_REQUEST['id'] + 0;
        $this->net_cache = BASE_DATA_PATH . "/cache/net/" . $id . ".php";

        $str = $this->net_work($id);

        Tpl::output('html', file_get_contents($this->net_cache));
        Tpl::output('Net_network_dian', "active");

        Tpl::showpage('net.network');
    }

    private function net_work($id = 0)
    {
        $net = Model('net');
        $users = Model("member");




        $str = '<ul id="org" style="display:none" ></ul>';
        file_put_contents($this->net_cache, $str);
        phpQuery::newDocumentFile($this->net_cache);

        if ($id) {
            $info = $net->getNetByUser($id);
            setBnCookie('lay_num', $info['lay_num']);
            $num_L_id = $info;

            $UserInfoA = $users->getMemberInfoByID($num_L_id['uid']);
            if ($num_L_id['status'] == 0) {
                $classA = 'red';
            } else {
                $classA = '';
            }

            if ($info['l_id'] > 0) {

                $num = $net->getNetByID($info['l_id']);
                $name_id = $net->get_uname($info['id']);
                $name = $net->get_uname($info['l_id']);
                $LoginInfo = $users->getMemberInfoByID($num['uid']);

                if ($num['status'] == 0) {
                    $class = 'red';
                } else {
                    $class = '';
                }
                if ($num_L_id['uid'] != $this->userid) {
                    $NetInfo = $net->getNetByID($num_L_id['pid']);
                    $link = '<a class="qh" href="' . $this->url(array('id' => $NetInfo['uid'])) . '">上一级代理</a>';
                }

				$num_L_id_today = $net->getRegTodayNew($UserInfoA["id"]);
				$num_today = $net->getRegTodayNew($LoginInfo["id"]);
				

                pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '<br />'. $UserInfoA['star'] . '星<br/>'.$this->group[$UserInfoA['group_id']].'<br/>姓名:' . $UserInfoA['name'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td></td><td>右</td></tr>
                    <tr><td>' . $num_L_id['l_num'] . '</td><td>总</td><td>' . $num_L_id['r_num'] . '</td></tr>
                   <tr><td>' . $num_L_id_today['l_num_today'] . '</td><td>增</td><td>' . $num_L_id_today['r_num_today'] . '</td></tr>
                    </table>
                    <ul class="ceng_1"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                    <tr><td>' . $num['l_num'] . '</td><td>总</td><td>' . $num['r_num'] . '</td></tr>
                    <tr><td>' . $num_today['l_num_today'] . '</td><td>增</td><td>' . $num_today['r_num_today'] . '</td></tr>
                    </table></li></ul></li>');
                if ($info['r_id'] > 0) {

                    $name = $net->get_uname($info['r_id']);
                    $num = $net->getNetByID($info['r_id']);
                    $LoginInfo = $users->getMemberInfoByID($num['uid']);
                    if ($num['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }

					$num_today = $net->getRegTodayNew($LoginInfo["id"]);

                    pq(".ceng_1")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" >
                        <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $num['l_num'] . '</td><td>总</td><td>' . $num['r_num'] . '</td></tr>
                        <tr><td>' . $num_today['l_num_today'] . '</td><td>增</td><td>' . $num_today['r_num_today'] . '</td></tr>
                        </table></li>');
                } else {
                    pq(".ceng_1")->append('<li><span class="reg"><strong><a class="add"  data-id="' . $info['id'] . '">注册</a></strong></span><p><a class="add"  data-id="' . $info['id'] . '">未加入</a></p></li>');
                }
            } elseif ($info['r_id'] > 0) {
                $info = $net->getNetByUser($id);
                $num = $net->getNetByID($info['r_id']);
                $name_id = $net->get_uname($info['id']);
                $name = $net->get_uname($info['r_id']);

                $LoginInfo = $users->getMemberInfoByID($num['uid']);
                if ($num['status'] == 0) {
                    $class = 'red';
                } else {
                    $class = '';
                }
                if ($num_L_id['uid'] != $this->userid) {
                    $NetInfo = $net->getNetByID($num_L_id['pid']);
                    $link = '<a class="qh" href="' . $this->url(array('id' => $NetInfo['uid'])) . '">上一级代理</a>';
                }



					$num_L_id_today = $net->getRegTodayNew($UserInfoA["id"]);
					$num_today = $net->getRegTodayNew($LoginInfo["id"]);

                pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '</a>' . $UserInfoA['star'] . '星<br/>'.$this->group[$UserInfoA['group_id']].'<br/>姓名:' . $UserInfoA['name'] . '<br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td></td><td>右</td></tr>
                    <tr><td>' . $num_L_id['l_num'] . '</td><td>总</td><td>' . $num_L_id['r_num'] . '</td></tr>
                        <tr><td>' . $num_L_id_today['l_num_today'] . '</td><td>增</td><td>' . $num_L_id_today['r_num_today'] . '</td></tr>
                    </table>
                    <ul class="ceng_1"><li id="' . $info["r_id"] . '"><strong class="' . $class . '" ><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr>
                    <td>' . $num['l_num'] . '</td><td>总</td><td>' . $num['r_num'] . '</td></tr>
                        <tr><td>' . $num_today['l_num_today'] . '</td><td>增</td><td>' . $num_today['r_num_today'] . '</td></tr>
                    </table></li></ul></li>');
                if ($info['l_id'] > 0) {
                    $name = $net->get_uname($info['l_id']);
                    $num = $net->getNetByID($info['l_id']);
                    $LoginInfo = $users->getMemberInfoByID($num['uid']);
                    if ($num['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
					$num_today = $net->getRegTodayNew($LoginInfo["id"]);


                    pq(".ceng_1")->append('<li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" >
                        <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $num['l_num'] . '</td><td>总</td><td>' . $num['r_num'] . '</td></tr>
                        <tr><td>' . $num_today['l_num_today'] . '</td><td>增</td><td>' . $num_today['r_num_today'] . '</td></tr>
                        </table></li>');
                } else {
                    pq(".ceng_1")->prepend('<li><span class="reg"><strong><a class="add"  data-id="' . $info['id'] . '">注册</a></strong></span><p><a class="add"  data-id="' . $info['id'] . '">未加入</a></p></li>');
                }
            } else {
                $info = $net->getNetByUser($id);
                $num_L_id = $net->getNetByID($info['id']);

                $name_id = $net->get_uname($info['id']);
                if ($num_L_id['uid'] != $this->userid) {
                    $NetInfo = $net->getNetByID($num_L_id['pid']);
                    $link = '<a class="qh" href="' . $this->url(array('id' => $NetInfo['uid'])) . '">上一级代理</a>';
                }

					$num_L_id_today = $net->getRegTodayNew($UserInfoA["id"]);
					

                if ($num_L_id['l_id'] <= 0) {
                    pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '<br/>' . $UserInfoA['star'] . '星<br/>'.$this->group[$UserInfoA['group_id']].'<br/>姓名:' . $UserInfoA['name'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) .
                        '<table class="member-form" >
                        <tr><td>左</td><td></td><td>右</td></tr>
                        <tr><td>' . $num_L_id['l_num'] . '</td><td>总</td><td>' . $num_L_id['r_num'] . '</td></tr>
                        <tr><td>' . $num_L_id_today['l_num_today'] . '</td><td>增</td><td>' . $num_L_id_today['r_num_today'] . '</td></tr>
                        </table><ul class="ceng_1"><li><span class="reg"><strong><a  class="add" data-id="' . $info['id'] . '">注册</a></strong></span><p><a  class="add" data-id="' . $info['id'] . '">未加入</a></p></li></ul></li>');
                }
                if ($num_L_id['r_id'] <= 0) {
                    pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '<br/>' . $UserInfoA['star'] . '星<br/>'.$this->group[$UserInfoA['group_id']].'<br/>姓名:' . $UserInfoA['name'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) .
                        '<table class="member-form" ><tr><td>左</td><td></td><td>右</td></tr>
                        <tr><td>' . $num_L_id['l_num'] . '</td><td>总</td><td>' . $num_L_id['r_num'] . '</td></tr>
                        <tr><td>' . $num_L_id_today['l_num_today'] . '</td><td>增</td><td>' . $num_L_id_today['r_num_today'] . '</td></tr>
                        </table><ul class="ceng_1"><li><span class="reg"><strong><a  class="add" data-id="' . $info['id'] . '">注册</a></strong></span><p><a  class="add" data-id="' . $info['id'] . '">未加入</a></p></li></ul></li>');
                }
            }

            $html = pq("#org")->html();
            $html = trim($html, "'");
            $html = '<ul id="org" style="display:none" >' . $html . '</ul>';
            file_put_contents($this->net_cache, $html);
            $html = $this->left($info['l_id']);
            $html = $this->right($info['r_id']);
        }

        return $html;
    }

    public function left($id1)
    {
        if ($id1) {
            $net = Model('net');
            $users = Model('member');

            phpQuery::newDocumentFile($this->net_cache);
            $info = $net->getNetByID($id1);
            if ($info['l_id'] > 0) {
                $arr = $net->getNetByID($info['l_id']);
                $name = $net->get_uname($arr['id']);
                $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                if ($arr['status'] == 0) {
                    $class = 'red';
                } else {
                    $class = '';
                }

					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);

                pq("#" . $id1)->append('<ul class="ceng_2"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                    <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                    <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr></table></li></ul>');
                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }


					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);

                    pq("#" . $id1 . " .ceng_2")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" >
                        <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                        <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr>
                        </table></li>');
                } else {
                    pq("#" . $id1 . " .ceng_2")->append('<li><span class="reg"><strong><a class="add"  data-id="' . $id1 . '">注册</a></strong></span><p><a class="add"  data-id="' . $id1 . '">未加入</a></p></li>');
                }
            } elseif ($info['r_id'] > 0) {
                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);


                    pq("#" . $id1)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id1 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id1 . '">未加入</a></p></li><li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                        <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr></table>
                        </li></ul>');
                }
            } else {
                pq("#" . $id1)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id1 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id1 . '">未加入</a></p></li></ul>');
            }
            $html = pq("#org")->html();
            $html = trim($html, "'");
            $html = '<ul id="org" style="display:none" >' . $html . '</ul>';
            file_put_contents($this->net_cache, $html);
            if (($info['lay_num'] - cookie('lay_num')) > 1) {
                return $html;
            }
            $html = $this->left($info['l_id']);
            $html = $this->right($info['r_id']);
            return $html;
        }

    }

    public function right($id2)
    {
        if ($id2) {
            $net = Model('net');
            $users = Model('member');

            phpQuery::newDocumentFile($this->net_cache);
            $info = $net->getNetByID($id2);
            if ($info['l_id'] > 0) {
                $arr = $net->getNetByID($info['l_id']);
                $name = $net->get_uname($arr['id']);
                $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                if ($arr['status'] == 0) {
                    $class = 'red';
                } else {
                    $class = '';
                }
					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);

                pq("#" . $id2)->append('<ul class="ceng_2"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                    '<table class="member-form" >
                    <tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                    <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                    <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr>
                    </table></li></ul>');
                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);

                    pq("#" . $id2 . " .ceng_2")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'<br/>姓名:' . $LoginInfo['name'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                        <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr></table></li>');
                } else {
                    pq("#" . $id2 . " .ceng_2")->append('<li><span class="reg"><strong><a class="add"  data-id="' . $id2 . '">注册</a></strong></span><p><a class="add"  data-id="' . $id2 . '">未加入</a></p></li>');
                }
            } elseif ($info['r_id'] > 0) {

                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
					$arr_today = $net->getRegTodayNew($LoginInfo["id"]);

                    pq("#" . $id2)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id2 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id2 . '">未加入</a></p></li><li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br/>' . $LoginInfo['star'] . '星<br/>'.$this->group[$LoginInfo['group_id']].'</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) .
                        '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr>
                        <tr><td>' . $arr['l_num'] . '</td><td>总</td><td>' . $arr['r_num'] . '</td></tr>
                        <tr><td>' . $arr_today['l_num_today'] . '</td><td>增</td><td>' . $arr_today['r_num_today'] . '</td></tr>
                        </table></li></ul>');
                }

            } else {
                pq("#" . $id2)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id2 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id2 . '">未加入</a></p></li></ul>');
            }
            $html = pq("#org")->html();
            $html = trim($html, "'");
            $html = '<ul id="org" style="display:none" >' . $html . '</ul>';
            file_put_contents($this->net_cache, $html);
            if (($info['lay_num'] - cookie('lay_num')) > 1) {
                return $html;
            }
            $html = $this->right($info['r_id']);
            $html = $this->left($info['l_id']);
            return $html;
        }

    }

    private function url($arr)
    {
        $url = "index.php?act=net&op=network&id=" . $arr['id'];
        return $url;
    }


    //
}
