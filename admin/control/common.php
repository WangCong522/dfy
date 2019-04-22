<?php
/**
 * 通用页面
 */



defined('InShopBN') or exit('Access Invalid!');

class commonControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    /**
     * 图片上传
     *
     */
    public function pic_uploadOp(){
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            $upload->set('thumb_width', 500);
            $upload->set('thumb_height',499);
            $upload->set('thumb_ext','_small');
            $upload->set('max_size',C('image_max_filesize')?C('image_max_filesize'):1024);
            $upload->set('ifremove',true);
            $upload->set('default_dir',$_GET['uploadpath']);

            if (!empty($_FILES['_pic']['tmp_name'])){
                $result = $upload->upfile('_pic');
                if ($result){
                    exit(json_encode(array('status'=>1,'url'=>UPLOAD_SITE_URL.'/'.$_GET['uploadpath'].'/'.$upload->thumb_image)));
                }else {
                    exit(json_encode(array('status'=>0,'msg'=>$upload->error)));
                }
            }
        }
    }

   
    /**
     * 图片裁剪
     *
     */
    public function pic_cutOp(){
        Language::read('admin_common');
		$lang = Language::getLangContent();
		import('function.thumb');
		if (chksubmit()){
			$thumb_width = $_POST['x'];
			$x1 = $_POST["x1"];
			$y1 = $_POST["y1"];
			$x2 = $_POST["x2"];
			$y2 = $_POST["y2"];
			$w = $_POST["w"];
			$h = $_POST["h"];
			$scale = $thumb_width/$w;
			$src = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_POST['url']);
			if (strpos($src, '..') !== false || strpos($src, BASE_UPLOAD_PATH) !== 0) {
			    exit();
			}
			if (!empty($_POST['filename'])){
// 				$save_file2 = BASE_UPLOAD_PATH.'/'.$_POST['filename'];
				$save_file2 = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_POST['filename']);
			}else{
				$save_file2 = str_replace('_small.','_sm.',$src);
			}
			$cropped = resize_thumb($save_file2, $src,$w,$h,$x1,$y1,$scale);
			@unlink($src);
			$pathinfo = pathinfo($save_file2);
			exit($pathinfo['basename']);
		}
		$save_file = str_ireplace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,$_GET['url']);
		$_GET['resize'] = $_GET['resize'] == '0' ? '0' : '1';
		Tpl::output('height',get_height($save_file));
		Tpl::output('width',get_width($save_file));
		Tpl::showpage('common.pic_cut','null_layout');
    }

    /**
     * 查询每月的周数组
     */
    public function getweekofmonthOp(){
        import('function.datehelper');
        $year = $_GET['y'];
        $month = $_GET['m'];
        $week_arr = getMonthWeekArr($year, $month);
        echo json_encode($week_arr);
        die;
    }

    /**
     * 设置常用菜单
     */
    public function common_operationsOp() {
        $type = $_GET['type'];
        $value = $_GET['value'];
        if (!in_array($type, array('add', 'del')) || empty($value)) {
            echo false;exit;
        }
        $quicklink = $this->getQuickLink();
        if (count($quicklink) == 10 && $type == 'add') {
            echo false;exit;
        }
        if ($type == 'add') {
            if (!empty($quicklink)) {
                array_push($quicklink, $value);
            } else {
                $quicklink[] = $value;
            }
        } else {
            $quicklink = array_diff($quicklink, array($value));
        }
        $quicklink = array_unique($quicklink);
        $quicklink = implode(',', $quicklink);

        $this->admin_info['qlink'] = $quicklink;
        $this->systemSetKey($this->admin_info);
        $result = Model('admin')->updateAdmin(array('admin_id' => $this->admin_info['id'], 'admin_quick_link' => $quicklink));
        if ($result) {
            echo true;exit;
        } else {
            echo false;exit;
        }
    }
    
    /**
     * 代办事项
     */
    public function pending_mattersOp() {
        $statistics  = $this->get_pending_matters();
        Tpl::output('statistics', $statistics);

        Tpl::showpage('common.pending_matters','null_layout');
    }
    
    /**
     * 代办事项ajax数据
     */
    public function ajax_pending_mattersOp() {
        $statistics  = $this->get_pending_matters();
        $count = 0;
        foreach ($statistics as $value) {
            $count += $value;
        }
        echo $count;exit();
    }
    
    /**
     * 代办事项数据查询
     * @return array
     */
    private function get_pending_matters() {
        //待审核账单
        $condition = array();
        $condition['ob_state'] = BILL_STATE_STORE_COFIRM;
        $statistics['check_billno'] = $model_bill->getOrderBillCount($condition);
        $statistics['check_vr_billno'] = $model_vr_bill->getOrderBillCount($condition);
        //待支付账单
        $condition = array();
        $condition['ob_state'] = BILL_STATE_SYSTEM_CHECK;
        $statistics['pay_billno'] = $model_bill->getOrderBillCount($condition);
        $statistics['pay_vr_billno'] = $model_vr_bill->getOrderBillCount($condition);
        // 平台客服
        $statistics['mall_consult'] = Model('mall_consult')->getMallConsultCount(array('is_reply' => 0));
        // 服务站
        $statistics['delivery_point'] = Model('delivery_point')->getDeliveryPointWaitVerifyCount(array());
       
        return $statistics;
    }
}
