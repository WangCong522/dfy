<?php
/**
 * 设置
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class csControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=cs&op=param', 'lang' => 'upload_param'),
        array('url' => 'act=cs&op=group', 'lang' => 'group_param'),
        array('url' => 'act=cs&op=star', 'lang' => 'star_param'),
    );
    private $group_model;
    private $star_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('setting');
        $this->group_model = Model('group');
        $this->star_model = Model('star');
    }

    public function indexOp()
    {
        $this->paramOp();
    }

    /**
     * 上传参数设置
     *
     */
    public function paramOp()
    {
        $data_serialize = array(
            'con_star',
            'con_pv'
        );
        if (chksubmit()) {

            $model_setting = Model('setting');

            $data = array();
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_PATH . '/payimg');
            $upload->set('thumb_ext', '');
            $upload->set('file_name', 'alipayimg.jpg');
            $upload->set('ifremove', false);
            if (!empty($_FILES['con_aliimg_account']['name'])) {
                $result = $upload->upfile('con_aliimg_account');
                if (!$result) {
                    showMessage($upload->error, '', '', 'error');
                } else {
                    $input['con_aliimg_account'] = $upload->file_name;
                }
            } elseif ($_POST['old_con_aliimg_account'] != '') {
                $input['con_aliimg_account'] = 'alipayimg.jpg';
            }

            $upload->set('default_dir', ATTACH_PATH . '/payimg');
            $upload->set('thumb_ext', '');
            $upload->set('file_name', 'wxpayimg.jpg');
            $upload->set('ifremove', false);
            if (!empty($_FILES['con_wximg_account']['name'])) {
                $result = $upload->upfile('con_wximg_account');
                if (!$result) {
                    showMessage($upload->error, '', '', 'error');
                } else {
                    $input['con_wximg_account'] = $upload->file_name;
                }
            } elseif ($_POST['old_con_wximg_account'] != '') {
                $input['con_wximg_account'] = 'wxpayimg.jpg';
            }
            foreach ($_POST as $key => $val) {

                if (in_array($key, $data_serialize)) {
                    $data[$key] = serialize($val);
                } else {
                    $data[$key] = trim($val);
                }
            }
            foreach ($input as $key => $val) {
                $data[$key] = trim($val);
            }
            $result = $model_setting->updateSetting($data);
            if ($result) {
                $this->log(L('nc_edit,upload_param'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,upload_param'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }

        //获取默认图片设置属性
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        foreach ($list_setting as $key => $value) {
            if (in_array($key, $data_serialize)) {
                $list_setting[$key] = unserialize($value);
            }
        }
        $new_setting = array_merge($list_setting['con_star'],$list_setting['con_pv']);
        Tpl::output('list_setting', $list_setting);

        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->links, 'param'));
        //
        Tpl::setDirquna('system');
        Tpl::showpage('upload.param');
    }

    /**
     * 会员等级设置
     */
    public function groupOp()
    {
        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->links, 'group'));
        //
        Tpl::setDirquna('system');
        Tpl::showpage('group.index');
    }
    /**
     * 星级设置
     */
    public function starOp()
    {
        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->links, 'star'));
        //
        Tpl::setDirquna('system');
        Tpl::showpage('star.index');
    }

    /**
     * 会员异步
     */
    public function groupGetXmlOp()
    {
        $condition = array();
        if (!empty($_POST['qtype'])) {
            $condition['typeid'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['like_title'] = $_POST['query'];
        }
        if (!empty($_POST['sortname']) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $condition['order'] = ltrim($condition['order'] . ',group_id desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $group_list = $this->group_model->getGroupList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($group_list)) {
            foreach ($group_list as $k => $v) {
                $list = array();
                $list['operation'] = "<a class='btn blue' href='index.php?act=cs&op=groupEdit&group_id={$v['group_id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
                if ($v['status'] == 1) {
                    $list['operation'] .= "<a class='btn blue' href='index.php?act=cs&op=trunOff&status=0&id=" . $v['group_id'] . "'>
                <i class='fa fa-pause'></i>停用</a>";
                } elseif ($v['status'] == 0) {
                    $list['operation'] .= "<a class='btn blue' href='index.php?act=cs&op=trunOff&status=1&id=" . $v['group_id'] . "'>
                <i class='fa fa-play'></i>启用</a>";
                }
                $list['name'] = $v['name'];
                $list['lsk'] = $v['lsk'];
                //$list['bdps'] = $v['bdps'];
                $list['tj'] = $v['tj'];
                $list['dpj'] = $v['dpj'];
                $list['dpj_top'] = $v['dpj_top'];
                $list['cpj'] = $v['cpj'];
//                $list['jiandian'] = $v['jiandian'];
                //$list['lead'] = $v['lead'];
                //$list['gej'] = $v['gej'];
                $list['subsidy'] = $v['subsidy'];
                $list['cfxf'] = $v['cfxf'];
                $list['jfzk'] = $v['jfzk'];
                $list['re_consum_reward'] = $v['re_consum_reward'];
                //$list['fund'] = $v['fund'];
                $data['list'][$v['group_id']] = $list;
            }
        }
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 星级异步
     */
    public function starGetXmlOp()
    {
        $condition = array();
        if (!empty($_POST['sortname']) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $condition['order'] = ltrim($condition['order'] . ',id desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $star_list = $this->star_model->getStarList($condition,$page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($star_list)) {
            foreach ($star_list as $k => $v) {
                $list = array();
                $list['operation'] = "<a class='btn blue' href='index.php?act=cs&op=starEdit&id={$v['id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
                if ($v['status'] == 1) {
                    $list['operation'] .= "<a class='btn blue' href='index.php?act=cs&op=trunOffStar&status=0&id=" . $v['id'] . "'>
                <i class='fa fa-pause'></i>停用</a>";
                } elseif ($v['status'] == 0) {
                    $list['operation'] .= "<a class='btn blue' href='index.php?act=cs&op=trunOffStar&status=1&id=" . $v['id'] . "'>
                <i class='fa fa-play'></i>启用</a>";
                }
                $list['name'] = $v['name'];
                $list['level'] = $v['level'];
                $list['con_star'] = unserialize($v['con_star']);
                $str = '';
                foreach ($list['con_star'] as $k => $value){
                    switch ($k){
                        case 1:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 2:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 3:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 4:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 5:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 6:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 7:
                            $str.= $k.'星董事'.$value.'个,';
                            break;
                        case 8:
                            $str.= '一星皇冠董事'.$value.'个,';
                            break;
                        case 9:
                            $str.= '二星皇冠董事'.$value.'个,';
                            break;
                        case 10:
                            $str.= '三星皇冠董事'.$value.'个,';
                            break;
                    }
                }
                $list['con_star'] = $str;
                $list['con_pv'] = $v['con_pv'];
                $list['jicha_rate'] = $v['jicha_rate'].'%';
                $list['bonus_rate'] = $v['bonus_rate'].'%';
                if($v['next_star'] == 0){
                    $list['next_star'] = '已是最高等级';
                }else{
                    $nextStar = $this->star_model->getStarInfo(['level' => $v['next_star']]);
                    $list['next_star'] = $nextStar['name'];
                }
                $list['updated_at'] = date('Y-m-d H:i:s',$v['updated_at']);
                $data['list'][$v['id']] = $list;
            }
        }
        exit(Tpl::flexigridXML($data));
    }

    public function trunOffOp()
    {
        $url = 'index.php?act=cs';
        $id = intval($_GET['id']) ? intval($_GET['id']) : showMessage('参数错误！', $url, 'html', 'error');
        $status = intval($_GET['status']);
        $result = $this->group_model->updates(array('group_id' => $id, 'status' => $status));
        if ($result) {
            showMessage('操作成功！');
        } else {
            showMessage('操作失败！', $url, 'html', 'error');
        }
    }

    public function trunOffStarOp()
    {
        $url = 'index.php?act=cs';
        $id = intval($_GET['id']) ? intval($_GET['id']) : showMessage('参数错误！', $url, 'html', 'error');
        $status = intval($_GET['status']);
        $result = $this->star_model->updates(array('id' => $id, 'status' => $status));
        if ($result) {
            showMessage('操作成功！');
        } else {
            showMessage('操作失败！', $url, 'html', 'error');
        }
    }
    /**
     * 编辑会员
     */
    public function groupEditOp()
    {
        $group_id = $_GET['group_id'];
        if (chksubmit()) {
            $data = array();
            foreach ($_POST as $key => $val) {
                $data[$key] = trim($val);
            }
            unset($data['form_submit']);
            unset($data['ref_url']);
            $result = $this->group_model->updates($data);
            if ($result) {
                $this->log(L('nc_edit,upload_param'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,upload_param'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $group_array = $this->group_model->getOneGroup($group_id);
        Tpl::output('PHPSESSID', session_id());
        //Tpl::output('parent_list', $parent_list);
        Tpl::output('group_array', $group_array);
        Tpl::setDirquna('system');
        Tpl::showpage('group.edit');
    }

    /**
     * 编辑星级
     */
    public function starEditOp()
    {
        $id = $_GET['id'];
        if (chksubmit()) {
            $data = array();

            foreach ($_POST as $key => $val) {
                if($key == 'con_star'){
                    $data[$key] = serialize($val);
                }else{
                    $data[$key] = trim($val);
                }
            }
            unset($data['form_submit']);
            unset($data['ref_url']);
            if($data['level'] == $data['next_star'])
                showMessage('下一等级不能为当前等级');
            $data['updated_at'] = time();
            $result = $this->star_model->updates($data);
            if ($result) {
                $this->log(L('nc_edit,upload_param'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,upload_param'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $star_array = $this->star_model->getOneStar($id);
        $star_array['con_star'] = unserialize($star_array['con_star']);
        $starList = $this->star_model->getALL(['status' => 1]);
        Tpl::output('PHPSESSID', session_id());
        //Tpl::output('parent_list', $parent_list);
        Tpl::output('star_array', $star_array);
        Tpl::output('star_list', $starList);
        Tpl::setDirquna('system');
        Tpl::showpage('star.edit');
    }

    public function starAddOp(){
        if (chksubmit()) {
            $data = array();

            foreach ($_POST as $key => $val) {
                if($key == 'con_star'){
                    $data[$key] = serialize($val);
                }else{
                    $data[$key] = trim($val);
                }
            }
            unset($data['form_submit']);
            unset($data['ref_url']);
            if($data['id'] == $data['next_star'])
                showMessage('下一等级不能为当前等级');
            $data['created_at'] = time();
            $data['updated_at'] = time();
            $result = $this->star_model->add($data);
            if ($result) {
                $this->log(L('nc_edit,upload_param'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,upload_param'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }
        $starList = $this->star_model->getALL(['status' => 1]);
        Tpl::output('PHPSESSID', session_id());
        //Tpl::output('parent_list', $parent_list);
        Tpl::output('star_list', $starList);
        Tpl::setDirquna('system');
        Tpl::showpage('star.add');
    }
    /**
     * 默认图设置
     */
    public function default_thumbOp()
    {
        $model_setting = Model('setting');
        if (chksubmit()) {
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_COMMON);
            //默认会员头像
            if (!empty($_FILES['default_user_portrait']['tmp_name'])) {
                $thumb_width = '32';
                $thumb_height = '32';

                $upload->set('thumb_width', $thumb_width);
                $upload->set('thumb_height', $thumb_height);
                $upload->set('thumb_ext', '_small');
                $upload->set('file_name', '');
                $result = $upload->upfile('default_user_portrait');
                if ($result) {
                    $_POST['default_user_portrait'] = $upload->file_name;
                } else {
                    showMessage($upload->error, '', '', 'error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            if (!empty($_POST['default_user_portrait'])) {
                $update_array['default_user_portrait'] = $_POST['default_user_portrait'];
            }
            if (!empty($update_array)) {
                $result = $model_setting->updateSetting($update_array);
            } else {
                $result = true;
            }
            if ($result === true) {
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['default_user_portrait']) && !empty($_POST['default_user_portrait'])) {
                    @unlink(BASE_UPLOAD_PATH . DS . ATTACH_COMMON . DS . $list_setting['default_user_portrait']);
                    @unlink(BASE_UPLOAD_PATH . DS . ATTACH_COMMON . DS . str_ireplace(',', '_small.', $list_setting['default_user_portrait']));
                }
                $this->log(L('nc_edit,default_thumb'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,default_thumb'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }

        $list_setting = $model_setting->getListSetting();

        //模板输出
        Tpl::output('list_setting', $list_setting);

        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->links, 'default_thumb'));
        //
        Tpl::setDirquna('system');
        Tpl::showpage('upload.thumb');
    }
}
