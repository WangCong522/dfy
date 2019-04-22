<?php

/**
 * NET模型
 */

defined('InShopBN') or exit('Access Invalid!');


class NetModel extends Model

{


    public function __construct()

    {

        parent::__construct('net');

    }


    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */

    public function getNetInfo($condition, $field = '*', $master = false)

    {

        return $this->table('net')->field($field)->where($condition)->master($master)->find();

    }


    //查询网络节点ID下的用户名

    public function get_uname($id)

    {

        $net = $this->getNetByID($id);

        $user = Model('member');

        $userinfo = $user->getMemberInfoByID($net['uid']);

        return $userinfo['username'];

    }


    /**
     * 取得详细信息（优先查询缓存）
     * @return array
     */

    public function getNetByID($Net_id, $fields = '*')

    {

        $Net_info = $this->getNetInfo(array('id' => $Net_id), '*', true);

        return $Net_info;

    }


    public function getNetByUser($uid, $fields = '*')

    {

        $Net_info = $this->getNetInfo(array('uid' => $uid), '*', true);

        return $Net_info;

    }

    /**
     * 取单个内容返回字段
     *
     * @param int $uid ID
     * @return array 数组类型的返回结果
     */
    public function getOneNetReField($uid, $field)
    {
        if (intval($uid) > 0) {
            $param = array();
            $param['table'] = 'net';
            $param['field'] = 'uid';
            $param['value'] = intval($uid);
            $result = Db::getRow($param);
            return $result[$field];
        } else {
            return false;
        }
    }

    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */

    public function getNetList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')

    {

        return $this->table('net')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();

    }


    /**
     * 列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */

    public function getNetsList($condition, $page = null, $order = 'id desc', $field = '*')

    {

        return $this->table('net')->field($field)->where($condition)->page($page)->order($order)->select();

    }


    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */

    public function del($id)

    {

        if (intval($id) > 0) {

            $where = " id = '" . intval($id) . "'";

            $result = Db::delete('net', $where);

            return $result;

        } else {

            return false;

        }

    }


    /**
     * 数量
     * @param array $condition
     * @return int
     */

    public function getNetCount($condition)

    {

        return $this->table('net')->where($condition)->count();

    }


    /**
     * 编辑
     * @param array $condition
     * @param array $data
     */

    public function editNet($condition, $data)

    {

        $update = $this->table('net')->where($condition)->update($data);

        return $update;

    }


    public function addNet($data)

    {

        $id = $this->table('net')->insert($data);

        return $id;

    }


    /**
     * 获取信息
     *
     * @param    array $param 条件
     * @param    string $field 显示字段
     * @return    array 数组格式的返回结果
     */

    public function infoNet($param, $field = '*')

    {

        if (empty($param)) return false;


        //得到条件语句

        $condition_str = $this->getCondition($param);

        $param = array();

        $param['table'] = 'net';

        $param['where'] = $condition_str;

        $param['field'] = $field;

        $param['limit'] = 1;

        $Net_list = Db::select($param);

        $Net_info = $Net_list[0];


        return $Net_info;

    }


    //检查上下级关系(检查是否为上级)

    public function CheckPid($id, $mid)

    {

        $re = $this->getNetByID($id);

        if ($re['pid'] != 0) {

            if ($re['pid'] == $mid) {

                return true;

            }

            return $this->CheckPid($re['pid'], $mid);

        } else {

            return false;

        }

        return false;

    }


    //检查上下级关系(检查是否为下级)

    public function CheckDid($id, $mid)

    {

        $re = $this->getNetByID($id);

        if ($re['pid'] != 0) {

            if ($re['pid'] == $mid) {

                return true;

            }

            return $this->CheckDid($re['pid'], $mid);

        } else {

            return false;

        }

        return false;

    }


    //到达指定区最底层

    public function MaxCeng($id, $wz)

