<?php
/**
 * 清理缓存
 */


defined('InShopBN') or exit('Access Invalid!');

class cacheControl extends SystemControl
{
    protected $cacheItems = array(
        'setting',          // 基本缓存
        'admin_menu',       // 后台菜单
    );

    public function __construct() {
        parent::__construct();
        Language::read('cache');
    }

    public function indexOp() {
        $this->clearOp();
    }

    /**
     * 清理缓存
     */
    public function clearOp() {
        if (!chksubmit()) {
			Tpl::setDirquna('system');
            Tpl::showpage('cache.clear');
            return;
        }

        $lang = Language::getLangContent();

        // 清理所有缓存
        if ($_POST['cls_full'] == 1) {
            foreach ($this->cacheItems as $i) {
                dkcache($i);
            }

        } else {
            $todo = (array) $_POST['cache'];

            foreach ($this->cacheItems as $i) {
                if (in_array($i, $todo)) {
                    dkcache($i);
                }
            }
        }

        $this->log(L('cache_cls_operate'));
        showMessage($lang['cache_cls_ok']);
    }
}
