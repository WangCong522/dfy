<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>服务站</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="portlet-body">
        <?php if (!is_array($output['bizInfo'])) { ?>
            <ul class="nav nav-tabs">
                <li>
                    <a href="index.php?act=team&op=applyServiceStation" class="link2">申请服务站</a>
                </li>
            </ul>
        <?php } ?>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="tab_1_1">
                <div class="portlet-body">
                    <div class="table-scrollable">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>报单中心编号</th>
                                <th>报单中心总人数</th>
                                <th>报单中心总业绩</th>
                                <th>申请时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['bizInfo'])) { ?>
                                <tr>
                                    <td><?php echo $output['bizInfo']['id']; ?></td>
                                    <td><?php echo $output['bizInfo']['count']; ?></td>
                                    <td><?php echo $output['bizInfo']['total']; ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', $output['bizInfo']['addtime']); ?></td>
                                    <td>
                                        <?php if (empty($output['bizInfo']['status'])) { ?>
                                            未审核
                                        <?php } else { ?>
                                            已审核
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination"><?php echo $output['show_page']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>