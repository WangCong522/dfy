<?php
/**
 * 文章管理
*
 */

defined('InShopBN') or exit('Access Invalid!');
class articleControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('article');
    }

    public function indexOp() {
        $this->articleOp();
    }

    /**
     * 文章管理
     */
    public function articleOp(){
        //分类列表
        $model_class = Model('article_class');
        $parent_list = $model_class->getTreeClassList(2);
        if (is_array($parent_list)){
            $unset_sign = false;
            foreach ($parent_list as $k => $v){
                $parent_list[$k]['ac_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['ac_name'];
            }
        }
        Tpl::output('parent_list',$parent_list);
		Tpl::setDirquna('system');
        Tpl::showpage('article.index');
    }

    public function deleteOp() {
        $model_article = Model('article');
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',',trim($_GET['del_id'],','));
            foreach ($_GET['del_id'] as $k => $v){
                $v = intval($v);
                $model_article->del($v);
            }
            $this->log(L('article_index_del_succ').'[ID:'.implode(',',$_GET['del_id']).']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
    /**
     * 异步调用文章列表
     */
    public function get_xmlOp(){
        $lang   = Language::getLangContent();
        $model_article = Model('article');
        $condition = array();
        if (!empty($_POST['qtype'])) {
            $condition['typeid'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['like_title'] = $_POST['query'];
        }
        if (!empty($_POST['sortname']) && in_array($_POST['sortorder'],array('asc','desc'))) {
            $condition['order'] = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $condition['order'] = ltrim($condition['order'].',aid desc',',');
        $page   = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $article_list = $model_article->getArticleList($condition,$page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($article_list)){
            $model_class = Model('article_class');
            unset($condition['order']);
            $class_list = $model_class->getClassList($condition);
            $tmp_class_name = array();
            if (is_array($class_list)){
                foreach ($class_list as $k => $v){
                    $tmp_class_name[$v['id']] = $v['typename'];
                }
            }

            foreach ($article_list as $k => $v){
                $list = array();
                $list['operation'] = "<a class='btn red' onclick=\"fg_delete({$v['aid']})\"><i class='fa fa-trash-o'></i>删除</a><a class='btn blue' href='index.php?act=article&op=article_edit&id={$v['aid']}'><i class='fa fa-pencil-square-o'></i>编辑</a>";
               // $list['article_sort'] = $v['article_sort'];
                $list['title'] = $v['title'];
                if (@array_key_exists($v['typeid'],$tmp_class_name)){
                    $v['typename'] = $tmp_class_name[$v['typeid']];
                }
                $list['typename'] = $v['typename'];

                $list['article_show'] = $v['status'] == 0 ? '<span class="no"><i class="fa fa-ban"></i>否</span>' : '<span class="yes"><i class="fa fa-check-circle"></i>是</span>';
                $list['addtime'] = date('Y-m-d H:i:s',$v['addtime']);
                $data['list'][$v['aid']] = $list;
            }
        }
		
        exit(Tpl::flexigridXML($data));
    }

    /**
     * 文章添加
     */
    public function article_addOp(){
        $lang   = Language::getLangContent();
        $model_article = Model('article');
        /**
         * 保存
         */
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["title"], "require"=>"true", "message"=>$lang['article_add_title_null']),
                array("input"=>$_POST["typeid"], "require"=>"true", "message"=>$lang['article_add_class_null']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {

                $insert_array = array();
                $insert_array['title'] = trim($_POST['title']);
                $insert_array['typeid'] = intval($_POST['typeid']);
                $insert_array['status'] = trim($_POST['article_show']);
                $insert_array['content'] = trim($_POST['content']);
                $insert_array['addtime'] = time();
                $result = $model_article->add($insert_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?act=article&op=article',
                            'msg'=>"{$lang['article_add_tolist']}",
                        ),
                        array(
                            'url'=>'index.php?act=article&op=article_add&ac_id='.intval($_POST['typeid']),
                            'msg'=>"{$lang['article_add_continueadd']}",
                        ),
                    );
                    $this->log(L('article_add_ok').'['.$_POST['article_title'].']',null);
                    showMessage("{$lang['article_add_ok']}",$url);
                }else {
                    showMessage("{$lang['article_add_fail']}");
                }
            }
        }
        /**
         * 分类列表
         */
        $model_class = Model('article_class');
        $parent_list = $model_class->getTreeClassList(0);

        /**
         * 模型实例化
         */

        Tpl::output('PHPSESSID',session_id());
        Tpl::output('typeid',intval($_GET['typeid']));
        Tpl::output('parent_list',$parent_list);
		Tpl::setDirquna('system');
        Tpl::showpage('article.add');
    }

    /**
     * 文章编辑
     */
    public function article_editOp(){
        $lang    = Language::getLangContent();
        $model_article = Model('article');

        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["title"], "require"=>"true", "message"=>$lang['article_add_title_null']),
                array("input"=>$_POST["typeid"], "require"=>"true", "message"=>$lang['article_add_class_null']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['aid'] = intval($_POST['aid']);
                $update_array['title'] = trim($_POST['title']);
                $update_array['typeid'] = intval($_POST['typeid']);
                $update_array['status'] = trim($_POST['article_show']);
                $update_array['content'] = trim($_POST['content']);

                $result = $model_article->updates($update_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>$_POST['ref_url'],
                            'msg'=>$lang['article_edit_back_to_list'],
                        ),
                        array(
                            'url'=>'index.php?act=article&op=article_edit&article_id='.intval($_POST['article_id']),
                            'msg'=>$lang['article_edit_edit_again'],
                        ),
                    );
                    $this->log(L('article_edit_succ').'['.$_POST['article_title'].']',null);
                    showMessage($lang['article_edit_succ'],$url);
                }else {
                    showMessage($lang['article_edit_fail']);
                }
            }
        }

        $article_array = $model_article->getOneArticle(intval($_GET['id']));
        if (empty($article_array)){
            showMessage($lang['param_error']);
        }
        /**
         * 文章类别模型实例化
         */
        $model_class = Model('article_class');
        /**
         * 父类列表，只取到第一级
         */
        $parent_list = $model_class->getTreeClassList(0);
 

        Tpl::output('PHPSESSID',session_id());
        Tpl::output('parent_list',$parent_list);
        Tpl::output('article_array',$article_array);
		Tpl::setDirquna('system');
        Tpl::showpage('article.edit');
    }
    /**
     * 文章图片上传
     */
    public function article_pic_uploadOp(){
        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload->set('default_dir',ATTACH_ARTICLE);
        $result = $upload->upfile('fileupload');
        if ($result){
            $_POST['pic'] = $upload->file_name;
        }else {
            echo 'error';exit;
        }
        /**
         * 模型实例化
         */
        $model_upload = Model('upload');
        /**
         * 图片数据入库
         */
        $insert_array = array();
        $insert_array['file_name'] = $_POST['pic'];
        $insert_array['upload_type'] = '1';
        $insert_array['file_size'] = $_FILES['fileupload']['size'];
        $insert_array['upload_time'] = time();
        $insert_array['item_id'] = intval($_POST['item_id']);
        $result = $model_upload->add($insert_array);
        if ($result){
            $data = array();
            $data['file_id'] = $result;
            $data['file_name'] = $_POST['pic'];
            $data['file_path'] = $_POST['pic'];
            /**
             * 整理为json格式
             */
            $output = json_encode($data);
            echo $output;
        }

    }
    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            /**
             * 删除文章图片
             */
            case 'del_file_upload':
                if (intval($_GET['file_id']) > 0){
                    $model_upload = Model('upload');
                    /**
                     * 删除图片
                     */
                    $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_ARTICLE.DS.$file_array['file_name']);
                    /**
                     * 删除信息
                     */
                    $model_upload->del(intval($_GET['file_id']));
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }
}
