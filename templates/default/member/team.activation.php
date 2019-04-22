<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>会员激活管理</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="portlet-body">
        <ul class="nav nav-tabs">
            <li class="{$link}">
                <a href="#tab_1_1" class="link1" data-toggle="tab" aria-expanded="true">待激活会员</a>
            </li>
            <li class="{$link2}">
                <a href="#tab_1_2" class="link2" data-toggle="tab" aria-expanded="false">已激活会员</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>会员编号</th>
                                <th>会员名</th>
                                <th>推荐人</th>
                                <th>账号级别</th>
                                <th>报单费</th>
                                <th>注册日期</th>
                                <?php if (empty($output['status'])) { ?>
                                    <th>操作</th>
                                <?php } else { ?>
                                    <th>激活日期</th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['list'])) {
                                foreach ($output['list'] as $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['id']; ?></td>
                                        <td><?php echo $val['username']; ?></td>
                                        <td><?php echo $val['rname']; ?></td>
                                        <td>
                                            <span
                                                class="label label-sm label-success"><?php echo $val['group_name']; ?></span>
                                        </td>
                                        <td><?php echo $val['lsk']; ?></td>
                                        <td><?php echo date("Y-m-d H:i", $val['time']); ?></td>
                                        <td>
                                            <?php if (empty($val['status'])) { ?>
                                                <a href="javascript:;"
                                                   class="btn btn-outline btn-circle btn-sm success add"
                                                   data-id="<?php echo $val['id']; ?>">
                                                    <i class="fa fa-user-plus"></i>激活学员</a>
                                                <a href="javascript:;"
                                                   class="btn btn-outline btn-circle red btn-sm blue del"
                                                   data-id="<?php echo $val['id']; ?>">
                                                    <i class="fa fa-trash-o"></i>删除</a>
                                            <?php } else {
                                                echo date("Y-m-d H:i:s", $val['jh_time']);
                                            } ?>

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
    $('.link1').click(function (event) {
        location.href = "index.php?act=team&op=activation";
    });
    $('.link2').click(function (event) {
        location.href = "index.php?act=team&op=activation&status=1";
    });
    $('.add').click(function (event) {
        var id = $(this).attr('data-id');
        swal({
            title: "你确定吗?",
            text: "激活用户将会消耗报单币",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            $.post("index.php?act=team&op=confirm_add", {id: id}, function (data) {

                if (data.status) {
                    swal("成功!", "确认添加成功!", "success");
                    location.reload();
                } else {
                    swal("失败!", data.info, "warning");
                }
            }, 'json');


        });
    });
    $('.del').click(function (event) {
        var id = $(this).attr('data-id');
        swal({
            title: "你确定吗?",
            text: "删除此用户吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            $.post("index.php?act=team&op=delUser", {id: id}, function (data) {
                if (data.status) {
                    swal("成功!", "删除成功!", "success");
                    location.reload();
                } else {
                    swal("失败!", data.info, "warning");
                }
            }, 'json');

        });
    });
</script>