<?php
/**
 * 消息通知
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class netControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=message&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=message&op=message_tpl', 'lang' => 'message_tpl'),
    );
    public $net_cache = '';

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,message');
    }

    public function indexOp()
    {
        $this->net_workOp();
    }

    /**
     *
     */
    public function net_workOp()
    {
        Tpl::setDirquna('member');

        $id = empty($_REQUEST['id']) ? 1 : $_REQUEST['id'] + 0;
        $this->net_cache = BASE_DATA_PATH . "/cache/net/" . $id . ".php";

        $str = $this->net_work($id);

        Tpl::output('html', file_get_contents($this->net_cache));

        Tpl::showpage('net.network', 'null_layout');
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
                pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '<br /></a><br>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) . '<table class="member-form" ><tr><td>左</td><td></td><td>右</td></tr><tr><td>' . $num_L_id['l_count'] . '</td><td>总</td><td>' . $num_L_id['r_count'] . '</td></tr></table><ul class="ceng_1"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '<br></a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $num['l_count'] . '</td><td>总</td><td>' . $num['r_count'] . '</td></tr></table></li></ul></li>');
                if ($info['r_id'] > 0) {
                    $name = $net->get_uname($info['r_id']);
                    $num = $net->getNetByID($info['r_id']);
                    $LoginInfo = $users->getMemberInfoByID($num['uid']);
                    if ($num['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
                    pq(".ceng_1")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $num['l_count'] . '</td><td>总</td><td>' . $num['r_count'] . '</td></tr></table></li>');
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
                pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) . '<table class="member-form" ><tr><td>左</td><td></td><td>右</td></tr><tr><td>' . $num_L_id['l_count'] . '</td><td>总</td><td>' . $num_L_id['r_count'] . '</td></tr></table><ul class="ceng_1"><li id="' . $info["r_id"] . '"><strong class="' . $class . '" ><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $num['l_count'] . '</td><td>总</td><td>' . $num['r_count'] . '</td></tr></table></li></ul></li>');
                if ($info['l_id'] > 0) {
                    $name = $net->get_uname($info['l_id']);
                    $num = $net->getNetByID($info['l_id']);
                    $LoginInfo = $users->getMemberInfoByID($num['uid']);
                    if ($num['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
                    pq(".ceng_1")->append('<li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $num['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $num['l_count'] . '</td><td>总</td><td>' . $num['r_count'] . '</td></tr></table></li>');
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
                if ($num_L_id['l_id'] <= 0) {
                    pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) . '<table class="member-form" ><tr><td>左</td><td></td><td>右</td></tr><tr><td>' . $num_L_id['l_count'] . '</td><td>总</td><td>' . $num_L_id['r_count'] . '</td></tr></table><ul class="ceng_1"><li><span class="reg"><strong><a  class="add" data-id="' . $info['id'] . '">注册</a></strong></span><p><a  class="add" data-id="' . $info['id'] . '">未加入</a></p></li></ul></li>');
                }
                if ($num_L_id['r_id'] <= 0) {
                    pq("#org")->append('<li><strong class="' . $classA . '"><a href="' . $this->url(array('id' => $num_L_id['uid'])) . '">用户名:' . $name_id . '<br />编号:' . $UserInfoA['id'] . '</a><br/>' . $link . '</strong>' . date("y-m-d H:i:s", $UserInfoA['login_time']) . '<table class="member-form" ><tr><td>左</td><td></td><td>右</td></tr><tr><td>' . $num_L_id['l_count'] . '</td><td>总</td><td>' . $num_L_id['r_count'] . '</td></tr></table><ul class="ceng_1"><li><span class="reg"><strong><a  class="add" data-id="' . $info['id'] . '">注册</a></strong></span><p><a  class="add" data-id="' . $info['id'] . '">未加入</a></p></li></ul></li>');
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
                pq("#" . $id1)->append('<ul class="ceng_2"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li></ul>');
                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
                    pq("#" . $id1 . " .ceng_2")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li>');
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
                    pq("#" . $id1)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id1 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id1 . '">未加入</a></p></li><li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li></ul>');
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
                pq("#" . $id2)->append('<ul class="ceng_2"><li id="' . $info["l_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li></ul>');
                if ($info['r_id'] > 0) {
                    $arr = $net->getNetByID($info['r_id']);
                    $name = $net->get_uname($arr['id']);
                    $LoginInfo = $users->getMemberInfoByID($arr['uid']);
                    if ($arr['status'] == 0) {
                        $class = 'red';
                    } else {
                        $class = '';
                    }
                    pq("#" . $id2 . " .ceng_2")->append('<li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li>');
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
                    pq("#" . $id2)->append('<ul class="ceng_2"><li><span class="reg"><strong><a  class="add" data-id="' . $id2 . '">注册</a></strong></span><p><a  class="add" data-id="' . $id2 . '">未加入</a></p></li><li id="' . $info["r_id"] . '"><strong class="' . $class . '"><a href="' . $this->url(array('id' => $arr['uid'])) . '">用户名:' . $name . '<br>编号:' . $LoginInfo['id'] . '</a></strong>' . date("y-m-d H:i:s", $LoginInfo['login_time']) . '<table class="member-form" ><tr><td>左</td><td>&nbsp;</td><td>右</td></tr><tr><td>' . $arr['l_count'] . '</td><td>总</td><td>' . $arr['r_count'] . '</td></tr></table></li></ul>');
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
}
