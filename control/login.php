<?php
/**
 * 前台登录 退出操作 注册
 **/
defined('InShopBN') or exit('Access Invalid!');
class loginControl extends BaseHomeControl{
    public function __construct(){
        parent::__construct();
        Tpl::output('hidden_nctoolbar', 1);
    }
    private $users_model;
    //登录操作
    public function indexOp(){
        Language::read("home_login_index");
        $lang = Language::getLangContent();
        $model_member = Model('member');
        $username = $this->generate_username(6, C('reg_auto_username_prefix'));
        Tpl::output('username', $username);
        //检查登录状态
        $model_member->checkloginMember();
        if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1') {
            $script = "document.getElementById('codeimage').src='" . APP_SITE_URL . "/index.php?act=seccode&op=makecode&nchash=" . getNchash() . "&t=' + Math.random();";
        }
        // var_dump(C('captcha_status_login'));exit;
        $result = chksubmit(true, C('captcha_status_login'), 'num');
        if ($result !== false) {
            if ($result === -11) {
                showDialog($lang['login_index_login_illegal'], '', 'error', $script);
            } elseif ($result === -12) {
                showDialog($lang['login_index_wrong_checkcode'], '', 'error', $script);
            }
            if (process::islock('login')) {
                showDialog($lang['nc_common_op_repeat'], SHOP_SITE_URL, '', 'error', $script);
            }
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["username"], "require" => "true", "message" => $lang['login_index_username_isnull']),
                array("input" => $_POST["pwd"], "require" => "true", "message" => $lang['login_index_password_isnull']),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showDialog($error, SHOP_SITE_URL, 'error', $script);
            }
            $array = array();
            $array['username'] = $_POST['username'];
            $array['password'] = md5($_POST['pwd']);
            $member_info = $model_member->getMemberInfo($array);
            if (is_array($member_info) and !empty($member_info)) {
                if (!$member_info['member_state']) {
                    //showDialog($lang['login_index_account_stop'],''.'error',$script);
                }
            } else {
                process::addprocess('login');
                showDialog($lang['login_index_login_fail'], '', 'error', $script);
            }
            $model_member->createSession($member_info);
            process::clear('login');
            if ($_GET['inajax'] == 1) {
                showDialog('', $_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'], 'js');
            } else {
                redirect($_POST['ref_url']);
            }
        }else{
            //登录表单页面
            $_pic = @unserialize(C('login_pic'));
            if ($_pic[0] != '') {
                Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . $_pic[array_rand($_pic)]);
            }else{
                Tpl::output('lpic', UPLOAD_SITE_URL . '/' . ATTACH_LOGIN . '/' . rand(1, 4) . '.jpg');
            }
            if(empty($_GET['ref_url'])){
                $ref_url = getReferer();
                if (!preg_match('/act=login&op=logout/', $ref_url)) {
                    $_GET['ref_url'] = $ref_url;
                }
            }
            Tpl::output('html_title', C('site_name'));
            if ($_GET['inajax'] == 1) {
                Tpl::showpage('login_inajax', 'null_layout');
            }else{
                Tpl::showpage('login');
            }
        }
    }
    //取得密保
    public function getmbOp(){
        $username = trim($_REQUEST['uname']);
        $model_member = Model('member');
        $array = array();
        $array['username'] = $username;
        $res = array();
        $member_info = $model_member->getMemberInfo($array);
        if (is_array($member_info) and !empty($member_info)) {
            $res = array('status' => 1, 'info' => $member_info['problem']);
        } else {
            $res = array('status' => 0, 'info' => '无此用户');
        }
        exit(json_encode($res));
    }
    //邮箱找回
    public function forgetOp(){
        $email = $_REQUEST['email'];
        $username = $_REQUEST['uname'];
        $typ = $_REQUEST['typ'];
        $model_member = Model('member');
        $array['username'] = $username;
        $UserInfo = $model_member->getMemberInfo($array);
        $res = array('info' => '用户名和邮箱不匹配!请查询后重试', 'status' => 0);
        if (is_array($UserInfo) and !empty($UserInfo)) {
            if ($typ == "mb") {
                if ($email == $UserInfo['answer']) {
                    $pass = rand(1000, 9999);
                    $pwd = md5($pass);
                    $data = array('password' => $pwd, 'jy_pwd' => $pwd);
                    $model_member->editMember($array, $data);
                    $res = array('info' => '您的新密码是：' . $pass . '，请登陆后立即修改', 'status' => 1);
                } else {
                    $res = array('info' => '出错了', 'status' => 0);
                }
                exit(json_encode($res));
            }
            $token = Model('token');
            if ($UserInfo['email'] == '') {
                $res = array('info' => '该用户尚未设置邮箱,请联系管理员重置密码', 'status' => 0);
                exit(json_encode($res));
            }
            $domain = $_SERVER['HTTP_HOST'];
            $url = "http://$domain/index.php?act=login&op=find&token=";
            $num = rand(1, 3);
            $data = array(
                'id' => $UserInfo['id'],
                'userid' => $UserInfo['id'],
                'token' => md5($UserInfo['username'] . $UserInfo['email'] . $num),
                'status' => 0,
                'time' => time(),
                'num' => $num,
            );
            $url = "http://$domain/index.php?act=login&op=find&token=" . $data['token'];
            $str = '亲爱的用户' . $UserInfo['username'] . '<br>您申请了找回密码，请点击下面的链接重置密码：<br/><a href="' . $url . '">' . $url . '</a><br>------------------------------<br/>如果您点击上述链接无效，请把下面的代码拷贝到浏览器的地址栏中<br/>' . $url . '<br/>本链接在您验证过一次后将自动失效';
            $email = new Email();

            if ($token->getTokenByID($UserInfo['id'])) {
                $result = $email->send_sys_email($UserInfo['email'], '为众会员---找回密码', $str);
                if (!$result) {
                    $res = array('info' => '邮件发送失败,清稍后重试', 'status' => 0);
                    exit(json_encode($res));
                }
                $r = $token->editToken(array('id' => $UserInfo['id']), $data);

                $res = array('info' => '发送成功!请查阅邮箱!', 'status' => 1);
                exit(json_encode($res));
            } else {
                $result = $email->send_sys_email($UserInfo['email'], '为众会员---找回密码', $str);
                if (!$result) {
                    $res = array('info' => '邮件发送失败,清稍后重试', 'status' => 0);
                    exit(json_encode($res));
                }
                $token->addToken($data);
                $res = array('info' => '发送成功!请查阅邮箱!', 'status' => 1);
                exit(json_encode($res));
            }
        } else {
            exit(json_encode($res));
        }
    }
    //自动为用户随机生成用户名(长度6-13)
    private function generate_username($length = 6, $str = 'SFN'){
        // 密码字符集，可任意添加你需要的字符
        $chars = '0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            // 这里提供两种字符获取方式
            // 第一种是使用substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组$chars 的任意元素
            // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        $username = str . $password;
        if ($this->check_username($username)) {
            return $str . $password;
        } else {
            $this->generate_username(8);
        }
    }
    //检查生成username是否重复
    private function check_username($username){
        $have = Model('member')->getMemberInfo(array('username' => $username));
        if ($have) {
            return false;
        }
        return true;
    }
    //检查手机号码是否存在
    private function check_phone($phone){
        $have = Model('member')->getMemberInfo(array('tel'=>$phone));
        if($have){
            return true;
        }
        return false;
    }
    //生成6位随机数验证码
    private function randCode($length = 5, $type = 0){
        $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
        if ($type == 0) {
            array_pop($arr);
            $string = implode("", $arr);
        } else if ($type == "-1") {
            $string = implode("", $arr);
        } else {
            $string = $arr[$type];
        }
        $count = strlen($string) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str[$i] = $string[rand(0, $count)];
            $code .= $str[$i];
        }
        return $code;
    }
    //登录页注册接口
    public function registerOp(){
        $yhm = trim($_POST['yhm']);          //用户名
        $dlmm = trim($_POST['dlmm']);        //登录密码
        $jymm = trim($_POST['jymm']);        //二级密码
        $tel = $_POST['tel'];                //手机号码
        $verify = $_POST['verifycode'];      //验证码
        $name = $_POST['name'];              //真实姓名
        $sex = $_POST['sex'];                 //性别
        $users = Model("member");
        //验证
        if(!$dlmm){echo json_encode(array('status'=>'2002','info'=>'登录密码不能为空！'));exit;}
        if(!$jymm){echo json_encode(array('status'=>'2002','info'=>'交易密码不能为空！'));exit;}
        if(!$name){echo json_encode(array('status'=>'2002','info'=>'用户名不能为空！'));exit;}
        //检查验证码
        $verifycode = $this->check_verifycodeOp($tel,$verify);
        if(!$this->check_username($yhm)){echo json_encode(array('status'=>'2001','info'=>'用户名已存在！'));exit;}
        //添加用户到表
        $data = array(
            'username' => $yhm,
            'password' => md5(trim($dlmm)),
            'jy_pwd' => md5(trim($jymm)),
            'status' => 0,
            'time' => time(),
            'level_id' => 1,
            'group_id' => 5,
            'num' => 0,
            'login_status' => 0,
            'login_time' => time(),
            'name' => $name,
            'tel' => $tel,
            'sex' => $sex,
        );
        $res = $users->addMember($data);
        if ($res) {
            $check = $users->register(array('uid'=>$res,'pid'=>$jdr_uid,'tjr'=>$tjr_info,'scwz'=>$scwz,'userid'=>$this->userid,'lsk'=>$lsk));
        }
        if ($check) {
            echo json_encode(array('status'=>'0','info'=>'注册成功！','user'=>$yhm));exit;
        } else {
            echo json_encode(array('status'=>'2003','info'=>'注册失败！'));exit;
        }
    }
    //登录页忘记密码接口
    public function indexdo_forgetOp(){
        $yhm = trim($_REQUEST['yhm']);          //用户名
        $dlmm = trim($_REQUEST['dlmm']);        //登录密码
        $jymm = trim($_REQUEST['jymm']);        //二级密码
        $tel = $_REQUEST['tel'];                //手机号码
        $verify = $_REQUEST['verifycode'];      //验证码
        $users = Model("member");
        //检查用户名是否存在
        if($users->username_id(array("username"=>$yhm))<1){
            echo json_encode(array('status'=>'1001','info'=>'用户名不存在！'));exit;
        }
        //检查手机号是否存在
        $have_phone = $this->check_phone($tel);
        if(!$have_phone){
            echo json_encode(array('status'=>'1002','info'=>'手机号码不存在！'));exit;
        }
        //检查验证码
        $verifycode = $this->check_verifycodeOp($tel,$verify);
        //更新用户信息
        $data = array(
            'password' => md5(trim($dlmm)),
            'jy_pwd' => md5(trim($jymm)),
        );
        $map['username'] = $yhm;
        $res = $users->editMember($map,$data);
        if ($res) {
            echo json_encode(array('status'=>'0','info'=>'密码修改成功！','user'=>$yhm));exit;
        } else {
            echo json_encode(array('status'=>'1003','info'=>'密码修改失败，请刷新后重试！'));exit;
        }
    }
    //发送验证码
    public function get_verifycodeOp(){
        $username="8T00324";                           //改为实际账户名
        $password="8T0032446";                           //改为实际短信发送密码
        $extnumber="";
        //定时短信发送时间,格式 2016-12-06T08:09:10+08:00，null或空串表示为非定时短信(即时发送)
        $plansendtime=''; 
        //正式发送
        $phonenumber = $_REQUEST['phone'];
        if(!preg_match("/^1(3|4|5|7|8)\d{9}$/",$phonenumber)){
            showDialog($lang['login_register_phone_right'], '', 'error', $script);
        }
        $map = array();
        $map['mobile'] = $phonenumber;
        $model_member = Model('member');
        $vcinfos  =$model_member->verify($map);
        if(empty($vcinfos)){
            $map['randcode']=$this->randCode(6,1);
            $map['published']=time();
            try {
                $vcid=$model_member->addverify($map);
                $content = "您的验证码为".$map['randcode']."，请在5分钟内按照页面提示提交验证码，切勿泄露他人，谢谢！【大方园】";//后期需修改内容
                $result=WsMessageSend::send($username, $password, $phonenumber, $content,$extnumber,$plansendtime);
                if ($vcid>0 && $result!=null) {                
                    return $this->success("验证码发送成功！验证码是".$map['randcode']);
                }else{
                    return $this->error("验证码发送失败！");
               }
            } catch (Exception $e) {
               return $this->error($e->getError());
            }
        }else{
            $vcinfo['randcode']=$this->randCode(6,1);
            $vcinfo['published']=time();
            try {
                $content = "您的验证码为".$vcinfo['randcode']."，请在5分钟内按照页面提示提交验证码，切勿泄露他人，谢谢！【大方园】";//后期需修改内容
                $result=WsMessageSend::send($username, $password, $phonenumber, $content,$extnumber,$plansendtime);
                if ($model_member->updateverify($phonenumber,$vcinfo)==true && $result!=null) {                
                    return $this->success("验证码发送成功！验证码是".$vcinfo['randcode']);
                }else{
                    return $this->error("验证码发送失败！");
               }
            } catch (Exception $e) {
               return $this->error($e->getError());
            } 
        }
    }
    //检查验证码
    public function check_verifycodeOp($tel,$verify){
        $map=array();       
        if(preg_match("/^1(3|4|5|7|8)\d{9}$/",$tel)){  
            $map['mobile']=$tel;
        }else{
            echo json_encode(array('status'=>'1001','info'=>'请填写正确的手机号码'));
            exit;
        } 
        if ($verify) {
            $map['randcode']=$verify;
        }else{
            echo json_encode(array('status'=>'1002','info'=>'请填写验证码！'));exit;
        }
        $map['published']=array('gt',time()-1800000);
        $model_member = Model('member');
        if ($model_member->getVerifyInfo($map)>0){
            // echo json_encode(array('status'=>'1004','info'=>'验证码正确'));exit;
            return true;
        }else{
            echo json_encode(array('status'=>'1003','info'=>'验证码或手机号码不正确！'));
            exit;
        }
    }
    public function findOp(){
        $tn = $_REQUEST['token'];
        $token = Model('token');
        $map = array(
            'token' => $tn,
            'status' => 0,
        );
        $TokenInfo = $token->getToken($map);
        if (!$TokenInfo) {
            showMessage('链接已失效...', 'index.php', 'html', 'error');
            exit();
        }
        Tpl::output('tn', $tn);
        $token->editToken(array('id' => $TokenInfo['id']), array('status' => 1));
        Tpl::showpage('login_find_token');
    }
    public function doRepwdOp(){
        $tn = $_REQUEST['token'];
        $pwd = md5($_REQUEST['newpwd']);
        $token = Model('token');
        $user = Model('member');
        $map = array(
            'token' => $tn,
        );
        $TokenInfo = $token->getToken($map);
        if (!$TokenInfo) {
            $res = array('info' => '该用户不存在', 'status' => 0);
            exit(json_encode($res));
        }
        $user->editMember(array('id' => $TokenInfo['id']), array('password' => $pwd));
        $res = array('info' => '重置密码成功,请使用新密码进行登录!', 'status' => 1);
        exit(json_encode($res));
    }
    /**
     * 退出操作
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function logoutOp(){
        Language::read("home_login_index");
        $lang = Language::getLangContent();
        // 清理消息COOKIE
        setBnCookie('is_login', '', -3600);
        unset ($_SESSION['pass_jy_pwd']);
        if (empty($_GET['ref_url'])) {
            $ref_url = getReferer();
        } else {
            $ref_url = $_GET['ref_url'];
        }
        redirect('index.php?act=login&ref_url=' . urlencode($ref_url));
    }
    /**
     * 会员名称检测
     * @param
     * @return
     */
    public function check_memberOp(){
        /**
         * 实例化模型
         */
        $model_member = Model('users');
        $check_member_name = $model_member->getMemberInfo(array('username' => $_GET['user_name']));
        if (is_array($check_member_name) and count($check_member_name) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
    public function globleRewardOp(){
        file_put_contents('./reward.log',date('Y-m-d,H:i',time()).PHP_EOL,FILE_APPEND);
        //echo date('Y-m-d,H:i',time());die;
        $memberModel = Model('member');
        $condition = [
            'star' => ['egt',7],
            'newpv' => ['gt',0]
        ];
        $userList = $memberModel->getMemberList($condition);
        if($userList){
            $starModel = Model('star');
            $groupModel = Model('group');
            $bonuslaiyuanModel = Model('bonuslaiyuan');
            $bonuslogModel = Molde('bonuslog');
            foreach ($userList as $key => $value){
                $userStar = $starModel->getStarInfo(['level' => $value['star']]);//用户星级信息
                $userGroup = $groupModel->getOneGroup($value['group_id']);
                $reward = $value['newpv'] * $userStar['bonus_rate'] / 100 ;
                //计算购物币
                $gwReward = $reward * $userGroup['cfxf'] / 100;
                //计算积分
                $actReward = $reward - $gwReward;
                $member_where = array(
                    'id' => $value['id']
                );
                $member_updata = array(
                    'ji_balance' => $value['ji_balance'] + $gwReward,
                    'zhang_balance' => $value['zhang_balance'] + $actReward,
                );
                //TODO 更新上级PV
                $memberModel->editMember($member_where, $member_updata);
                $bonuslaiyuanModel->recorde($value['id'],$gwReward,'购物币',12,'全球分红奖',$value['id'],$value['username'],$value['username']);
                //TODO 写入本日结算
                $bonuslogModel->log($value['id'], $value['username'], 12,$actReward);
                //TODO 记录积分变动信息
                $bonuslaiyuanModel->recorde($value['id'], $actReward,'积分',12,'全球分红奖',$value['id'],$value['username'],$value['username']);
                //TODO 写入本日结算
                $bonuslogModel->log($value['id'], $value['username'], 12, $actReward);
            }
        }
        return false;
    }
}