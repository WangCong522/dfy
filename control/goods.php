<?php
/**
 * 商品
 ***/
defined('InShopBN') or exit('Access Invalid!');
class goodsControl extends BaseMemberControl{
    private $net_model;
    private $member_model;
    private $biz_model;
    public function __construct(){
        parent::__construct();
        $this->biz_model = Model('biz');
        Tpl::output('Shop_index_active', "active");
    }
    //默认进入页面
    public function indexOp(){
        
        exit;
    }
    //商品列表显示页面
    public function shopOp(){
        $goods_model = Model('goods');
        $goods_class_model = Model('goods_class');
        $classInfo = $goods_class_model->getClassList(array('order' => 'id asc'));
        /**
         * 读取语言包
         */
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        $condition = array();
        if ($_GET['typeid']) {
            $condition['typeid'] = intval($_GET['typeid']);
            $condition['group_id'] = intval($_GET['typeid']);
            $curClassName = $goods_class_model->getOneClassField($condition['typeid'], 'typename');
        }
        $condition['status'] = '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $goodsList = $goods_model->getGoodsList($condition, $page);
        if ($goodsList) {
            foreach ($goodsList as &$g) {
                $g['thumb'] = UPLOAD_SITE_URL . '/' . $g['thumb'];
            }
        }
        //获取用户信息
        $member_model = Model('member');
        $userInfo = $member_model->getMemberInfoByID($this->userid);
        foreach ($goodsList as $key => $value) {
            if ($value['group_id'] !== $userInfo['begin_group'] && $value['typeid'] == 6) {
                unset($goodsList[$key]);
            }
        }
        //获取领过的商品
        $member_model = Model('member');
        $BantPrizeInof = $goods_model->getBantPrizeInof($this->userid);
        foreach ($goodsList as $key => $value) {
            foreach ($BantPrizeInof as $k => $v) {
                if ($value['gid'] == $v['good_id'] && $value['typeid'] == 6) {
                    unset($goodsList[$key]);
                }
            }
        }
        Tpl::output('goodsList', $goodsList);
        Tpl::output('toPrizegoodsList', $toPrizegoodsList);
        Tpl::output('classInfo', $classInfo);
        Tpl::output('curClassName', $curClassName);
        Tpl::output('show_page', $page->show());
        Tpl::output('User_shop_dian', "active");
        Tpl::showpage('goods_list');
    }
    //商品详情显示页面
    public function showOp(){
        $this->check_jy_pwd();
        /**
         * 读取语言包
         */
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        if (empty($_GET['id'])) {
            showMessage($lang['para_error'], '', 'html', 'error');
            //'缺少参数:文章编号'
        }
        //获取用户信息
        $member_model = Model('member');
        $userInfo = $member_model->getMemberInfoByID($this->userid);
        /**
         * 根据文章编号获取文章信息
         */
        $goods_model = Model('goods');
        $goods_class_model = Model('goods_class');
        $goods = $goods_model->getOneGoods(intval($_GET['id']));
        $goods['thumb'] = UPLOAD_SITE_URL . '/' . $goods['thumb'];
        $goods['typename'] = $goods_class_model->getOneClassField($goods['typeid'], 'typename');
        if (empty($goods) || !is_array($goods) || $goods['status'] == '0') {
            showMessage($lang['article_show_not_exists'], '', 'html', 'error');
            //'该文章并不存在'
        }
        Tpl::output('goods', $goods);
        Tpl::output('userInfo', $userInfo);
        /**
         * 根据类别编号获取文章类别信息
         */
        $goods_class_model = Model('goods_class');
        $condition = array();
        $goods_class = $goods_class_model->getOneClass($goods['typeid']);
        if (empty($goods_class) || !is_array($goods_class)) {
            showMessage($lang['article_show_delete'], '', 'html', 'error');
            //'该文章已随所属类别被删除'
        }
        Tpl::output('Message_Gonggao_selected', "active");
        Tpl::showpage('goods_show');
    }
    //查询用户状态(如果用户已激活使用order_buy,如果用户没有被激活使用order_buynot)
    public function member_statusOp(){
        $member_model = Model('member');
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($this->userid);
        if($userInfo['status'] == 0){
            $this->order_buynotOp();
        }else{
            $this->order_buyOp();
        }
    }
    //商品购买(用户已激活)
    public function order_buyOp(){
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $yulebao_model = Model('yulebao');
        $trans = Model('trans');
        $gid = $_POST['gid'];
        $sbuy_type = $_POST['sbuy_type'];
        $uid = $this->userid;
        $num = intval($_POST['num']) ? intval($_POST['num']) : 0;
        if ($sbuy_type !== 'gwb' && $sbuy_type !== 'jf') {
            $this->error('无效的支付方式！');
        }
        if (!$num) {
            $this->error('请检查商品购买数量！');
        }
        //获取商品信息
        $goodInfo = $goods_model->getOneGoods($gid);
        if (empty($goodInfo)) {
            $this->error('无效商品！');
        }
        if ($num > $goodInfo['stock']) {
            $this->error('库存不足！');
        }
        $goods_name = $goodInfo['goods_name'];
        $goods_typeid = $goodInfo['typeid'];
        $unit_price = $goodInfo['price'];
        $ship_fee = $goodInfo['ship_fee'] * $num;
        $total = $unit_price * $num + $ship_fee;
        $consignee = trim($_POST['consignee']);
        $address = trim($_POST['address']);
        $tel = trim($_POST['tel']);
        $time = time();
        $fanli = $goodInfo['fanli'];
        $beout = $goodInfo['beout'];
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        if ($userInfo['status'] == 0) {
            $this->error('未激活账户不能购买商品!');
        }
        //用户使用娱乐包购买商品
        if ($goods_typeid == 1) {
            if ($userInfo['jinbi_balance'] < $total) {
                $this->error('购买娱乐包，金币余额不足！');
            } else {
                $setting_model = Model('setting');
                $con_yulebao_limit = $setting_model->getRowSetting('con_yulebao_limit');
                //会员已购买的娱乐包
                $getCount['uid'] = $uid;
                $yulebao_count = $yulebao_model->getCount($getCount);
                if ($yulebao_count + $num > $con_yulebao_limit['value']) {
                    $this->error('已超出娱乐包购买上限');
                }
                //娱乐包进行支付
                $member_where = array('id' => $uid);
                $member_updata = array('jinbi_balance' => $userInfo['jinbi_balance'] - $total);
                $chenck_memeber = $member_model->editMember($member_where, $member_updata);
            }
        }
        if ($sbuy_type == 'hhzf') {
            if ($userInfo['ji_balance'] + $userInfo['zhang_balance'] < $total) {
                $this->error('账户总余额不足！');
            }
            //普通商品支付,优先扣除购物币
            $surplusMoney = $userInfo['zhang_balance'] - $total;
            if ($surplusMoney >= 0) {
                $gwb_balance = $surplusMoney;
                $jinbi_balance = $userInfo['ji_balance'];
                $xiaofei_gwb = $total;
                $xiaofei_jinbi = 0;
            } elseif ($surplusMoney < 0) {
                $gwb_balance = 0;
                $jinbi_balance = $userInfo['ji_balance'] - abs($surplusMoney);
                $xiaofei_gwb = $userInfo['zhang_balance'];
                $xiaofei_jinbi = abs($surplusMoney);
            }
            /*$member_where = array(
                  'id' => $uid
              );
              $member_updata = array(
                  'ji_balance' => $jinbi_balance,
                  'zhang_balance' => $gwb_balance,
                  'name' => !empty($consignee) ? $consignee : $userInfo['name'],
                  'address' => !empty($address) ? $address : $userInfo['address'],
                  'tel' => !empty($tel) ? $tel : $userInfo['tel'],
                  'pv' => $userInfo['pv'] + $total,
                  'newpv' => $userInfo['pv'] + $total
              );
              $chenck_memeber = $member_model->editMember($member_where, $member_updata);*/
        }
        //购物币支付
        if ($sbuy_type == 'gwb') {
            if ($userInfo['zhang_balance'] < $total) {
                $this->error('账户购物币余额不足！');
            }
            $gwb_balance = $userInfo['zhang_balance'] - $total;
            $jinbi_balance = $userInfo['ji_balance'];
            $xiaofei_gwb = $total;
            $xiaofei_jinbi = 0;
            $xiaofei = $xiaofei_gwb;
        }
        //积分支付
        if ($sbuy_type == 'jf') {
            if ($userInfo['ji_balance'] < $total) {
                $this->error('账户积分余额不足！');
            }
            $userzkInfo = $this->group_model->getOneGroup($userInfo['group_id']);
            //TODO 当前用户等级信息
            // $userzkInfo = Model('jfzk')->getUserGroudMsg($userInfo['group_id']);
            if (!$userzkInfo) {
                $userzkInfo['rate'] = 100;
            }
            if ($userzkInfo['status'] == 0) {
                $userzkInfo['rate'] = 100;
            }
            $newtotal = $total * ((float) $userzkInfo['rate'] / 100);
            $jinbi_balance = $userInfo['ji_balance'] - $newtotal;
            $gwb_balance = $userInfo['zhang_balance'];
            $xiaofei_gwb = 0;
            $xiaofei_jinbi = $newtotal;
            $xiaofei = $newtotal;
        }
        $member_where = array('id' => $uid);
        $member_updata = array('ji_balance' => $jinbi_balance, 'zhang_balance' => $gwb_balance, 'name' => !empty($consignee) ? $consignee : $userInfo['name'], 'address' => !empty($address) ? $address : $userInfo['address'], 'tel' => !empty($tel) ? $tel : $userInfo['tel'], 'pv' => $userInfo['pv'] + $xiaofei, 'newpv' => $userInfo['pv'] + $xiaofei);
        $chenck_memeber = $member_model->editMember($member_where, $member_updata);
        $this->net_model = Model('net');
        $userNet = $this->net_model->getNetByUser($userInfo['id']);
        //计算重消奖励
        $member_model->RepetitiveConsumptionReward($userInfo, $userNet, $xiaofei);
        //星级升级
        $member_model->newUpdateSatrLevel($userInfo, $userNet);
        if ($chenck_memeber) {
            //订单成功支付入库
            $dataOrder['uid'] = $uid;
            $dataOrder['username'] = $userInfo['username'];
            $dataOrder['gid'] = $gid;
            $dataOrder['goods_name'] = $goods_name;
            $dataOrder['goods_typeid'] = $goods_typeid;
            $dataOrder['unit_price'] = $unit_price;
            $dataOrder['num'] = $num;
            $dataOrder['ship_fee'] = $ship_fee;
            $dataOrder['total'] = $total;
            $dataOrder['addtime'] = $time;
            $dataOrder['status'] = $goods_typeid == 1 ? 1 : 1;
            $dataOrder['consignee'] = $consignee;
            $dataOrder['address'] = $address;
            $dataOrder['tel'] = $tel;
            $dataOrder['sbuy_type'] = $sbuy_type;
            $dataOrder['xiaofei'] = $xiaofei;
            $checkOrder = $goods_order_model->add($dataOrder);
            if ($checkOrder) {
                //更新库存
                $update_goods = array('gid' => $gid, 'stock' => $goodInfo['stock'] - $num);
                $goods_model->updates($update_goods);
                if ($goods_typeid == 1) {
                    $money = '-' . $total;
                    $trans->recorde($this->userid, $money, '金币', '购买娱乐包', '购买：' . $goods_name . ',购买数量：' . $num);
                } else {
                    if ($sbuy_type == 'gwb') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                    if ($sbuy_type == 'jifen') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费会员积分:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                    if ($sbuy_type == 'hhzf') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                }
                if ($goods_typeid == 1) {
                    //娱乐包直推奖
                    $bonus = Model('bonus');
                    $bonus->zhitui($total, $goods_name, $num, $unit_price, $userInfo['pid'], $userInfo['id'], $userInfo['username']);
                    //娱乐包入库
                    $dataYulebao['uid'] = $uid;
                    $dataYulebao['username'] = $userInfo['username'];
                    $dataYulebao['gid'] = $gid;
                    $dataYulebao['typeid'] = $goods_typeid;
                    $dataYulebao['price'] = $unit_price;
                    $dataYulebao['fanli'] = $fanli;
                    $dataYulebao['beout'] = $beout;
                    $dataYulebao['addtime'] = $time;
                    $dataYulebao['goods_name'] = $goods_name;
                    for ($i = 1; $i <= $num; $i++) {
                        $yulebao_model->add($dataYulebao);
                    }
                }
                $result['status'] = 1;
            }
        } else {
            $this->error('支付失败!');
        }
        echo json_encode($result);
    }
    //用户没有被激活的购买接口
    public function order_buynotOp(){
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $yulebao_model = Model('yulebao');
        $trans = Model('trans');
        $gid = $_POST['gid'];
        $sbuy_type = $_POST['sbuy_type'];
        $uid = $this->userid;
        $num = intval($_POST['num']) ? intval($_POST['num']) : 0;
        if ($sbuy_type !== 'gwb' && $sbuy_type !== 'jf') {
            $this->error('无效的支付方式！');
        }
        if (!$num) {
            $this->error('请检查商品购买数量！');
        }
        //获取商品信息
        $goodInfo = $goods_model->getOneGoods($gid);
        if (empty($goodInfo)) {
            $this->error('无效商品！');
        }
        if ($num > $goodInfo['stock']) {
            $this->error('库存不足！');
        }
        $goods_name = $goodInfo['goods_name'];
        $goods_typeid = $goodInfo['typeid'];
        $unit_price = $goodInfo['price'];
        $ship_fee = $goodInfo['ship_fee'] * $num;
        $total = $unit_price * $num + $ship_fee;
        $consignee = trim($_POST['consignee']);
        $address = trim($_POST['address']);
        $tel = trim($_POST['tel']);
        $time = time();
        $fanli = $goodInfo['fanli'];
        $beout = $goodInfo['beout'];
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        //用户使用娱乐包购买商品
        if ($goods_typeid == 1) {
            if ($userInfo['jinbi_balance'] < $total) {
                $this->error('购买娱乐包，金币余额不足！');
            } else {
                $setting_model = Model('setting');
                $con_yulebao_limit = $setting_model->getRowSetting('con_yulebao_limit');
                //会员已购买的娱乐包
                $getCount['uid'] = $uid;
                $yulebao_count = $yulebao_model->getCount($getCount);
                if ($yulebao_count + $num > $con_yulebao_limit['value']) {
                    $this->error('已超出娱乐包购买上限');
                }
                //娱乐包进行支付
                $member_where = array('id' => $uid);
                $member_updata = array('jinbi_balance' => $userInfo['jinbi_balance'] - $total);
                $chenck_memeber = $member_model->editMember($member_where, $member_updata);
            }
        }
        if ($sbuy_type == 'hhzf') {
            if ($userInfo['ji_balance'] + $userInfo['zhang_balance'] < $total) {
                $this->error('账户总余额不足！');
            }
            //普通商品支付,优先扣除购物币
            $surplusMoney = $userInfo['zhang_balance'] - $total;
            if ($surplusMoney >= 0) {
                $gwb_balance = $surplusMoney;
                $jinbi_balance = $userInfo['ji_balance'];
                $xiaofei_gwb = $total;
                $xiaofei_jinbi = 0;
            } elseif ($surplusMoney < 0) {
                $gwb_balance = 0;
                $jinbi_balance = $userInfo['ji_balance'] - abs($surplusMoney);
                $xiaofei_gwb = $userInfo['zhang_balance'];
                $xiaofei_jinbi = abs($surplusMoney);
            }
            /*$member_where = array(
                  'id' => $uid
              );
              $member_updata = array(
                  'ji_balance' => $jinbi_balance,
                  'zhang_balance' => $gwb_balance,
                  'name' => !empty($consignee) ? $consignee : $userInfo['name'],
                  'address' => !empty($address) ? $address : $userInfo['address'],
                  'tel' => !empty($tel) ? $tel : $userInfo['tel'],
                  'pv' => $userInfo['pv'] + $total,
                  'newpv' => $userInfo['pv'] + $total
              );
              $chenck_memeber = $member_model->editMember($member_where, $member_updata);*/
        }
        //购物币支付
        if ($sbuy_type == 'gwb') {
            if ($userInfo['zhang_balance'] < $total) {
                $this->error('账户购物币余额不足！');
            }
            $gwb_balance = $userInfo['zhang_balance'] - $total;
            $jinbi_balance = $userInfo['ji_balance'];
            $xiaofei_gwb = $total;
            $xiaofei_jinbi = 0;
            $xiaofei = $xiaofei_gwb;
        }
        //积分支付
        if ($sbuy_type == 'jf') {
            if ($userInfo['ji_balance'] < $total) {
                $this->error('账户积分余额不足！');
            }
            $userzkInfo = $this->group_model->getOneGroup($userInfo['group_id']);
            //TODO 当前用户等级信息
            // $userzkInfo = Model('jfzk')->getUserGroudMsg($userInfo['group_id']);
            if (!$userzkInfo) {
                $userzkInfo['rate'] = 100;
            }
            if ($userzkInfo['status'] == 0) {
                $userzkInfo['rate'] = 100;
            }
            $newtotal = $total * ((float) $userzkInfo['rate'] / 100);
            $jinbi_balance = $userInfo['ji_balance'] - $newtotal;
            $gwb_balance = $userInfo['zhang_balance'];
            $xiaofei_gwb = 0;
            $xiaofei_jinbi = $newtotal;
            $xiaofei = $newtotal;
        }
        if($total >= 2000 && $total < 4000){
            $group_id = 1;
        }elseif($total >= 4000 && $total < 13000){
            $group_id = 2;
        }elseif($total >= 13000 && $total < 26000){
            $group_id = 3;
        }elseif($total >= 26000){
            $group_id = 4;
        }else{
            $group_id = 5;
        }
        //查看用户消费情况给用户升等级$unit_price
        $member_where = array('id' => $uid);
        $member_updata = array('ji_balance' => $jinbi_balance, 'zhang_balance' => $gwb_balance, 'name' => !empty($consignee) ? $consignee : $userInfo['name'], 'address' => !empty($address) ? $address : $userInfo['address'], 'tel' => !empty($tel) ? $tel : $userInfo['tel'], 'pv' => $userInfo['pv'] + $xiaofei, 'newpv' => $userInfo['pv'] + $xiaofei, 'group_id' => $group_id);
        //用户购买成功更新用户信息(积分情况【ji_balance】;购物币【zhang_balance】;用户收货地址【address】;等同于消费额(升级依据)【pv】;新增业绩(升级依据)【newpv】)
        $chenck_memeber = $member_model->editMember($member_where, $member_updata); 
        $this->net_model = Model('net');
        $userNet = $this->net_model->getNetByUser($userInfo['id']);
        //计算重消奖励
        $member_model->RepetitiveConsumptionReward($userInfo, $userNet, $xiaofei);
        //星级升级
        $member_model->newUpdateSatrLevel($userInfo, $userNet);
        if ($chenck_memeber) {
            //订单成功支付入库
            $dataOrder['uid'] = $uid;
            $dataOrder['username'] = $userInfo['username'];
            $dataOrder['gid'] = $gid;
            $dataOrder['goods_name'] = $goods_name;
            $dataOrder['goods_typeid'] = $goods_typeid;
            $dataOrder['unit_price'] = $unit_price;
            $dataOrder['num'] = $num;
            $dataOrder['ship_fee'] = $ship_fee;
            $dataOrder['total'] = $total;
            $dataOrder['addtime'] = $time;
            $dataOrder['status'] = $goods_typeid == 1 ? 1 : 1;
            $dataOrder['consignee'] = $consignee;
            $dataOrder['address'] = $address;
            $dataOrder['tel'] = $tel;
            $dataOrder['sbuy_type'] = $sbuy_type;
            $dataOrder['xiaofei'] = $xiaofei;
            $checkOrder = $goods_order_model->add($dataOrder);
            if ($checkOrder) {
                //更新库存
                $update_goods = array('gid' => $gid, 'stock' => $goodInfo['stock'] - $num);
                $goods_model->updates($update_goods);
                if ($goods_typeid == 1) {
                    $money = '-' . $total;
                    $trans->recorde($this->userid, $money, '金币', '购买娱乐包', '购买：' . $goods_name . ',购买数量：' . $num);
                } else {
                    if ($sbuy_type == 'gwb') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                    if ($sbuy_type == 'jifen') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费会员积分:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                    if ($sbuy_type == 'hhzf') {
                        $money = '-' . $total;
                        $beizhu = '购买：' . $goods_name . ',购买数量：' . $num . ',消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
                        $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
                    }
                }
                if ($goods_typeid == 1) {
                    //娱乐包直推奖
                    $bonus = Model('bonus');
                    $bonus->zhitui($total, $goods_name, $num, $unit_price, $userInfo['pid'], $userInfo['id'], $userInfo['username']);
                    //娱乐包入库
                    $dataYulebao['uid'] = $uid;
                    $dataYulebao['username'] = $userInfo['username'];
                    $dataYulebao['gid'] = $gid;
                    $dataYulebao['typeid'] = $goods_typeid;
                    $dataYulebao['price'] = $unit_price;
                    $dataYulebao['fanli'] = $fanli;
                    $dataYulebao['beout'] = $beout;
                    $dataYulebao['addtime'] = $time;
                    $dataYulebao['goods_name'] = $goods_name;
                    for ($i = 1; $i <= $num; $i++) {
                        $yulebao_model->add($dataYulebao);
                    }
                }
                $result['status'] = 1;
            }
        } else {
            $this->error('支付失败!');
        }
        echo json_encode($result);
    }
    //查询用户是否为激活状态进行添加到购物车功能
    public function member_cartisOp(){
        $member_model = Model('member');
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($this->userid);
        if($userInfo['status'] == 0){
            $this->add_cartnotOp();
        }else{
            $this->add_cartOp();
        }
    }
    //用户已激活的加入购物车接口
    public function add_cartOp(){
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $trans = Model('trans');
        $gid = $_POST['gid'];
        $uid = $this->userid;
        $num = intval($_POST['num']) ? intval($_POST['num']) : 1;
        if (!$num) {
            $this->error('请检查商品购买数量！');
        }
        //获取商品信息
        $goodInfo = $goods_model->getOneGoods($gid);
        if (empty($goodInfo)) {
            $this->error('无效商品！');
        }
        if ($num > $goodInfo['stock']) {
            $this->error('库存不足！');
        }
        $goods_name = $goodInfo['goods_name'];
        $goods_typeid = $goodInfo['typeid'];
        $unit_price = $goodInfo['price'];
        $ship_fee = $goodInfo['ship_fee'] * $num;
        $total = $unit_price * $num + $ship_fee;
        $consignee = trim($_POST['consignee']);
        $address = trim($_POST['address']);
        $tel = trim($_POST['tel']);
        $time = time();
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        if ($userInfo['status'] == 0) {
            $this->error('未激活账户不能购买商品!');
        }
        //订单加入购物车
        $dataOrder['uid'] = $uid;
        $dataOrder['username'] = $userInfo['username'];
        $dataOrder['gid'] = $gid;
        $dataOrder['goods_name'] = $goods_name;
        $dataOrder['goods_typeid'] = $goods_typeid;
        $dataOrder['unit_price'] = $unit_price;
        $dataOrder['num'] = $num;
        $dataOrder['ship_fee'] = $ship_fee;
        $dataOrder['total'] = $total;
        $dataOrder['addtime'] = $time;
        $dataOrder['status'] = 0;
        $dataOrder['consignee'] = $consignee ? $consignee : $userInfo['consignee'];
        $dataOrder['address'] = $address ? $address : $userInfo['address'];
        $dataOrder['tel'] = $tel ? $tel : $userInfo['tel'];
        $checkOrder = $goods_order_model->add($dataOrder);
        if ($checkOrder) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['info'] = '加入购物车失败！';
        }
        echo json_encode($result);
    }
    //用户没有激活的加入购物车接口
    public function add_cartnotOp(){
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $trans = Model('trans');
        $gid = $_POST['gid'];
        $uid = $this->userid;
        $num = intval($_POST['num']) ? intval($_POST['num']) : 1;
        if (!$num) {
            $this->error('请检查商品购买数量！');
        }
        //获取商品信息
        $goodInfo = $goods_model->getOneGoods($gid);
        if (empty($goodInfo)) {
            $this->error('无效商品！');
        }
        if ($num > $goodInfo['stock']) {
            $this->error('库存不足！');
        }
        $goods_name = $goodInfo['goods_name'];
        $goods_typeid = $goodInfo['typeid'];
        $unit_price = $goodInfo['price'];
        $ship_fee = $goodInfo['ship_fee'] * $num;
        $total = $unit_price * $num + $ship_fee;
        $consignee = trim($_POST['consignee']);
        $address = trim($_POST['address']);
        $tel = trim($_POST['tel']);
        $time = time();
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        //订单加入购物车
        $dataOrder['uid'] = $uid;
        $dataOrder['username'] = $userInfo['username'];
        $dataOrder['gid'] = $gid;
        $dataOrder['goods_name'] = $goods_name;
        $dataOrder['goods_typeid'] = $goods_typeid;
        $dataOrder['unit_price'] = $unit_price;
        $dataOrder['num'] = $num;
        $dataOrder['ship_fee'] = $ship_fee;
        $dataOrder['total'] = $total;
        $dataOrder['addtime'] = $time;
        $dataOrder['status'] = 0;
        $dataOrder['consignee'] = $consignee ? $consignee : $userInfo['consignee'];
        $dataOrder['address'] = $address ? $address : $userInfo['address'];
        $dataOrder['tel'] = $tel ? $tel : $userInfo['tel'];
        $checkOrder = $goods_order_model->add($dataOrder);
        if ($checkOrder) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['info'] = '加入购物车失败！';
        }
        echo json_encode($result);
    }
    //查看购物车
    public function userCartOp(){
        $orders_model = Model('goods_order');
        $page = new Page();
        $page->setEachNum(100);
        $page->setStyle('admin');
        $where = array('uid' => $this->userid, 'status' => 'cart');
        $orderList = $orders_model->getGoodsOrderList($where, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('orderList', $orderList);
        Tpl::output('User_cart_dian', "active");
        Tpl::showpage('user.cart');
    }
    //购物车结算
    public function userCartBuyOp(){
        $sbuy_type = $_POST['sbuy_type'];
        if ($sbuy_type !== 'gwb' && $sbuy_type !== 'jf') {
            $this->error('无效的支付方式！');
        }
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $trans = Model('trans');
        $uid = $this->userid;
        $all_money = 0;
        $where = array('uid' => $this->userid, 'status' => 'cart');
        $orderList = $goods_order_model->getGoodsOrderList($where);
        foreach ($orderList as $item) {
            $goodsInfo = $goods_model->getOneGoods($item['gid']);
            if ($goodsInfo) {
                if ($goodsInfo['stock'] < $item['num']) {
                    showMessage($item['goods_name'] . '库存不足请删除！', '', 'html', 'error');
                }
            } else {
                showMessage($item['goods_name'] . '已下架！', '', 'html', 'error');
            }
            $all_money += $item['total'];
        }
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        if ($sbuy_type == 'hhzf') {
            if ($userInfo['ji_balance'] + $userInfo['zhang_balance'] < $all_money) {
                $this->error('账户总余额不足！');
            }
            //普通商品支付,优先扣除购物币
            $surplusMoney = $userInfo['zhang_balance'] - $all_money;
            if ($surplusMoney >= 0) {
                $gwb_balance = $surplusMoney;
                $jinbi_balance = $userInfo['ji_balance'];
                $xiaofei_gwb = $all_money;
                $xiaofei_jinbi = 0;
            } elseif ($surplusMoney < 0) {
                $gwb_balance = 0;
                $jinbi_balance = $userInfo['ji_balance'] - abs($surplusMoney);
                $xiaofei_gwb = $userInfo['zhang_balance'];
                $xiaofei_jinbi = abs($surplusMoney);
            }
            $beizhu = '购物车结算,消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
        }
        //购物币支付
        if ($sbuy_type == 'gwb') {
            if ($userInfo['zhang_balance'] < $all_money) {
                $this->error('账户购物币余额不足！');
            }
            $gwb_balance = $userInfo['zhang_balance'] - $all_money;
            $jinbi_balance = $userInfo['ji_balance'];
            $xiaofei_gwb = $all_money;
            $xiaofei_jinbi = 0;
            $xiaofei = $xiaofei_gwb;
            $beizhu = '购物车结算,消费积分:' . $xiaofei;
        }
        //积分支付
        if ($sbuy_type == 'jf') {
            if ($userInfo['ji_balance'] < $all_money) {
                $this->error('账户积分余额不足！');
            }
            $userzkInfo = $this->group_model->getOneGroup($userInfo['group_id']);
            //TODO 当前用户等级信息
            // $userzkInfo = Model('jfzk')->getUserGroudMsg($userInfo['group_id']);
            if (!$userzkInfo) {
                $userzkInfo['rate'] = 100;
            }
            if ($userzkInfo['status'] == 0) {
                $userzkInfo['rate'] = 100;
            }
            $newtotal = $all_money * ((float) $userzkInfo['rate'] / 100);
            $jinbi_balance = $userInfo['ji_balance'] - $newtotal;
            $gwb_balance = $userInfo['zhang_balance'];
            $xiaofei_gwb = 0;
            $xiaofei_jinbi = ${$newtotal};
            $xiaofei = $newtotal;
            $beizhu = '购物车结算,消费购物币:' . $xiaofei;
        }
        $member_where = array('id' => $uid);
        $member_updata = array('ji_balance' => $jinbi_balance, 'zhang_balance' => $gwb_balance, 'pv' => $userInfo['pv'] + $xiaofei, 'nwepv' => $userInfo['pv'] + $xiaofei);
        $this->net_model = Model('net');
        $userNet = $this->net_model->getNetByUser($userInfo['id']);
        //计算重消奖励
        $member_model->RepetitiveConsumptionReward($userInfo, $userNet, $xiaofei);
        //星级升级
        $member_model->newUpdateSatrLevel($userInfo, $userNet);
        $chenck_memeber = $member_model->editMember($member_where, $member_updata);
        if ($chenck_memeber) {
            $money = '-' . $all_money;
            // $beizhu = '购物车结算,消费购物币:' . $xiaofei_gwb . ',积分补差价:' . $xiaofei_jinbi;
            $trans->recorde($this->userid, $money, '购物币', '购买商品', $beizhu);
            $check = $goods_order_model->clear_cart($uid, $sbuy_type, $xiaofei);
            if ($check) {
                // showMessage('购物结算成功！', 'index.php?act=goods&op=userOrders');
                $result['status'] = 1;
            } else {
                // showMessage('购物结算失败！');
                $this->error('购物结算失败！');
            }
        }
        echo json_encode($result);
    }
    //查看订单
    public function userOrdersOp(){
        $orders_model = Model('goods_order');
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $where = array('uid' => $this->userid, 'status' => 'order');
        $orderList = $orders_model->getGoodsOrderList($where, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('orderList', $orderList);
        Tpl::output('User_orders_dian', "active");
        Tpl::showpage('user.orders');
    }
    //取消订单
    public function cancelOrderOp(){
        $orders_model = Model('goods_order');
        $member_model = Model('member');
        $uid = $this->userid;
        $order_id = intval($_GET['order_id']);
        $orderInfo = $orders_model->getOneGoodsOrder($order_id);
        if ($orderInfo) {
            if ($orderInfo['uid'] == $uid && $orderInfo['status'] != 2) {
                $check = $orders_model->del($order_id);
                if ($check) {
                    $member_where = array('id' => $uid);
                    $member_updata = array('zhang_balance' => array('exp', 'zhang_balance +' . $orderInfo['total']));
                    $chenck_memeber = $member_model->editMember($member_where, $member_updata);
                    if ($chenck_memeber) {
                        showMessage('取消订单成功!');
                    } else {
                        showMessage('取消订单失败!');
                    }
                } else {
                    showMessage('取消订单失败!');
                }
            } else {
                showMessage('订单已发货,取消失败！');
            }
        } else {
            showMessage('不存在订单！');
        }
    }
    //查看我的娱乐包
    public function userYulebaoOp(){
        $yulebao_model = Model('yulebao');
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $where = array('uid' => $this->userid, 'order' => 'id asc');
        $yulebaoList = $yulebao_model->getYulebaoList($where, $page);
        Tpl::output('show_page', $page->show());
        Tpl::output('yulebaoList', $yulebaoList);
        Tpl::output('User_yulebao_dian', "active");
        Tpl::showpage('user.yulebao');
    }
    /**复投娱乐包
     * state 0 : 复投失败
     * state 1 : 复投成功
     * state 2 : 金币余额不足
     */
    public function yulebaofutouOp(){
        $id = $_GET['id'];
        $uid = $this->userid;
        $member_model = Model('member');
        $yulebao_model = Model('yulebao');
        $trans_model = Model('trans');
        $yulebaoInfo = $yulebao_model->getOneYulebao($id);
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        if ($userInfo['status'] == 0) {
            $this->error('未激活账户不能购买商品!');
        }
        if ($yulebaoInfo['status'] == 0) {
            if ($userInfo['jinbi_balance'] < $yulebaoInfo['price']) {
                $result['state'] = 2;
                $result['msg'] = '金币余额不足';
            } else {
                //更新娱乐包状态
                $update_array = array('id' => $yulebaoInfo['id'], 'curfanli' => 0, 'status' => 1);
                $check_yulebalo = $yulebao_model->updates($update_array);
                if ($check_yulebalo) {
                    $member_where = array('id' => $uid);
                    $member_updata = array('jinbi_balance' => $userInfo['jinbi_balance'] - $yulebaoInfo['price']);
                    //扣除金币
                    $chenck_memeber = $member_model->editMember($member_where, $member_updata);
                    if ($chenck_memeber) {
                        $money = '-' . $yulebaoInfo['price'];
                        $trans_model->recorde($uid, $money, '金币', '复投娱乐包', '复投：' . $yulebaoInfo['goods_name']);
                        //娱乐包直推奖
                        $bonus = Model('bonus');
                        $bonus->zhitui($yulebaoInfo['price'], $yulebaoInfo['goods_name'], 1, $yulebaoInfo['price'], $userInfo['pid'], $userInfo['id'], $userInfo['username']);
                        $result['state'] = 1;
                        $result['msg'] = '复投成功';
                    }
                }
            }
        } else {
            $result['state'] = 0;
            $result['msg'] = '复投失败';
        }
        echo json_encode($result);
        exit;
    }
    /**
     * 删除娱乐包
     */
    public function yulebaodelOp(){
        $id = intval($_GET['id']);
        if ($id) {
            $yulebao_model = Model('yulebao');
            $check = $yulebao_model->del($id);
            if ($check) {
                $result = array('state' => 1, 'msg' => '删除成功！');
            } else {
                $result = array('state' => 0, 'msg' => '删除失败！');
            }
        } else {
            $result = array('state' => 0, 'msg' => '删除失败！');
        }
        echo json_encode($result);
        exit;
    }
    public function sendOp(){
        Tpl::output('Message_index_selected', "active");
        Tpl::showpage('article.send');
    }
    public function DoSendOp(){
        $type = $_REQUEST['type'];
        $title = $_REQUEST['title'];
        $num = $_REQUEST['num'];
        $content = $_REQUEST['content'];
        $user = Model('member');
        $message = Model('message');
        if ($num != '') {
            $where = array('num' => $num);
            $UserInfo = $user->getMemberInfo($where);
            if (!$UserInfo) {
                exit(json_encode(array('info' => '该用户不存在!', 'status' => 0)));
            }
        }
        if ($type == 1) {
            $totype = 1;
            $toid = 'A';
        } else {
            $totype = 0;
            $toid = $UserInfo['id'];
        }
        $data = array('formid' => $this->userid, 'formname' => $this->username, 'totype' => $totype, 'toid' => $toid, 'title' => $title, 'content' => $content, 'addtime' => time());
        $res = $message->addMessage($data);
        if ($res) {
            exit(json_encode(array('info' => '发送成功', 'status' => 1)));
        } else {
            exit(json_encode(array('info' => '发送失败', 'status' => 0)));
        }
    }
    public function messageFjOp(){
        $message = Model("message");
        $condition = array();
        //$condition['typeid']  = $_GET['id'];
        //$condition['status']  = '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        //$article_list = $article_model->getArticleList($condition,$page);
        $where = array('fromid' => $this->userid);
        $list = $message->getMsgList($where, $page);
        Tpl::output('list', $list);
        Tpl::output('show_page', $page->show());
        Tpl::output('Message_MessageFj_selected', "active");
        Tpl::showpage('article.messageFj');
    }
    public function GetMessageFjOp(){
        $id = $_REQUEST['id'] + 0;
        $message = Model("message");
        $where = array('id' => $id);
        $info = $message->getMessage($where);
        exit(json_encode(array('info' => $info, 'status' => 1)));
    }
    public function messageSjOp(){
        $message = Model("message");
        $condition = array();
        //$condition['typeid']  = $_GET['id'];
        //$condition['status']  = '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        //$article_list = $article_model->getArticleList($condition,$page);
        $where = array('toid' => $this->userid);
        $list = $message->getMsgList($where, $page);
        Tpl::output('list', $list);
        Tpl::output('Message_MessageSj_selected', "active");
        Tpl::output('show_page', $page->show());
        Tpl::showpage('article.messageSj');
    }
    public function GetMessageSjOp(){
        $id = $_REQUEST['id'] + 0;
        $message = Model("message");
        $where = array('id' => $id, 'toid' => $this->userid);
        $info = $message->getMessage($where);
        $message->editMessage($where, array('status' => '0'));
        exit(json_encode(array('info' => $info, 'status' => 1)));
    }
    //领取商品
    public function buy_prizeOp(){
        $member_model = Model('member');
        $goods_model = Model('goods');
        $goods_order_model = Model('goods_order');
        $yulebao_model = Model('yulebao');
        $trans = Model('trans');
        $gid = $_POST['gid'];
        $uid = $this->userid;
        //获取商品信息
        $goodInfo = $goods_model->getOneGoods($gid);
        if (empty($goodInfo)) {
            $this->error('无效商品！');
        }
        if ($num > $goodInfo['stock']) {
            $this->error('库存不足！');
        }
        $goods_name = $goodInfo['goods_name'];
        $goods_typeid = $goodInfo['typeid'];
        $unit_price = $goodInfo['price'];
        $ship_fee = $goodInfo['ship_fee'] * $num;
        $total = $unit_price * $num + $ship_fee;
        $consignee = trim($_POST['consignee']);
        $address = trim($_POST['address']);
        $tel = trim($_POST['tel']);
        $time = time();
        $fanli = $goodInfo['fanli'];
        $beout = $goodInfo['beout'];
        //获取用户信息
        $userInfo = $member_model->getMemberInfoByID($uid);
        if ($userInfo['status'] == 0) {
            $this->error('未激活账户不能领取奖品!');
        }
        $dataOrder['uid'] = $uid;
        $dataOrder['username'] = $userInfo['username'];
        $dataOrder['gid'] = $gid;
        $dataOrder['goods_name'] = $goods_name;
        $dataOrder['goods_typeid'] = $goods_typeid;
        $dataOrder['unit_price'] = $unit_price;
        $dataOrder['num'] = $goodInfo['give'];
        $dataOrder['ship_fee'] = $ship_fee;
        $dataOrder['total'] = $unit_price;
        $dataOrder['addtime'] = $time;
        $dataOrder['status'] = $goods_typeid == 1 ? 1 : 1;
        $dataOrder['consignee'] = $consignee;
        $dataOrder['address'] = $address;
        $dataOrder['tel'] = $tel;
        $dataOrder['sbuy_type'] = 'jp';
        $dataOrder['xiaofei'] = 0;
        $checkOrder = $goods_order_model->add($dataOrder);
        $UserPrize['user_id'] = $uid;
        $UserPrize['good_id'] = $gid;
        $UserPrize['createtime'] = time();
        $userPrize = $goods_order_model->addUserPrize($UserPrize);
        if ($checkOrder && $userPrize) {
            //更新库存
            $update_goods = array('gid' => $gid, 'stock' => $goodInfo['stock'] - 1);
            $goods_model->updates($update_goods);
            $result['status'] = 1;
        } else {
            $this->error('领取失败!');
        }
        echo json_encode($result);
    }
    //检测用户是否存在(检查侧推荐人是否存在)
    private function select_tjr($tjr){
        $net = Model('net');
        $re = $net->select_tjr($tjr);
        if ($re) {
            return $re;
        } else {
            $this->error('推荐人不存在,请检查!');
        }
    }
    //检查服务站是否存在
    private function select_ss($name){
        $re = $this->biz_model->getOneBizByUsername($name);
        if ($re) {
            if ($re['is_show']) {
                return $re;
            } else {
                $this->error('该服务站未启用！请更换服务站');
            }
        } else {
            $this->error('服务站不存在,请检查!');
        }
        exit;
    }
    //检测用户下级
    private function check_wz($username, $wz){
        $net = Model('net');
        $info = $net->select_wz($username);
        if ($info == 0) {
            $this->error('该接点用户尚未激活,不能添加下级');
        } elseif ($info == -1) {
            $this->error('该接点用户不存在,请检查');
        } else {
            if ($wz == 1) {
                if (empty($info['l_id'])) {
                    return $info['uid'];
                } else {
                    $this->error('该接点用户的市场位置左区已满!请重新选择');
                }
            } else {
                if (empty($info['r_id'])) {
                    return $info['uid'];
                } else {
                    $this->error('该接点用户的市场位置右区已满!请重新选择');
                }
            }
        }
    }
    //用户提交网络关系（报单人）
    public function member_tjrOp(){
        $tjr = trim($_REQUEST['tjr']);
        $jdr = trim($_REQUEST['jdr']);
        $ssname = $_REQUEST['ssname'];
        $scwz = $_REQUEST['scwz'];
        $gid = $users->infoMember($this->userid);
        // var_dump($gid);exit;
        //验证推荐人是否存在
        $tjr_info = $this->select_tjr($tjr);
        //验证接点人
        $jdr_uid = $this->check_wz($jdr, $scwz);
        //验证服务站是否存在并且启用
        $ssInfo = $this->select_ss($ssname);
        //更新用户信息到表
        $users = Model("member");
        $data = array(
            'rid' => $tjr_info['id'],
            'pid' => $jdr_uid,
            'ssid' => $ssInfo['id'],
            'ssuid' => $ssInfo['uid'],
            'ssname' => $ssname,
            'rname' => $tjr_info['username'],
            'lsk' => 0,
            'group_id' => $gid['group_id'],
        );
        // var_dump($this->userid);exit;
        $res = $users->editMember($this->userid,$data);
        if ($res) {
            $check = $users->register(array('uid' => $res, 'pid' => $jdr_uid, 'tjr' => $tjr_info, 'scwz' => $scwz, 'userid' => $this->userid, 'lsk' => 0));
        }
        if ($check) {
            $this->success('填写网络关系成功！');
        } else {
            $this->error('填写网络关系成功！请刷新后重试');
        }
    }
}