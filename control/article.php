<?php
/**
 * 文章
 ***/


defined('InShopBN') or exit('Access Invalid!');

class articleControl extends BaseMemberControl
{
    public function __construct()
    {
        parent::__construct();
        Tpl::output('Message_index_active', "active");
    }

    /**
     * 默认进入页面
     */
    public function indexOp()
    {
        exit;
    }

    /**
     * 文章列表显示页面
     */
    public function articleOp()
    {
        /**
         * 读取语言包
         */
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        if (empty($_GET['id'])) {
            $_GET['id'] = 3;
            //showMessage($lang['para_error'],'','html','error');//'缺少参数:文章类别编号'
        }
        $article_model = Model('article');
        $article_class_model = Model('article_class');
        $article_class = $article_class_model->getOneClass($_GET['id']);
        $condition = array();
        $condition['typeid'] = $_GET['id'];
        $condition['status'] = '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        $article_list = $article_model->getArticleList($condition, $page);
        Tpl::output('artList', $article_list);
        Tpl::output('article_class', $article_class);
        Tpl::output('show_page', $page->show());
        Tpl::output('Message_Gonggao_selected', "active");

        Tpl::showpage('article_list');
    }

    /**
     * 单篇文章显示页面
     */
    public function showOp()
    {
        /**
         * 读取语言包
         */
        Language::read('home_article_index');
        $lang = Language::getLangContent();
        if (empty($_GET['id'])) {
            showMessage($lang['para_error'], '', 'html', 'error');//'缺少参数:文章编号'
        }
        /**
         * 根据文章编号获取文章信息
         */
        $article_model = Model('article');
        $article = $article_model->getOneArticle(intval($_GET['id']));
        if (empty($article) || !is_array($article) || $article['status'] == '0') {
            showMessage($lang['article_show_not_exists'], '', 'html', 'error');//'该文章并不存在'
        }
        Tpl::output('article', $article);

        /**
         * 根据类别编号获取文章类别信息
         */
        $article_class_model = Model('article_class');
        $condition = array();
        $article_class = $article_class_model->getOneClass($article['typeid']);
        if (empty($article_class) || !is_array($article_class)) {
            showMessage($lang['article_show_delete'], '', 'html', 'error');//'该文章已随所属类别被删除'
        }
        Tpl::output('Message_Gonggao_selected', "active");
        Tpl::output('article_class', $article_class);
        Tpl::showpage('article_show');
    }

    public function sendOp()
    {
        Tpl::output('Message_index_selected', "active");
        Tpl::showpage('article.send');
    }

    public function DoSendOp()
    {
        $type = $_REQUEST['type'];
        $title = $_REQUEST['title'];
        $username = $_REQUEST['username'];
        $content = $_REQUEST['content'];

        $user = Model('member');
        $message = Model('message');

        if ($username != '') {
            $where = array('username' => $username);
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

    public function messageFjOp()
    {
        $message = Model("message");

        $condition = array();
        //$condition['typeid']	= $_GET['id'];
        //$condition['status']	= '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        //$article_list	= $article_model->getArticleList($condition,$page);

        $where = array('fromid' => $this->userid);

        $list = $message->getMsgList($where, $page);
        Tpl::output('list', $list);

        Tpl::output('show_page', $page->show());

        Tpl::output('Message_MessageFj_selected', "active");
        Tpl::showpage('article.messageFj');
    }

    public function GetMessageFjOp()
    {
        $id = $_REQUEST['id'] + 0;
        $message = Model("message");

        $where = array('id' => $id);
        $info = $message->getMessage($where);
        exit(json_encode(array('info' => $info, 'status' => 1)));
    }

    public function messageSjOp()
    {
        $message = Model("message");

        $condition = array();
        //$condition['typeid']	= $_GET['id'];
        //$condition['status']	= '1';
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');
        //$article_list	= $article_model->getArticleList($condition,$page);

        $where = array('toid' => $this->userid);

        $list = $message->getMsgList($where, $page);
        Tpl::output('list', $list);

        Tpl::output('Message_MessageSj_selected', "active");
        Tpl::output('show_page', $page->show());
        Tpl::showpage('article.messageSj');
    }

    public function GetMessageSjOp()
    {
        $id = $_REQUEST['id'] + 0;
        $message = Model("message");

        $where = array('id' => $id, 'toid' => $this->userid);
        $info = $message->getMessage($where);
        $message->editMessage($where, array('status' => '0'));

        exit(json_encode(array('info' => $info, 'status' => 1)));
    }

}

?>
