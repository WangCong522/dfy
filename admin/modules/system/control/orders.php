<?php
/**
 * 文章管理
 *
 */

defined('InShopBN') or exit('Access Invalid!');

class ordersControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Language::read('goods');
    }

    public function indexOp()
    {
        $this->ordersOp();
    }

    /**
     * 订单管理
     */
    public function ordersOp()
    {
        Tpl::setDirquna('system');
        Tpl::showpage('orders.index');
    }

    public function deleteOp()
    {
        $model_article = Model('goods');
        if (preg_match('/^[\d,]+$/', $_GET['del_id'])) {
            $_GET['del_id'] = explode(',', trim($_GET['del_id'], ','));
            foreach ($_GET['del_id'] as $k => $v) {
                $v = intval($v);
                $model_article->del($v);
            }
            $this->log(L('article_index_del_succ') . '[ID:' . implode(',', $_GET['del_id']) . ']', null);
            exit(json_encode(array('state' => true, 'msg' => '删除成功')));
        } else {
            exit(json_encode(array('state' => false, 'msg' => '删除失败')));
        }
    }

    /**
     * 发货
     */
    public function deliver_goodsOp()
    {
        $model_goods_order = Model('goods_order');
        $order_id = $_GET['order_id'];
        $express = trim($_GET['express']);
        $orderInfo = $model_goods_order->getOneGoodsOrder($order_id);
        if ($orderInfo) {
            if ($orderInfo['status'] == 0) {
                $result['state'] = 0;
                $result['msg'] = '未支付订单不能发货！';
            } else {
                $data = array(
                    'order_id' => $order_id,
                    'express' => $express,
                    'status' => 2
                );
                $check = $model_goods_order->updates($data);
                if ($check) {
                    $result['state'] = 1;
                    $result['msg'] = '操作成功！';
                } else {
                    $result['state'] = 0;
                    $result['msg'] = '操作失败！';
                }
            }
        } else {
            $result['state'] = 0;
            $result['msg'] = '订单不存在！';
        }
        echo json_encode($result);
    }
    
    /**
     * 异步调用订单列表
     */
    public function get_xmlOp()
    {
        $lang = Language::getLangContent();
        $model_goods_order = Model('goods_order');
        $condition = array();
        if (!empty($_POST['qtype'])) {
            $condition['typeid'] = intval($_POST['qtype']);
        }
        if (!empty($_POST['query'])) {
            $condition['like_title'] = $_POST['query'];
        }
        if (!empty($_POST['sortname']) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $condition['order'] = ltrim($condition['order'] . ',order_id desc', ',');
        $page = new Page();
        $page->setEachNum(intval($_POST['rp']));
        $page->setStyle('admin');
        $order_list = $model_goods_order->getGoodsOrderList($condition, $page);
        $data = array();
        $data['now_page'] = $page->get('now_page');
        $data['total_num'] = $page->get('total_num');
        if (is_array($order_list)) {
            foreach ($order_list as $k => $v) {
                $list = array();
                $list['operation'] =
                    $v['status'] == 2 ? '<span class="no"><i class="fa fa-check"></i>已发货</span>' : "<a class='btn red' target='_self' onclick=\"deliver_goods({$v['order_id']})\"><i class='fa fa-truck'></i>发货</a>";
                $list['is_actived'] = $v['status'] == '1' || $v['status'] == '2' ? '<span class="yes"><i class="fa fa-check-circle"></i>已支付</span>' : '<span class="no"><i class="fa fa-ban"></i>未支付</span>';
                $list['order_id'] = $v['order_id'];
                $list['username'] = $v['username'];
                $list['goods_name'] = $v['goods_name'];
                $list['unit_price'] = $v['unit_price'];
                $list['num'] = $v['num'];
                $list['total'] = $v['total'];
                $list['addtime'] = date('Y-m-d H:i:s', $v['addtime']);
                $list['consignee'] = $v['consignee'];
                $list['tel'] = $v['tel'];
                $list['address'] = $v['address'];
                $list['express'] = $v['express'];
                $data['list'][$v['order_id']] = $list;
            }
        }
        exit(Tpl::flexigridXML($data));
    }

}
