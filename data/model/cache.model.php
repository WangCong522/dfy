<?php
/**
 * 缓存操作
 */
defined('InShopBN') or exit('Access Invalid!');
class cacheModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function call($method){
        $method = '_'.strtolower($method);
        if (method_exists($this,$method)){
            return $this->$method();
        }else{
            return false;
        }
    }

    /**
     * 基本设置
     *
     * @return array
     */
    private function _setting(){
        $list =$this->table('setting')->limit(false)->select();
        $array = array();
        foreach ((array)$list as $v) {
            $array[$v['name']] = $v['value'];
        }
        unset($list);
        return $array;
    }

    /**
     * 自定义导航
     *
     * @return array
     */
    private function _nav(){
        $list = $this->table('navigation')->order('nav_sort')->limit(false)->select();
        if (!is_array($list)) return null;
        return $list;
    }

    /**
     * Circle Member Level
     *
     * @return array
     */
    private function _circle_level(){
        $list = $this->table('circle_mldefault')->limit(false)->select();

        if (!is_array($list)) return null;
        $array = array();
        foreach ($list as $val){
            $array[$val['mld_id']] = $val;

        }
        return $array;
    }

    private function _admin_menu() {
        Language::read('layout');
        $lang = Language::getLangContent();
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_SYSTEM.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_SYSTEM.'/include/menu.php');
        }
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_MEMBER.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_MEMBER.'/include/menu.php');
        }
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_FIN.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_FIN.'/include/menu.php');
        }
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_STAT.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_STAT.'/include/menu.php');
        }
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_MICEOSHOP.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_MICEOSHOP.'/include/menu.php');
        }
        if (file_exists(BASE_PATH.DS.ADMIN_MODULES_MOBILE.'/include/menu.php')) {
            require(BASE_PATH.DS.ADMIN_MODULES_MOBILE.'/include/menu.php');
        }
        return $_menu;
    }
}
