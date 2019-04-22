<?php
/**
 * 文章分类
 */


defined('InShopBN') or exit('Access Invalid!');

class article_classControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('article_class');
    }

    public function indexOp()
    {
        $this->article_classOp();
    }

    /**
     * 文章管理
     */
    public function article_classOp()
    {
        $model_class = Model('article_class');
        /**
         * 列表
         */
        $tmp_list = $model_class->getTreeClassList(2);
        if (is_array($tmp_list)) {
            foreach ($tmp_list as $k => $v) {
                if ($v['ac_id'] == 1 || $v['ac_parent_id'] == 1) {
                    continue;
                }
                /**
                 * 判断是否有子类
                 */
                if ($tmp_list[$k + 1]['deep'] > $v['deep']) {
                    $v['have_child'] = 1;
                }
                $class_list[] = $v;
            }
        }

        Tpl::output('class_list', $class_list);
        Tpl::setDirquna('system');
        Tpl::showpage('article_class.index');
    }

    /**
     * 文章分类 新增
     */
    public function article_class_addOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('article_class');
        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["typename"], "require" => "true", "message" => $lang['article_class_add_name_null']),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                $insert_array = array();
                $insert_array['typename'] = trim($_POST['typename']);

                $result = $model_class->add($insert_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => 'index.php?act=article_class&op=article_class_add&ac_parent_id=' . intval($_POST['ac_parent_id']),
                            'msg' => $lang['article_class_add_class'],
                        ),
                        array(
                            'url' => 'index.php?act=article_class&op=article_class',
                            'msg' => $lang['article_class_add_back_to_list'],
                        )
                    );
                    $this->log(l('nc_add,article_class_index_class') . '[' . $_POST['ac_name'] . ']', 1);
                    showMessage($lang['article_class_add_succ'], $url);
                } else {
                    showMessage($lang['article_class_add_fail']);
                }
            }
        }

        $parent_list = $model_class->getTreeClassList(1);
        if (is_array($parent_list)) {
            foreach ($parent_list as $k => $v) {
                if ($v['ac_id'] == 1) {
                    unset($parent_list[$k]);
                    continue;
                }
                $parent_list[$k]['typename'] = str_repeat("&nbsp;", $v['deep'] * 2) . $v['typename'];
            }
        }

        Tpl::output('ac_parent_id', intval($_GET['ac_parent_id']));
        Tpl::output('parent_list', $parent_list);
        Tpl::setDirquna('system');
        Tpl::showpage('article_class.add');
    }

    /**
     * 文章分类编辑
     */
    public function article_class_editOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('article_class');

        if (chksubmit()) {
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input" => $_POST["typename"], "require" => "true", "message" => $lang['article_class_add_name_null']),
            );
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage($error);
            } else {

                $update_array = array();
                $update_array['id'] = intval($_POST['ac_id']);
                $update_array['typename'] = trim($_POST['typename']);
                $result = $model_class->updates($update_array);
                if ($result) {
                    $url = array(
                        array(
                            'url' => 'index.php?act=article_class&op=article_class',
                            'msg' => $lang['article_class_add_back_to_list'],
                        ),
                        array(
                            'url' => 'index.php?act=article_class&op=article_class_edit&ac_id=' . intval($_POST['ac_id']),
                            'msg' => $lang['article_class_edit_again'],
                        ),
                    );
                    $this->log(l('nc_edit,article_class_index_class') . '[' . $_POST['ac_name'] . ']', 1);
                    showMessage($lang['article_class_edit_succ'], 'index.php?act=article_class&op=article_class');
                } else {
                    showMessage($lang['article_class_edit_fail']);
                }
            }
        }

        $classInfo = $model_class->getOneClass(intval($_GET['id']));
        if (empty($classInfo)) {
            showMessage($lang['param_error']);
        }

        Tpl::output('classInfo', $classInfo);
        Tpl::setDirquna('system');
        Tpl::showpage('article_class.edit');
    }

    /**
     * 删除分类
     */
    public function article_class_delOp()
    {
        $lang = Language::getLangContent();
        $model_class = Model('article_class');
        if (intval($_GET['id']) > 0) {
            $array = array(intval($_GET['id']));

            $del_array = $model_class->getChildClass($array);
            if (is_array($del_array)) {
                foreach ($del_array as $k => $v) {
                    $model_class->del($v['id']);
                }
            }
            $this->log(l('nc_add,article_class_index_class') . '[ID:' . intval($_GET['id']) . ']', 1);
            showMessage($lang['article_class_index_del_succ'], 'index.php?act=article_class&op=article_class');
        } else {
            showMessage($lang['article_class_index_choose'], 'index.php?act=article_class&op=article_class');
        }
    }

    /**
     * ajax操作
     */
    public function ajaxOp()
    {
        switch ($_GET['branch']) {
            /**
             * 分类：验证是否有重复的名称
             */
            case 'article_class_name':
                $model_class = Model('article_class');
                $class_array = $model_class->getOneClass(intval($_GET['id']));

                $condition['typename'] = trim($_GET['value']);


                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)) {
                    $update_array = array();
                    $update_array['id'] = intval($_GET['id']);
                    $update_array['typename'] = trim($_GET['value']);
                    $model_class->updates($update_array);
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
            /**
             * 分类： 排序 显示 设置
             */
            case 'article_class_sort':
                $model_class = Model('article_class');
                $update_array = array();
                $update_array['ac_id'] = intval($_GET['id']);
                $update_array[$_GET['column']] = trim($_GET['value']);
                $result = $model_class->updates($update_array);
                echo 'true';
                exit;
                break;
            /**
             * 分类：添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_class_name':
                $model_class = Model('article_class');
                $condition['ac_name'] = trim($_GET['ac_name']);
                $condition['ac_parent_id'] = intval($_GET['ac_parent_id']);
                $condition['no_ac_id'] = intval($_GET['ac_id']);
                $class_list = $model_class->getClassList($condition);
                if (empty($class_list)) {
                    echo 'true';
                    exit;
                } else {
                    echo 'false';
                    exit;
                }
                break;
        }
    }

    /**     * 异步调用分类列表     */
    public function get_goods_class_xmlOp()
    {
        $lang = Language::getLangContent();
        $model_goods_class = Model('article_class');
        $condition = array();
        if (!empty($_POST['qtype'])) {
            $condition['typeid'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['typename'] = $_POST['query'];
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
                $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$v['id']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=article_class&op=article_class_edit&id={$v['id']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
                $list['id'] = $v['id'];
                $list['typename'] = $v['typename'];
                $data['list'][$v['id']] = $list;
            }
        }
        exit(Tpl::flexigridXML($data));
    }
}
