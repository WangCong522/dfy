<?php
/**
 * 文章管理
 *
 */

defined('InShopBN') or exit('Access Invalid!');

class goodsControl extends SystemControl
{
    
    const EXPORT_SIZE = 1000;
    private $group_model;
    private $user_model;
    private $biz_model;
    private $net_model;
    private $trans_model;
    private $bonus_model;
    private $prize_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('goods');
        $this->group_model = Model('group');
        $this->user_model = Model('member');
        $this->biz_model = Model('biz');
        $this->net_model = Model('net');
        $this->trans_model = Model('trans');
        $this->bonus_model = Model('bonus');
        $this->prize_model = Model('prize');
    }

    public function indexOp()
    {
        $this->goodsOp();
    }

    /**
     * 商品管理
     */
    public function goodsOp()
    {
        //分类列表
        $model_class = Model('goods_class');
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }
        Tpl::output('parent_list', $parent_list);
        Tpl::setDirquna('system');
        Tpl::showpage('goods.index');
    }

    /**
     * 商品分类
     */
    public function goods_classOp()
    {
        //分类列表
        $model_class = Model('goods_class');
        $classList = $model_class->getClassList(array());
        Tpl::output('classList', $classList);
        Tpl::setDirquna('system');
        Tpl::showpage('goods_class');
    }

    public function deleteOp()
    {
        $model_article = Model('goods');
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',', trim($_GET['del_id'], ','));
            foreach ($_GET['del_id'] as $k => $v) {
                $v = intval($v);
                $model_article->del($v);
            }
            $this->log(L('article_index_del_succ') . '[ID:' . implode(',', $_GET['del_id']) . ']', null);
            exit(json_encode(array('state' => true, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('state' => false, 'msg' => '删除失败')));
        }
    }

    /**
     * 异步调用文章列表
     */
    public function get_xmlOp()
    {
        $lang = Language::getLangContent();
        $model_goods = Model('goods');
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
        $condition['order'] = ltrim($condition['order'] . ',gid desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $article_list = $model_goods->getGoodsList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($article_list)) {
            $model_class = Model('goods_class');
            unset($condition['order']);
            $class_list = $model_class->getClassList($condition);
            $tmp_class_name = array();
            if (is_array($class_list)) {
                foreach ($class_list as $k => $v) {
                    $tmp_class_name[$v['id']] = $v['typename'];
                }
            }
            foreach ($article_list as $k => $v) {
                $list = array();
                $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$v['gid']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=goods&op=goods_edit&id={$v['gid']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
                // $list['article_sort'] = $v['article_sort'];
                $list['gid'] = $v['gid'];
                $list['title'] = $v['goods_name'];
                if (@array_key_exists($v['typeid'], $tmp_class_name)) {
                    $v['typename'] = $tmp_class_name[$v['typeid']];
                }
                $list['typename'] = $v['typename'];
                $list['price'] = $v['price'];
                $list['article_show'] = $v['status'] == 0 ? '<span class="no"><i class="fa fa-ban"></i>否</span>' : '<span class="yes"><i class="fa fa-check-circle"></i>是</span>';
                $list['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                $data['list'][$v['gid']] = $list;
            }
        }

        exit(Tpl::flexigridXML($data));
    }

    /**
     * 异步调用分类列表
     */
    public function get_goods_class_xmlOp()
    {
        $lang = Language::getLangContent();
        $model_goods_class = Model('goods_class');
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
        $condition['order'] = ltrim($condition['order'] . ',id desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $article_list = $model_goods_class->getClassList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($article_list)) {
            foreach ($article_list as $k => $v) {
                $list = array();
                $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$v['id']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=goods&op=goods_class_edit&id={$v['id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
                $list['id'] = $v['id'];
                $list['typename'] = $v['typename'];
                $data['list'][$v['id']] = $list;
            }
        }

        exit(Tpl::flexigridXML($data));
    }

    /**
     * 分类添加
     */
    public function goods_class_addOp()
    {
        $lang = Language::getLangContent();
        $model_goods_class = Model('goods_class');
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["typename"], "require" => "true", "message" => '分类名称不能为空！'),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $insert_array['typename'] = trim($_POST['typename']);
                $insert_array['status'] = 1;
                $insert_array['addtime'] = time();
                $result = $model_goods_class->add($insert_array);
                if ($result) {
                    $this->log(L('article_add_ok') . '[' . $_POST['article_title'] . ']', null);
                    showMessage("添加分类成功！");
                } else {
                    showMessage("添加分类失败！");
                }
            }
        }
        Tpl::output('PHPSESSID', session_id());
        Tpl::setDirquna('system');
        Tpl::showpage('goods_class.add');
    }

    /**
     * 分类删除
     */
    public function goods_class_deleteOp()
    {
        $model_goods = Model('goods');
        $model_goods_class = Model('goods_class');
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',', trim($_GET['del_id'], ','));
            foreach ($_GET['del_id'] as $k => $v) {
                $v = intval($v);
                $goodsList = $model_goods->getGoodsList(array('typeid' => $v));
                if ($goodsList) {
                    exit(json_encode(array('state' => false, 'msg' => '该分类下还存在商品！删除失败')));
                }
                $model_goods_class->del($v);
            }
            $this->log(L('article_index_del_succ') . '[ID:' . implode(',', $_GET['del_id']) . ']', null);
            exit(json_encode(array('state' => true, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('state' => false, 'msg' => '删除失败')));
        }
    }

    /**
     * 分类编辑
     */
    public function goods_class_editOp()
    {
        $model_goods_class = Model('goods_class');
        $id = intval($_GET['id']);
        $classInfo = $model_goods_class->getOneClass($id);
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["typename"], "require" => "true", "message" => '分类名称不能为空！'),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $update_array['typename'] = trim($_POST['typename']);
                $update_array['id'] = trim($_POST['id']);
                $result = $model_goods_class->updates($update_array);
                if ($result) {
                    $this->log(L('article_edit_ok') . '[' . $_POST['article_title'] . ']', null);
                    showMessage("编辑分类成功！");
                } else {
                    showMessage("编辑分类失败！");
                }
            }
        }
        Tpl::output('PHPSESSID', session_id());
        Tpl::output('classInfo', $classInfo);
        Tpl::setDirquna('system');
        Tpl::showpage('goods_class.edit');
    }

    /**
     * 商品添加
     */
    public function goods_addOp()
    {

        $lang = Language::getLangContent();
        $model_goods = Model('goods');
        /**
         * 保存
         */
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["goods_name"], "require" => "true", "message" => $lang['article_add_title_null']),
                array("input" => $_POST["typeid"], "require" => "true", "message" => $lang['article_add_class_null']),
            );
            if ($_POST["typeid"] == 1) {
                $obj_validate->validateparam = array(
                    array("input" => $_POST["fanli"], "require" => "true", "message" => $lang['goods_add_fanli_null']),
                    array("input" => $_POST["beout"], "require" => "true", "message" => $lang['goods_add_beout_null']),
                );
            }
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                $insert_array = array();
                $insert_array['goods_name'] = trim($_POST['goods_name']);
                $insert_array['typeid'] = intval($_POST['typeid']);
                $insert_array['status'] = trim($_POST['article_show']);
                $insert_array['content'] = trim($_POST['content']);
                $insert_array['addtime'] = time();
                $insert_array['price'] = trim($_POST['price']);
                $insert_array['fanli'] = trim($_POST['fanli']);
                $insert_array['beout'] = trim($_POST['beout']);
                $insert_array['thumb'] = trim($_POST['thumb']);
                $insert_array['stock'] = trim($_POST['stock']);
                $insert_array['ship_fee'] = trim($_POST['ship_fee']);
                $insert_array['give'] = trim($_POST['give']);
                $insert_array['group_id'] = trim($_POST['group_id']);
                if ($_POST['typeid'] != 6) {
                    $insert_array['group_id'] = 0;
                }
                
                $result = $model_goods->add($insert_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => 'index.php?act=goods&op=goods',
                            'msg' => "{$lang['article_add_tolist']}",
                        ),
                        array(
                            'url' => 'index.php?act=goods&op=goods_add&ac_id=' . intval($_POST['typeid']),
                            'msg' => "{$lang['article_add_continueadd']}",
                        ),
                    );
                    $this->log(L('article_add_ok') . '[' . $_POST['article_title'] . ']', null);
                    showMessage("{$lang['article_add_ok']}", $url);
                } else {
                    showMessage("{$lang['article_add_fail']}");
                }
            }
        }
        /**
         * 分类列表
         */
        $model_class = Model('goods_class');
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['ac_name'];
            }
        }

        $groupList = $this->group_model->getGroupList(array('g_status' => 1));
        /**
         * 模型实例化
         */

        Tpl::output('PHPSESSID', session_id());
        Tpl::output('typeid', intval($_GET['typeid']));
        Tpl::output('parent_list', $parent_list);
        Tpl::output('groupList', $groupList);
        Tpl::setDirquna('system');
        Tpl::showpage('goods.add');
    }

    /**
     * 商品编辑
     */
    public function goods_editOp()
    {
        $lang = Language::getLangContent();
        $model_goods = Model('goods');

        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["goods_name"], "require" => "true", "message" => $lang['article_add_title_null']),
                array("input" => $_POST["typeid"], "require" => "true", "message" => $lang['article_add_class_null']),
            );
            if ($_POST["typeid"] == 1) {
                $obj_validate->validateparam = array(
                    array("input" => $_POST["fanli"], "require" => "true", "message" => $lang['goods_add_fanli_null']),
                    array("input" => $_POST["beout"], "require" => "true", "message" => $lang['goods_add_beout_null']),
                );
            }
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {
                $update_array = array();
                $update_array['gid'] = intval($_POST['gid']);
                $update_array['goods_name'] = trim($_POST['goods_name']);
                $update_array['typeid'] = intval($_POST['typeid']);
                $update_array['status'] = trim($_POST['article_show']);
                $update_array['content'] = trim($_POST['content']);
                $update_array['price'] = trim($_POST['price']);
                $update_array['fanli'] = trim($_POST['fanli']);
                $update_array['beout'] = trim($_POST['beout']);
                $update_array['thumb'] = trim($_POST['thumb']);
                $update_array['stock'] = trim($_POST['stock']);
                $update_array['ship_fee'] = trim($_POST['ship_fee']);
                $update_array['give'] = trim($_POST['give']);
                $update_array['group_id'] = trim($_POST['group_id']);

                if ($_POST['typeid'] != 6) {
                    $insert_array['group_id'] = 0;
                }
                
                $result = $model_goods->updates($update_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => $_POST['ref_url'],
                            'msg' => $lang['article_edit_back_to_list'],
                        ),
                        array(
                            'url' => 'index.php?act=goods&op=goods_edit&id=' . intval($_POST['gid']),
                            'msg' => $lang['article_edit_edit_again'],
                        ),
                    );
                    $this->log(L('article_edit_succ') . '[' . $_POST['article_title'] . ']', null);
                    showMessage($lang['article_edit_succ'], $url);
                } else {
                    showMessage($lang['article_edit_fail']);
                }
            }
        }

        $article_array = $model_goods->getOneGoods(intval($_GET['id']));
        if (empty($article_array)) {
            showMessage($lang['param_error']);
        }
        /**
         * 文章类别模型实例化
         */
        $model_class = Model('goods_class');
        /**
         * 父类列表，只取到第一级
         */
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)) {
            $unset_sign = false;
            foreach ($parent_list as $k => $v) {
                $parent_list[$k]['typename'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['typename'];
            }
        }

        $groupList = $this->group_model->getGroupList(array('g_status' => 1));

        Tpl::output('PHPSESSID', session_id());
        Tpl::output('parent_list', $parent_list);
        Tpl::output('article_array', $article_array);
        Tpl::output('groupList', $groupList);
        Tpl::setDirquna('system');
        Tpl::showpage('goods.edit');
    }

    /**
     * 商品图片上传
     */
    public function goods_pic_uploadOp()
    {
        /**
         * 上传图片
         */
        $subpath = date('Y-m-d', time());
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_ARTICLE_GOODS . '/' . $subpath);
        //生成缩略图，宽高依次如下
        $thumb_width = '400';
        $thumb_height = '400';
        $upload->set('thumb_width', $thumb_width);
        $upload->set('thumb_height', $thumb_height);
        $upload->set('ifremove', true);
        //略图名称扩展依次如下
        $upload->set('thumb_ext', '_thumb');
        //生成新图的扩展名为.jpg
        $upload->set('new_ext', 'jpg');
        $result = $upload->upfile('fileupload');
        if ($result) {
            $data['file_id'] = $upload->thumb_image;
            $data['file_name'] = $upload->thumb_image;
            $data['file_path'] = ATTACH_ARTICLE_GOODS . '/' . $subpath . '/' . $data['file_name'];
            $output = json_encode($data);
            echo $output;
            exit;
        } else {
            echo 'error';
            exit;
        }


    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        switch ($_GET['branch']) {
            /**
             * 删除文章图片
             */
            case 'del_file_upload':
                if (intval($_GET['file_id']) > 0) {
                    $model_upload = Model('upload');
                    /**
                     * 删除图片
                     */
                    $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
                    @unlink(BASE_UPLOAD_PATH . DS . ATTACH_ARTICLE . DS . $file_array['file_name']);
                    /**
                     * 删除信息
                     */
                    $model_upload->del(intval($_GET['file_id']));
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }
}
