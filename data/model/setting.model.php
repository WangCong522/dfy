<?php
/**
 * 系统设置内容
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class settingModel extends Model
{
    public function __construct()
    {
        parent::__construct('setting');
    }

    /**
     * 读取系统设置信息
     *
     * @param string $name 系统设置信息名称
     * @return array 数组格式的返回结果
     */
    public function getRowSetting($name)
    {
        $param = array();
        $param['table'] = 'setting';
        $param['where'] = "name='" . $name . "'";
        $result = Db::select($param);
        if (is_array($result) and is_array($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * 读取系统设置列表
     *
     * @param
     * @return array 数组格式的返回结果
     */
    public function getListSetting()
    {
        $param = array();
        $param['table'] = 'setting';
        $result = Db::select($param);
        /**
         * 整理
         */
        if (is_array($result)) {
            $list_setting = array();
            foreach ($result as $k => $v) {
                $list_setting[$v['name']] = $v['value'];
            }
        }
        return $list_setting;
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updateSetting($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            foreach ($param as $k => $v) {
                $tmp = array();
                $specialkeys_arr = array('statistics_code');
                $tmp['value'] = (in_array($k, $specialkeys_arr) ? htmlentities($v, ENT_QUOTES) : $v);
                $where = " name = '" . $k . "'";
                $result = Db::update('setting', $tmp, $where);
                if ($result !== true) {
                    return $result;
                }
            }
            dkcache('setting');
            // @unlink(BASE_DATA_PATH.DS.'cache'.DS.'setting.php');
            return true;
        } else {
            return false;
        }
    }

    /**
     * 清空数据
     */

    public function wipeData()
    {
        Db::query("TRUNCATE TABLE user_day");
        Db::query("TRUNCATE TABLE user_lays");
        Db::query("TRUNCATE TABLE user_message");
        Db::query("TRUNCATE TABLE user_net");
        Db::query("TRUNCATE TABLE user_parent");
        Db::query("TRUNCATE TABLE user_record");
        Db::query("TRUNCATE TABLE user_trans");
        Db::query("TRUNCATE TABLE user_users");
        Db::query("TRUNCATE TABLE user_bonuslaiyuan");
        Db::query("TRUNCATE TABLE user_biz");
        Db::query("TRUNCATE TABLE user_bonuslog");
        Db::query("TRUNCATE TABLE user_remit");
        Db::query("TRUNCATE TABLE user_goods_order");
        Db::query("TRUNCATE TABLE user_iplist");
        Db::query("TRUNCATE TABLE user_jiang");
        Db::query("TRUNCATE TABLE user_token");
        Db::query("TRUNCATE TABLE user_layerlist");
        Db::query("INSERT INTO `user_users` (`id`, `username`, `password`, `status`, `time`, `jy_pwd`, `login_id`, `group_id`, `level_id`, `sn`, `num`, `login_status`, `state`, `login_time`, `overdue_time`, `dian_balance`, `ji_balance`, `bao_balance`, `zhang_balance`, `sheng_balance`, `xu_balance`, `yun_balance`, `zhu_balance`, `name`, `problem`, `answer`, `idcard`, `address`, `tel`, `qq`, `email`, `sex`, `ssid`, `ssuid`, `ssname`, `rid`, `rname`, `lsk`, `pid`, `paynetfee`,`is_biz`) VALUES ('1', 'admin', '96e79218965eb72c92a549dd5a330112', '1', '0', 'e3ceb5881a0a1fdaad01296d7554868d', '1', '4', '1', NULL, '6000', '1', 'low', '0', NULL, '500', '0.00', '50000.00', '0.00', '0', '0', '0', '0', 'admin', NULL, NULL, NULL, '测试地址', '15971871234', NULL, 'admin@888.com', '1', '0', '0', NULL, '0', NULL, '6800.00', '0', '0','1')
");
        Db::query("INSERT INTO `user_biz` (`id`, `uid`, `username`, `addtime`, `count`, `total`, `status`) VALUES ('1', '1', 'admin', '1479799386', '0', '0.00', '1')");
        Db::query("INSERT INTO `user_net` (`id`, `uid`, `l_id`, `r_id`, `pid`, `l_count`, `r_count`, `status`, `lay_num`, `area`, `all_l_count`, `all_r_count`) VALUES ('1', '1', '0', '0', '0', '0.00', '0.00', '1', '0', NULL, '0.00', '0.00')");
        Db::query("INSERT INTO `user_parent` (`uid`, `net_id`, `parent_id`, `parent`) VALUES ('1', '1', '0', ',');
");
    }

}