    {

        if ($wz == 1) {

            $re = $this->getNetByID($id);

            if ($re['l_id'] > 0) {

                return $this->MaxCeng($re['l_id'], $wz);

            } else {

                return $re['uid'];

            }

        } else {

            $re = $this->getNetByID($id);

            if ($re['r_id'] > 0) {

                return $this->MaxCeng($re['r_id'], $wz);

            } else {

                return $re['uid'];

            }

        }

    }


    public function NetUserStatus($id)

    {

        $net = $this->getNetByID($id);


        return $net['status'];

    }


    //查询指定用户的左区或者右区的存在情况

    public function select_wz($username)
    {
        $users = Model('member');
        $map = array(
            'username' => $username,
        );
        $re = $users->getMemberInfo($map);
        if ($re) {
            if ($re['status'] > 0) {

            } else {
                return 0;
            }
        } else {
            return -1;
        }
        $info = $this->getNetByUser($re['id']);
        return $info;
    }


    //查询推荐人是否存在

    public function select_tjr($username)

    {

        $users = Model('member');

        $map = array(

            'username' => $username,

        );

        $re = $users->getMemberInfo($map);

        if ($re) {

            return $re;

        } else {

            return false;

        }

    }


    public function bind_user($id, $wz, $x_id, $lsk)
    {
        if ($wz == 1) {
            $data = array(
                'l_id' => $x_id
            );
        } else {
            $data = array(
                'r_id' => $x_id
            );
        }
        return $this->editNet(array('id' => $id), $data);
    }


    //更改层数

    public function ChangeLay($pid, $id)

    {

        $net = $this->getNetByID($pid);//->getField('lay_num');

        $LayNum = $net['lay_num'];

        $LayNum++;

        //$this->where('id = '.$id)->setField('lay_num',$LayNum);

        $data = array('lay_num' => $LayNum);

        return $this->editNet(array('id' => $id), $data);

    }

    //小区内接点人空位
    public function getJdr($uid)
    {
        $waitList = array();                     //等待堆栈（数组用做堆栈），未处理的id
        array_push($waitList, $uid);     //我这是点中某个节点（cid），列出所有子类中的数据
        $rsList = array();                        //结果队列
        while (count($waitList) > 0) {            //等待堆栈中还有节点，继续处理
            $tmp = array_pop($waitList);          //取出一个节点
            array_push($rsList, $tmp);            //输出这个节点//从数据表中找出这个节点的所有子节点
            $dcon['pid'] = $tmp;
            $dcs = $this->where($dcon)->field('uid')->order('uid asc')->select();
            foreach ($dcs as $value) {                //将所有子节点压入等待堆栈
                array_push($waitList, $value['uid']);
            }
        }
        if ($rsList) {
            sort($rsList);
            foreach ($rsList as $value) {
                $jdrInfo = $this->getNetByUser($value);
                if ($jdrInfo['l_id'] == 0 || $jdrInfo['r_id'] == 0) {
                    if ($jdrInfo['l_id'] == 0) {
                        $result['wz'] = 1;
                    } elseif ($jdrInfo['r_id'] == 0) {
                        $result['wz'] = 2;
                    }
                    $result['username'] = $this->get_uname($jdrInfo['uid']);
                    return $result;
                }
            }
        }
    }

    //小区人数
    public function getNum($id)
    {
        $waitList = array();                     //等待堆栈（数组用做堆栈），未处理的id
        array_push($waitList, $id);     //我这是点中某个节点（cid），列出所有子类中的数据

        $rsList = array();                        //结果队列
        while (count($waitList) > 0) {            //等待堆栈中还有节点，继续处理
            $tmp = array_pop($waitList);          //取出一个节点
            array_push($rsList, $tmp);            //输出这个节点//从数据表中找出这个节点的所有子节点
            $dcon['pid'] = $tmp;
            $dcs = $this->where($dcon)->field('id')->order('id asc')->select();

            foreach ($dcs as $value) {                //将所有子节点压入等待堆栈
                array_push($waitList, $value['id']);
            }
        }

        unset($rsList[0]);
        sort($rsList);
        if ($rsList) {
            $result = array();
            foreach ($rsList as $value) {
                $netInfo = $this->getNetByID($value);
                if ($netInfo['area'] == 1) {
                    $result['l_num'] += 1;
                    $result['l_ids'][] = $value;
                } elseif ($netInfo['area'] == 2) {
                    $result['r_num'] += 1;
                    $result['r_ids'][] = $value;
                }
            }
            return $result;
        }
    }



