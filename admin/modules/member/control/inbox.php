<?php
/**
 * 消息通知
 */

//use ShopBN\Tpl;

defined('InShopBN') or exit('Access Invalid!');
class inboxControl extends SystemControl{
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
        Tpl::showpage('inbox.index');
    }
	
	/**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_message = Model('message');
        //$message_grade = $model_message->getmessageGradeArr();
        $condition = array('toid'=>'A');
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
            $param['operation'] = "<a class='btn blue' href='index.php?act=inbox&op=reply&id=" . $value['id'] . "'><i class='fa fa-pencil-square-o'></i>回复</a>";
            $param['title'] = $value['title'];
			$param['formname'] = $value['formname'];
			$param['date'] = date("Y-m-d H:i:s",$value['addtime']);
            $param['is_read'] = $value['status'] ==  '0' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
			$param['is_reply'] = $value['isreply'] ==  '1' ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
			//$param['time'] = date("Y-m-d H:i:s",$value['time']);
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
	
	/**
     * 回复
     */
    public function replyOp(){
        $lang    = Language::getLangContent();
        $model_message = Model('message');

        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["title"], "require"=>"true", "message"=>'标题不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['mid'] = intval($_POST['id']);
                $update_array['title'] = trim($_POST['title']);
                $update_array['formid'] = 'A';
				$update_array['formname'] = '管理员';
				$update_array['totype'] = '0';
                $update_array['status'] =1;
                $update_array['content'] = trim($_POST['content']);
				$update_array['addtime'] = time();
				$update_array['toid'] =  intval($_POST['toid']);

                $result = $model_message->addMessage($update_array);
                if ($result){
                    $model_message->editMessage(array('id'=>$update_array['mid']),array('isreply'=>1));
					$url = array(
                        array(
                            'url'=>$_POST['ref_url'],
                            'msg'=>'返回',
                        ),
                    );
                    $this->log('回复来信：'.'['.$_POST['title'].']',null);
                    showMessage('回复成功',$url);
                }else {
                    showMessage('回复失败');
                }
            }
        }

        $message_array = $model_message->getMessageById(intval($_GET['id']));
        if (empty($message_array)){
            showMessage($lang['param_error']);
        }
		
		$model_message->editMessage(array('id'=>$message_array['id']),array('status'=>0));
		$UserInfo = Model("member")->getMemberInfoByID($message_array['formid'],'username');
		$message_array['who'] = $UserInfo['username'];

        Tpl::output('PHPSESSID',session_id());
        Tpl::output('message_array',$message_array);
		Tpl::setDirquna('member');
        Tpl::showpage('inbox.reply');
    }


}
