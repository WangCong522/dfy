<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>提现记录</span>
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
                                <th>编号</th>
                                <th>提现方式</th>
                                <th>账户</th>
                                <th>提现金额</th>
                                <th>手续费</th>
                                <th>管理费</th>
                                <th>实得</th>
                                <th>申请时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['txList'])) {
                                foreach ($output['txList'] as $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['id']; ?></td>
                                        <td><?php echo $val['czfs']; ?></td>
                                        <td><?php echo $val['zhanghao']; ?></td>
                                        <td><?php echo $val['jine']; ?></td>
                                        <td><?php echo $val['con_tx_jine']; ?></td>
                                        <td><?php echo $val['guanlifei']; ?></td>
                                        <td><?php echo $val['sjjine']; ?></td>
                                        <td><?php echo $val['time']; ?></td>
                                        <td>
                                            <?php if ($val['status'] == 1) { ?>
                                                已审核
                                            <?php } elseif ($val['status'] == 0) { ?>
                                                待审核
                                            <?php } elseif ($val['status'] == 2) { ?>
                                                未通过
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
                    <div class="pagination"><?php echo $output['show_page']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function futou(id) {
        swal({
            title: "确认复投本娱乐包？",
            text: "复投后奖继续享受分红！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: true
        }, function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "index.php?act=goods&op=yulebaofutou",
                    data: "id=" + id,
                    success: function (data) {
                        if (data.state == 1) {
                            setTimeout(function () {
                                swal("成功!", data.msg, "success");
                            }, 300);
                            window.setTimeout('location.reload();', '1000');
                        } else {
                            setTimeout(function () {
                                swal("错误", data.msg, "warning");
                            }, 300);
                        }
                    }
                });
            }
        });
    }

    function del(id) {
        swal({
            title: "确认删除本娱乐包？",
            text: "删除后本娱乐包不再享受分红！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: true
        }, function (confirm) {
            if (confirm) {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "index.php?act=goods&op=yulebaodel",
                    data: "id=" + id,
                    success: function (data) {
                        if (data.state == 1) {
                            setTimeout(function () {
                                swal("成功!", data.msg, "success");
                            }, 300);
                            window.setTimeout('location.reload();', '1000');
                        } else {
                            setTimeout(function () {
                                swal("错误", data.msg, "warning");
                            }, 300);
                        }
                    }
                });
            }
        });
    }
</script>