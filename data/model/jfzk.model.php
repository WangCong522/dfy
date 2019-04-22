<?php
/**
 *积分折扣
 */
defined('InShopBN') or exit('Access Invalid!');

class jfzkModel extends Model
{
    private $net_model;
    private $biz_model;
    private $layerlist_model;


    public function __construct()
    {
        parent::__construct('jfzk');
    }

    /**
     * 积分折扣列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getJfzkList($condition = array(), $field = '*', $page = null, $order = 'id desc', $limit = '')
    {
        return $this->table('jfzk')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }


    /**
     * 详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getJfzkInfo($condition, $field = '*', $master = false)
    {
        return $this->table('jfzk')->field($field)->where($condition)->master($master)->find();
    }



    //后台启用
    public function activation_jfzk($id)
    {
        $jihuouserinfo = $this->getJfzkInfoByID($id);

        if ($jihuouserinfo['status'] == 1) {
            showMessage('信息已启用！');
        }
        $re = $update = $this->table('jfzk')->where(array('id' => $id))->update(array('status' => 1));
        
        return $re;
    }

    //后台假删
    public function jfzkdel_jfzk($id)
    {
        $jihuouserinfo = $this->getJfzkInfoByID($id);

        if ($jihuouserinfo['status'] == 0) {
            showMessage('信息已删除！');
        }
        $re = $update = $this->table('jfzk')->where(array('id' => $id))->update(array('status' => 0));
        
        return $re;
    }

    //彻底删除
    public function jfzkis_del_jfzk($id)
    {
        $re = $update = $this->table('jfzk')->where(array('id' => $id))->delete();
        return $re;
    }

    //等级一条
    public function getUserGroudMsg($group_id){
        return $this->table('jfzk')->where(array('groud_id'=>$group_id))->find();
    }

    //增加
    public function addJFZKMsg($param)
    {
        if (empty($param)) {
            return false;
        }

        $insert_id = $this->table('jfzk')->insert($param);
        return $insert_id;
    }

    //后台假删
    public function jfzkedit_jfzk($id,$data)
    {
        $re = $update = $this->table('jfzk')->where(array('id' => $id))->update($data);
        
        return $re;
    }

    /**
     * 取得积分详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getJfzkInfoByID($id, $fields = '*')
    {
        $jfzk_info = $this->getJfzkInfo(array('id' => $id), '*', true);
        return $jfzk_info;
    }

}
