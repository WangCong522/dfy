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
                                <th>总运费</th>
                                <th>总价</th>
                                <th>下单时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['orderList'])) {
                                foreach ($output['orderList'] as $val) {
                                    $count += $val['total'];
                                    ?>
                                    <tr>
                                        <td><?php echo $val['order_id']; ?></td>
                                        <td><?php echo $val['goods_name']; ?></td>
                                        <td><?php echo $val['unit_price']; ?></td>
                                        <td><?php echo $val['num']; ?></td>
                                        <td><?php echo $val['ship_fee']; ?></td>
                                        <td><?php echo $val['total']; ?></td>
                                        <td><?php echo date("Y-m-d H:i", $val['addtime']); ?></td>
                                        <td>
                                            <?php if ($val['status'] == 0) { ?>
                                                未支付 <a
                                                    href="index.php?act=goods&op=cancelOrder&order_id=<?php echo $val['order_id']; ?>">删除</a>
                                            <?php } elseif ($val['status'] == 1) { ?>
                                                等待发货 <a
                                                    href="index.php?act=goods&op=cancelOrder&order_id=<?php echo $val['order_id']; ?>">取消订单</a>
                                            <?php } elseif ($val['status'] == 2) { ?>
                                                已发货
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-offset-10 col-sm-1">
                            <span
                                style="padding: 10px 0;display: block;text-align: center;">总计：<?php echo number_format($count, 2); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">选择购买方式：</label>
                            <div class="dropup col-sm-6">
                                <select class="form-control" id="sbuy_type" name='sbuy_type'>
                                    <option value="">下拉选择支付方式</option>
                                    <option value="gwb">购物币支付</option>
                                    <option value="jf">会员积分支付</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-1 text-center">
                            <button class="btn btn-default buy_now">全部购买</button>
                        </div>
                    </div>
                    <!--<div class="pagination" style="margin-top: 10px">
                        <?php echo $output['show_page']; ?>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var doing = false;
$('.buy_now').click(function (event) {
    var buy_type = $('#sbuy_type').val();
    if (buy_type == '') {
        sweetAlert("错误!", "请选择购买方式后进行支付", "error");
        return false;
    }

    if (doing) {
        sweetAlert("已经提交，请耐心等待", "error");
        return false;
    }

    $.post("index.php?act=goods&op=userCartBuy", {'sbuy_type':buy_type}, function (data) {
            if (data.status) {
                swal("成功!", "本次购买成功！", "success");
                window.setTimeout('location.href="index.php?act=goods&op=userOrders"', '1000');
            } else {
                swal("失败!", data.info, "error");
            }
            doing = false;
        }, 'json');
});
</script>