<?php
/**
 * 设置
 *
 */


defined('InShopBN') or exit('Access Invalid!');

class reg_csControl extends SystemControl
{
    private $links = array(
        array('url' => 'act=upload&op=param', 'lang' => 'reg_cs_set'),
    );

    public function __construct()
    {
        parent::__construct();
        Language::read('setting');
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
        if (chksubmit()) {
            $model_setting = Model('setting');
            $data = array();
            foreach ($_POST as $key => $val) {
                $data[$key] = trim($val);
            }
            $result = $model_setting->updateSetting($data);
            if ($result) {
                $this->log(L('nc_edit,reg_cs_set'), 1);
                showMessage(L('nc_common_save_succ'));
            } else {
                $this->log(L('nc_edit,reg_cs_set'), 0);
                showMessage(L('nc_common_save_fail'));
            }
        }

        //获取默认图片设置属性
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();

        Tpl::output('setting', $list_setting);

        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->links, 'param'));
        //
        Tpl::setDirquna('system');
        Tpl::showpage('setting.reg_cs');
    }
}
