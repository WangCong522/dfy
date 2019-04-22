<?php
/**
 * 分润工资
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class wageControl extends SystemControl
{
    const EXPORT_SIZE = 1000;
    private $biz_model;
    private $bonus_model;
    private $bonuslaiyuan_model;


    public function __construct()
    {
        parent::__construct();
        Language::read('member');
        $this->biz_model = Model('biz');
        $this->bonus_model = Model('bonus');
        $this->bonuslaiyuan_model = Model('bonuslaiyuan');
    }

    public function indexOp()
    {
        $this->wageOp();
    }

    /**
     * 会员管理
     */
    public function wageOp()
    {
        Tpl::setDirquna('member');/**/
        Tpl::showpage('wage.index');
    }

    /**
     * 分红操作
     */
    public function shareOp()
    {
        $starSetting = unserialize(C('con_star'));

        if (chksubmit()) {
            $money = intval($_POST["money"]);
            $star = intval($_POST["star"]);
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $money, "require" => "true", "message" => '金额不能为空'),
                array("input" => $star, "require" => "true", "message" => '星级不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $result = $this->bonus_model->share($star, $money);
                if ($result) {
                    showMessage('操作成功！');
                } else {
                    showMessage('操作失败！');
                }
            }
        }

        Tpl::setDirquna('member');/**/
        Tpl::output('starSetting', $starSetting);
        Tpl::showpage('wage.share');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $condition = array();
        if ($_POST['query'] != '') {
            //$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
            switch ($_POST['qtype']) {
                case 'username':
                    $condition['username_like'] = $_POST['query'];
                    break;
            }
            //$condition['where'] = $_POST['qtype'] . " like '%" . $_POST['query'] . "%'";
        }
        $condition['type'] = 12;
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        $fin_list = $this->bonuslaiyuan_model->getBonusLaiyuanList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if ($fin_list) {
            foreach ($fin_list as $value) {
                $param = array();
                //$param['operation'] = "<a class='btn blue' href='index.php?act=fin&op=read&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
                $param['time'] = date("Y-m-d H:i:s", $value['time']);
                $param['uid'] = $value['uid'];
                $param['username'] = $value['username'];
                $param['money_type'] = $value['money_type'];
                $param['money'] = $value['money'];
                $param['intro'] = $value['intro'];
                $param['rel_username'] = $value['rel_username'];
                $data['list'][$value['id']] = $param;
            }
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

    public function adoptOp()
    {
        $id = $_GET['id'];
        $uid = $_GET['uid'];
        $url = 'index.php?act=biz';
        if ($id && $uid) {
            $update_array = array(
                'id' => $id,
                'status' => 1
            );
            $result = $this->biz_model->updates($update_array);
            if ($result) {
                //服务站推荐奖
                $this->bonus_model->ssTuijian($uid);
                showMessage('审核成功！', $url);
            } else {
                showMessage('审核失败！', $url);
            }
        } else {
            showMessage('参数错误！', $url);
        }
    }

    public function trunOffOp()
    {
        $id = intval($_GET['id']);
        $is_show = intval($_GET['is_show']);
        $url = 'index.php?act=biz';
        $bizInfo = $this->biz_model->getOneBiz($id);
        if ($bizInfo['status'] == 1) {
            $check = $this->biz_model->updates(array('id' => $id, 'is_show' => $is_show));
            if ($check) {
                showMessage('操作成功！');
            } else {
                showMessage('操作失败！', $url, 'html', 'error');
            }
        } else {
            showMessage('该服务站未激活！', $url, 'html', 'error');
        }
    }

    /**
     * 性别
     * @return multitype:string
     */
    private function get_sex()
    {
        $array = array();
        $array[1] = '男';
        $array[2] = '女';
        $array[3] = '保密';
        return $array;
    }


}
