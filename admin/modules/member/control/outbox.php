<?php
/**
 * 消息通知
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');
class outboxControl extends SystemControl{
    private $links = array(
        array('url'=>'act=message&op=seller_tpl', 'lang'=>'seller_tpl'),
        array('url'=>'act=message&op=message_tpl', 'lang'=>'message_tpl'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting,message');
    }

    public function indexOp() {
        $this->messageOp();
    }

    /**
     * 商家消息模板
     */
    public function messageOp() {
		Tpl::setDirquna('member');
        Tpl::showpage('outbox.index');
    }
	
	/**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_message = Model('message');
        //$message_grade = $model_message->getmessageGradeArr();
        $condition = array('formid'=>'A');
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id','title',
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $message_list = $model_message->getMessageList($condition, '*', $page, $order);

        $data = array();
        $data['now_page'] = $model_message->shownowpage();
        $data['total_num'] = $model_message->gettotalnum();
        foreach ($message_list as $value) {
            $param = array();
            $param['operation'] = "<a class='btn blue' href='index.php?act=outbox&op=read&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
            $param['title'] = $value['title'];
			
			$UserInfo = Model("member")->getMemberInfoByID($value['toid'],'username');
			$param['toname'] = $UserInfo['username'];
			$param['date'] = date("Y-m-d H:i:s",$value['addtime']);
            $param['is_read'] = $value['status'] ==  '0' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
	
	/**
     * read
     */
    public function readOp(){
        $lang    = Language::getLangContent();
        $model_message = Model('message');

        $message_array = $model_message->getMessageById(intval($_GET['id']));
        if (empty($message_array)){
            showMessage($lang['param_error']);
        }
	   $UserInfo = Model("member")->getMemberInfoByID($message_array['toid'],'username');
	   $message_array['who'] = $UserInfo['username'];

        Tpl::output('PHPSESSID',session_id());
        Tpl::output('message_array',$message_array);
		Tpl::setDirquna('member');
        Tpl::showpage('outbox.read');
    }
	//
}