    //小区当天注册人数
    public function getNumToday($id)
    {
        $waitList = array();                     //等待堆栈（数组用做堆栈），未处理的id
        array_push($waitList, $id);     //我这是点中某个节点（cid），列出所有子类中的数据

        $rsList = array();                        //结果队列
        while (count($waitList) > 0) {            //等待堆栈中还有节点，继续处理
            $tmp = array_pop($waitList);          //取出一个节点
            array_push($rsList, $tmp);            //输出这个节点//从数据表中找出这个节点的所有子节点
            $dcon['pid'] = $tmp;
            $dcs = $this->where($dcon)->field('id')->order('id asc')->select();

            foreach ($dcs as $value) {                //将所有子节点压入等待堆栈
                array_push($waitList, $value['id']);
            }

        }

        unset($rsList[0]);
        sort($rsList);

       $strlist =  implode(",",$rsList);

        $curdate = date('Y-m-d');


        if ($rsList) {
            $result = array();
            foreach ($rsList as $value) {
                $netInfo = $this->getNetByID($value);
                $user = $this->table("users")->where("id=$netInfo[uid] and '$curdate'=FROM_UNIXTIME(time,'%Y-%m-%d')")->find();
                if(!$user)
                    continue;
                if ($netInfo['area'] == 1) {
                    $result['l_num'] += 1;
                } elseif ($netInfo['area'] == 2) {
                    $result['r_num'] += 1;
                }
            }
            return $result;
        }


    }

    public function getNumToday02($id){

        $curdate = date('Y-m-d');
        $dcs = $this->table('net')->where("pid = $id")->field('id,uid,area')->order('id asc')->select();
        $presult = array();
        $presult["l_num"] = 0;
        $presult["r_num"] = 0;

        foreach($dcs as $value){

            $user = $this->table("users")->where("id=$value[uid] and '$curdate'=FROM_UNIXTIME(time,'%Y-%m-%d')")->find();
            if($user)
            {
                if($value["area"]==1) {
                    $presult['l_num'] += 1;
                }
                elseif($value["area"]==2){
                    $presult['r_num'] += 1;
                }
            }

            $result = $this->getNumToday($value["id"]);

            if($result) {
                if ($value["area"] == 1) {
                    $presult['l_num'] += $result['l_num'] + $result['r_num'];
                } elseif ($value["area"] == 2) {
                    $presult['r_num'] += $result['l_num'] + $result['r_num'];
                }
            }
        }


        return $presult;

    }
	
	

