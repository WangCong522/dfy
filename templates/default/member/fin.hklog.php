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
                                <th>汇款方式</th>
                                <th>汇款账户</th>
                                <th>汇款金额</th>
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
                                        <td><?php echo $val['hkzh']; ?></td>
                                        <td><?php echo $val['jine']; ?></td>
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
