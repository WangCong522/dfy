<?php
/**
 * 积分折扣
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');

class jfzkControl extends SystemControl
{
    const EXPORT_SIZE = 1000;
    private $group_model;
    private $user_model;
    private $biz_model;
    private $net_model;
    private $trans_model;
    private $bonus_model;
    private $jfzk_model;


    private $links = array(
        array('url' => 'act=fin&op=seller_tpl', 'lang' => 'seller_tpl'),
        array('url' => 'act=fin&op=fin_tpl', 'lang' => 'fin_tpl'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting,fin');
        $this->group_model = Model('group');
    }

    public function indexOp()
    {
        $this->jfzkOp();
    }

    /**
     * 折扣
     */
    public function jfzkOp()
    {
        Tpl::setDirquna('fin');
        Tpl::output('act', 'jfzk');
        Tpl::showpage('jfzk.index');
    }

    /**
     * 启用
     */
    public function activationOp()
    {
        $id = intval($_GET['id']);
        $result = Model('jfzk')->activation_jfzk($id);

        if ($result) {
            showMessage('启用成功！');
        } else {
            showMessage('启用失败！', '', 'html', 'error');
        }
    }

    /**
     * 删除
     */
    public function jfzk_delOp()
    {
        $id = intval($_GET['id']);
        $result = Model('jfzk')->jfzkdel_jfzk($id);

        if ($result) {
            showMessage('删除成功！');
        } else {
            showMessage('删除失败！', '', 'html', 'error');
        }
    }

    /**
     * 彻底删除
     */
    public function jfzk_is_delOp()
    {
        $id = intval($_GET['id']);
        $result = Model('jfzk')->jfzkis_del_jfzk($id);

        if ($result) {
            showMessage('删除成功！');
        } else {
            showMessage('删除失败！', '', 'html', 'error');
        }
    }

    /**
     * 新增数据
     */
    public function message_addOp()
    {
        $lang = Language::getLangContent();
        $model_member = Model('jfzk');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["groud_id"], "require" => "true", "message" => '缺少会员等级'),
                array("input" => $_POST["rate"], "require" => "true", "message" => '缺少折扣')
            );  
            if ($_POST["rate"] < 0) showMessage('折扣率必须为正整数');
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $groud_id = trim($_POST['groud_id']);
                $rate = trim($_POST['rate']);

                //该会员等级已经设置
                $result = Model('jfzk')->getUserGroudMsg($groud_id);
                if ($result) showMessage('该等级已经设置,请启用');

                $insert_array = array();
                $insert_array['groud_id'] = $groud_id;
                $insert_array['rate'] = $rate;
                $insert_array['createtime'] = time();
                $insert_array['status'] = 1;
                $result = $model_member->addJFZKMsg($insert_array);

                if ($result) {
                    showMessage('信息添加成功', 'index.php?act=jfzk&op=index');
                } else {
                    showMessage('信息添加失败');
                }
            }
        }
        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        Tpl::setDirquna('fin');
        Tpl::output('groupList', $groupList);
        Tpl::output('act', 'jfzk');
        Tpl::showpage('jfzk.add');
        
    }

    /**
     * 编辑数据
     */
    public function jfzk_editOp()
    {
        $id = intval($_GET['id']);
        $$lang = Language::getLangContent();
        $model_member = Model('jfzk');

        /**
         * 保存
         */

        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["groud_id"], "require" => "true", "message" => '缺少会员等级123456'),
                array("input" => $_POST["rate"], "require" => "true", "message" => '缺少折扣')
            );
            if ($_POST["rate"] < 0) showMessage('折扣率必须为正整数');
            if ($_POST["rate"] > 100) showMessage('非法数值,请填写0-100以内');
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $groud_id = trim($_POST['groud_id']);
                $rate = trim($_POST['rate']);

                $insert_array = array();
                $insert_array['groud_id'] = $groud_id;
                $insert_array['rate'] = $rate;
                $result = $model_member->jfzkedit_jfzk($id,$insert_array);
                if ($result) {
                    showMessage('信息更改成功', 'index.php?act=jfzk&op=index');
                } else {
                    showMessage('信息未作更改');
                }
            }
        }
        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        $userGroudMsg = Model('jfzk')->getJfzkInfoByID($id);
        Tpl::setDirquna('fin');
        Tpl::output('groupList', $groupList);
        Tpl::output('userGroudMsg', $userGroudMsg);
        Tpl::output('act', 'jfzk');
        Tpl::showpage('jfzk.edit');
    }


    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {   

        $model_jfzk = Model('jfzk');
        $model_group = Model('group');

        $condition = array();

        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }

        $order = '';

        $param = array('id', 'username',);

        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }


        $page = $_POST['rp'];
        $jfzk_list = $model_jfzk->getJfzkList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_jfzk->shownowpage();
        $data['total_num'] = $model_jfzk->gettotalnum();

        foreach ($jfzk_list as $value) {
            $param = array();

            if ($value['status'] != 1) {
                $param['operation'] .= "<a class='btn green' href='index.php?act=jfzk&op=activation&id=" . $value['id'] . "' >
                <i class='fa fa-key'></i>启用</a>";
            }else{
            $param['operation'] = "<a class='btn blue' href='index.php?act=jfzk&op=jfzk_edit&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>编辑</a> 
                <a class='btn red' href='index.php?act=jfzk&op=jfzk_del&id=" . $value['id'] . "' ><i class='fa fa-ban'></i>删除</a>"; 
            }

            $param['id'] = $value['id'];
            $param['groud_id'] = $value['groud_id'];
            $param['group_name'] = $model_group->getOneGroupReField($value['groud_id'], 'name');
            $param['rate'] = $value['rate'].'%';
            $param['createtime'] = date("Y-m-d H:i:s", $value['createtime']);

            if ($value['status'] != 1) {
                $param['status'] = "<a class='btn green' href='index.php?act=jfzk&op=jfzk_is_del&id=" . $value['id'] . "' >
                <i class='fa fa-del'></i>彻底删除</a>";
            }else{
                $param['status'] = $value['status'] == '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            }
            
           
            
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }
}