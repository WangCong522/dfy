<?php
/**
 * 清理缓存
 */


defined('InShopBN') or exit('Access Invalid!');

class wipe_dataControl extends SystemControl
{
    protected $cacheItems = array(
        'setting',          // 基本缓存
        'admin_menu',       // 后台菜单
    );

    private $setting_model;

    public function __construct()
    {
        parent::__construct();
        Language::read('cache');
        $this->setting_model = Model('setting');
    }

    public function indexOp()
    {
        $this->clearOp();
    }

    /**
     * 清理缓存
     */
    public function clearOp()
    {
        if (!chksubmit()) {
            Tpl::setDirquna('system');
            Tpl::showpage('wipe_data');
            return;
        }
        $lang = Language::getLangContent();
        // 清理所有数据
        $this->setting_model->wipeData();
        showMessage('清空成功');
    }
}