    //小区当天注册人数
    public function getNumStar($id)
    {
        $waitList = array();                     //等待堆栈（数组用做堆栈），未处理的id
        array_push($waitList, $id);     //我这是点中某个节点（cid），列出所有子类中的数据

        $rsList = array();                        //结果队列
        while (count($waitList) > 0) {            //等待堆栈中还有节点，继续处理
            $tmp = array_pop($waitList);          //取出一个节点
            array_push($rsList, $tmp);            //输出这个节点//从数据表中找出这个节点的所有子节点
            $dcon['pid'] = $tmp;
            $dcs = $this->where($dcon)->field('id')->order('id asc')->select();

            foreach ($dcs as $value) {                //将所有子节点压入等待堆栈
                array_push($waitList, $value['id']);
            }

        }

        unset($rsList[0]);
        sort($rsList);

        $strlist =  implode(",",$rsList);

        $curdate = date('Y-m-d');

        $result["l_num"] = 0;
        $result["r_num"] = 0;
        $result["l_num_1"] = 0;
        $result["r_num_1"] = 0;
        $result["l_num_2"] = 0;
        $result["r_num_2"] = 0;
        $result["l_num_3"] = 0;
        $result["r_num_3"] = 0;
        $result["l_num_4"] = 0;
        $result["r_num_4"] = 0;
        if ($rsList) {
            foreach ($rsList as $value) {
                $netInfo = $this->getNetByID($value);
                $user = $this->table("users")->where("id=$netInfo[uid]")->find();


                if ($netInfo['area'] == 1) {
                    $result['l_num'] += 1;
                    if($user["star"]==1){
                        $result["l_num_1"] += 1 ;
                    }
                    elseif($user['star']==2){
                        $result["l_num_2"] += 1 ;
                    }
                    elseif($user['star']==3){
                        $result["l_num_3"] += 1 ;
                    }
                    elseif($user['star']==4){
                        $result["l_num_4"] += 1 ;
                    }
                } elseif ($netInfo['area'] == 2) {
                    $result['r_num'] += 1;
                    if($user["star"]==1){
                        $result["r_num_1"] += 1 ;
                    }
                    elseif($user['star']==2){
                        $result["r_num_2"] += 1 ;
                    }
                    elseif($user['star']==3){
                        $result["r_num_3"] += 1 ;
                    }
                    elseif($user['star']==4){
                        $result["r_num_4"] += 1 ;
                    }
                }
            }
            return $result;
        }


    }

    public function getNumToday03($id){


        $dcs = $this->table('net')->where("pid = $id")->field('id,uid,area')->order('id asc')->select();
        $presult = array();
        $presult["l_num"] = 0;
        $presult["r_num"] = 0;
        $presult["l_num_1"] = 0;
        $presult["r_num_1"] = 0;
        $presult["l_num_2"] = 0;
        $presult["r_num_2"] = 0;
        $presult["l_num_3"] = 0;
        $presult["r_num_3"] = 0;
        $presult["l_num_4"] = 0;
        $presult["r_num_4"] = 0;

        foreach($dcs as $value){

            $user = $this->table("users")->where("id=$value[uid]")->find();



            if($value["area"]==1) {
                $presult['l_num'] += 1;
                if($user["star"]==1){
                    $presult["l_num_1"] += 1 ;
                }
                elseif($user['star']==2){
                    $presult["l_num_2"] += 1 ;
                }
                elseif($user['star']==3){
                    $presult["l_num_3"] += 1 ;
                }
                elseif($user['star']==4){
                    $presult["l_num_4"] += 1 ;
                }
            }
            elseif($value["area"]==2){
                $presult['r_num'] += 1;
                if($user["star"]==1){
                    $presult["r_num_1"] += 1 ;
                }
                elseif($user['star']==2){
                    $presult["r_num_2"] += 1 ;
                }
                elseif($user['star']==3){
                    $presult["r_num_3"] += 1 ;
                }
                elseif($user['star']==4){
                    $presult["r_num_4"] += 1 ;
                }
            }
            $result = $this->getNumStar($value["id"]);

            if($result) {
                if ($value["area"] == 1) {
                    $presult['l_num'] += $result['l_num'] + $result['r_num'];
                } elseif ($value["area"] == 2) {
                    $presult['r_num'] += $result['l_num'] + $result['r_num'];
                }
            }
        }
        return $presult;

    }
	

