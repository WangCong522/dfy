<?php

defined('InShopBN') or exit('Access Invalid!');

class pushmapControl extends SystemControl
{
    protected $net_model;
    protected $member_model;
    protected $group;
    protected $star;

    public function __construct()
    {
        parent::__construct();
        Language::read('member');
        $this->net_model = Model('net');
        $this->member_model = Model('member');
        $this->group = [
            '1' => '银卡',
            '2' => '金卡',
            '3' => '钻卡',
            '4' => '金钻卡'
        ];
        $this->star = [
            '0' => '普通会员',
            '1' => '一星董事',
            '2' => '二星董事',
            '3' => '三星董事',
            '4' => '四星董事',
            '5' => '五星董事',
            '6' => '六星董事',
            '7' => '七星董事',
            '8' => '一星皇冠董事',
            '9' => '二星皇冠董事',
            '10' => '三星皇冠董事'

        ];
    }

    public function indexOp()
    {
        $username = $_GET['username'];
        if ($username != '') {
            $memberInfo = $this->member_model->getMemberInfo(['username' => $username]);

            $topDownUserList = $this->member_model->getMemberList(['id' =>
                $memberInfo['id']]);
            if ($topDownUserList) {
                foreach ($topDownUserList as $k => $v) {
                    $info[$k]['user_id'] = $v['id'];
                    $downInfo2 = $this->member_model->getMemberList(['rid' => $v['id']]);
                    $info[$k]['count'] = count($downInfo2);
                    $info[$k]['name'] = $v['username'] . '-'
                        . $this->group[$v['group_id']] . '-' . $v['name'].'-'
                        .$this->star[$v['star']];
                }
            }
        } else {
            $topUserInfo = $this->member_model->getMemberInfo(['rid' => 0]);
            if ($topUserInfo) {
                $topDownUserList = $this->member_model->getMemberList(['rid' => 0]);
                if ($topDownUserList) {
                    foreach ($topDownUserList as $k => $v) {
                        $info[$k]['user_id'] = $v['id'];
                        $downInfo2 = $this->member_model->getMemberList(['rid' => $v['id']]);
                        $info[$k]['count'] = count($downInfo2);
                        $info[$k]['name'] = $v['username'] . '-'
                            . $this->group[$v['group_id']] . '-' . $v['name'].'-'
                            .$this->star[$v['star']];
                    }
                }
            }
        }
        Tpl::output('info', $info);
        Tpl::setDirquna('member');
        Tpl::showpage('pushmap.index');
    }

    public function getDownByAsynsOp()
    {
        $param = $_GET;
        $parent_Id = $param['pid'];
        $downInfo = $this->member_model->getMemberList(['rid' => $parent_Id]);
        $pCount = count($downInfo);
        $i = 1;
        $arr = [];
        foreach ($downInfo as $k => $v) {
            $nId = $v['id'];
            $nName = $v['name'];
            $downInfo2 = $this->member_model->getMemberList(['rid' => $v['id']]);
            if (count($downInfo2) > 0) {
                $arr[$k] = [
                    'id' => $nId,
                    'name' => $v['username'] . '-'
                        . $this->group[$v['group_id']] . '-' . $nName.'-'
                        .$this->star[$v['star']],
                    'count' => count($downInfo2),
                    'times' => 1,
                    'isParent' => true,
                ];
            } else {
                $arr[$k] = [
                    'id' => $nId,
                    'name' => $v['username'] . '-'
                        . $this->group[$v['group_id']] . '-' . $nName.'-'
                        .$this->star[$v['star']],
                ];
            }
            if ($i < $pCount) {
                $i++;
            }
        }

        exit(json_encode($arr));
    }

}