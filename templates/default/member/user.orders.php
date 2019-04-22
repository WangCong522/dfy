<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>我的订单</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="portlet-body">
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>订单编号</th>
                                <th>商品名称</th>
                                <th>单价</th>
                                <th>数量</th>
                                <th>总价</th>
                                <th>支付方式</th>
                                <th>花费额度</th>
                                <th>下单时间</th>
                                <th>状态</th>
                                <th>物流单号</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['orderList'])) {
                                foreach ($output['orderList'] as $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['order_id']; ?></td>
                                        <td><?php echo $val['goods_name']; ?></td>
                                        <td><?php echo $val['unit_price']; ?></td>
                                        <td><?php echo $val['num']; ?></td>
                                        <td><?php echo $val['total']; ?></td>
                                        <td>
                                            <?php 
                                                if($val['sbuy_type'] == 'gwb'){
                                                    echo '购物币消费';
                                                }elseif ($val['sbuy_type'] == 'jf'){
                                                    echo '会员积分消费';
                                                }elseif ($val['sbuy_type'] == 'hhzf'){
                                                    echo '混合支付';
                                                }elseif ($val['sbuy_type'] == 'jp'){
                                                    echo '会员奖品';
                                                }
                                            ?>   
                                        </td>
                                        <td><?php echo $val['xiaofei']; ?></td>
                                        <td><?php echo date("Y-m-d H:i", $val['addtime']); ?></td>
                                        <td>
                                            <?php if ($val['status'] == 0) { ?>
                                                未支付
                                            <?php } elseif ($val['status'] == 1) { ?>
                                                等待发货
                                                <?php if ($val['goods_typeid'] != 1) { ?>
                                                    <a href="index.php?act=goods&op=cancelOrder&order_id=<?php echo $val['order_id']; ?>">取消订单</a>
                                                <?php } ?>
                                                <a href="index.php?act=goods&op=show&id=<?php echo $val['gid']; ?>">商品详情</a>
                                            <?php } elseif ($val['status'] == 2) { ?>
                                                已发货
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $val['express']; ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination"><?php echo $output['show_page']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>