    public function getNumToday04($id){


        $dcs = $this->table('net')->where("pid = $id")->field('id,uid,area,l_num_star,r_num_star,l_num,r_num')->order('id asc')->select();
        $presult = array();
        $presult["l_num"] = 0;
        $presult["r_num"] = 0;
        $presult["l_num_1"] = 0;
        $presult["r_num_1"] = 0;
        $presult["l_num_2"] = 0;
        $presult["r_num_2"] = 0;
        $presult["l_num_3"] = 0;
        $presult["r_num_3"] = 0;
        $presult["l_num_4"] = 0;
        $presult["r_num_4"] = 0;

        foreach($dcs as $value){

            $user = $this->table("users")->where("id=$value[uid]")->find();

            $net_l = unserialize($value["l_num_star"]);
            $net_r = unserialize($value["r_num_star"]);


            if($value["area"]==1) {
                $presult['l_num'] += 1;
                $presult["l_num"] += $value['l_num']+$value['r_num'];
                $presult["l_num_1"] += $net_l[1]+$net_r[1];
                $presult["l_num_2"] += $net_l[2]+$net_r[2];
                $presult["l_num_3"] += $net_l[3]+$net_r[3];
                $presult["l_num_4"] += $net_l[4]+$net_r[4];

                if($user["star"]==1){
                    $presult["l_num_1"] += 1 ;
                }
                elseif($user['star']==2){
                    $presult["l_num_2"] += 1 ;
                }
                elseif($user['star']==3){
                    $presult["l_num_3"] += 1 ;
                }
                elseif($user['star']==4){
                    $presult["l_num_4"] += 1 ;
                }
            }
            elseif($value["area"]==2){
                $presult['r_num'] += 1;
                $presult["r_num"] += $value['l_num']+$value['r_num'];
                $presult["r_num_1"] += $net_r[1]+$net_l[1];
                $presult["r_num_2"] += $net_r[2]+$net_l[2];
                $presult["r_num_3"] += $net_r[3]+$net_l[3];
                $presult["r_num_4"] += $net_r[4]+$net_l[4];
                if($user["star"]==1){
                    $presult["r_num_1"] += 1 ;
                }
                elseif($user['star']==2){
                    $presult["r_num_2"] += 1 ;
                }
                elseif($user['star']==3){
                    $presult["r_num_3"] += 1 ;
                }
                elseif($user['star']==4){
                    $presult["r_num_4"] += 1 ;
                }
            }

        }
        return $presult;

    }


	public function getRegTodayNew($id){
		
		$result["l_num_today"] = 0;
		$result["r_num_today"] = 0;
		
		$todayinfo = $this->table("reg_day")->where("user_id=$id and date='".date("Y-m-d")."'")->find();
		if($todayinfo){
			$result["l_num_today"] = $todayinfo['l_num'];
			$result["r_num_today"] = $todayinfo['r_num'];
		}
		return $result;
	
	}
	public function updateParentNumStar($starInfo,$id){
        $userNet = $this->table('net')->where(['uid' => $id])->find();
        if(!$userNet)
            return false;
        if($userNet['pid'] == 0)
            return false;
        if($userNet['area'] == 1){//左区
            $parentNet = $this->table('net')->where(['uid' => $userNet['pid']])->find();
            $parentNet['l_num_star'] = unserialize($parentNet['l_num_star']);
            $parentNet['l_num_star'][$starInfo['level']] = isset($parentNet['l_num_star'][$starInfo['level']]) ? $parentNet['l_num_star'][$starInfo['level']] + 1 : 1;
            $parentNet['l_num_star'] = serialize($parentNet);
            $fman_update['l_num_star'] =   $parentNet['l_num_star'];
            $this->editNet(array('uid' =>  $userNet['pid']), $fman_update);//更新左区董事等级人数
        }else{//右区
            $parentNet = $this->table('net')->where(['uid' => $userNet['pid']])->find();
            $parentNet['r_num_star'] = unserialize($parentNet['r_num_star']);
            $parentNet['r_num_star'][$starInfo['level']] = isset($parentNet['r_num_star'][$starInfo['level']]) ? $parentNet['r_num_star'][$starInfo['level']] + 1 : 1;
            $parentNet['r_num_star'] = serialize($parentNet);
            $fman_update['r_num_star'] =   $parentNet['r_num_star'];
            $this->editNet(array('uid' =>  $userNet['pid']), $fman_update);//更新左区董事等级人数
        }
        $this->updateParentNumStar($starInfo,$parentNet['uid']);
        return true;
    }
}

