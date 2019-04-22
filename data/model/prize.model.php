<?php
/**
 * 会员模型
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class prizeModel extends Model
{
    private $net_model;
    private $biz_model;
    private $layerlist_model;

    public function __construct()
    {
        parent::__construct('member');
        $this->net_model = Model('net');
        $this->biz_model = Model('biz');
        $this->layerlist_model = Model('layerlist');
    }

    /**
     * 奖品列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getPrizeList($condition, $page = null, $order = 'id desc', $field = '*')
    {
        return $this->table('prize')->field($field)->where($condition)->page($page)->order($order)->select();
    }


    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false)
    {
        return $this->table('users')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*')
    {
        $member_info = $this->getMemberInfo(array('id' => $member_id), '*', true);
        return $member_info;
    }


    //后台启用
    public function activation_prize($id)
    {
        $jihuouserinfo = $this->getPrizeInfoByID($id);

        if ($jihuouserinfo['status'] == 1) {
            showMessage('信息已启用！');
        }
        $re = $this->table('prize')->where(array('id' => $id))->update(array('status' => 1));
        return $re;
    }

    //后台删除
    public function member_del_prize($id)
    {
        $jihuouserinfo = $this->getPrizeInfoByID($id);

        if ($jihuouserinfo['status'] == 0) {
            showMessage('信息已删除！');
        }
        $re =  $this->table('prize')->where(array('id' => $id))->update(array('status' => 0));
        return $re;
    }

    //后台真删除
    public function cedi_del_prize($id)
    {
        $jihuouserinfo = $this->getPrizeInfoByID($id);
        $re = $this->table('prize')->where(array('id' => $id))->delete();
        return $re;
    }

    //添加
    public function addPrizeMsg($data)
    {
        if (empty($data)) {
            return false;
        }

        $insert_id = $this->table('prize')->insert($data);
        return $insert_id;;
    }


    /**
     * 取得奖品详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getPrizeInfoByID($id, $fields = '*')
    {
        $prize_info = $this->getPrizeInfo(array('id' => $id), '*', true);
        return $prize_info;
    }

        /**
     * 奖品详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPrizeInfo($condition, $field = '*', $master = false)
    {
        return $this->table('prize')->field($field)->where($condition)->master($master)->find();
    }


    /**
     * 取得当前用户待领取奖品（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getToReceivePrizeInfo($group_id)
    {
        return $this->table('prize')->where(array('group_id'=>$group_id))->select();
    }

}