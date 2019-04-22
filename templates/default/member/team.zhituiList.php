<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>直推列表</span>
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
                                <th>会员编号</th>
                                <th>会员名</th>
								<th>真实姓名</th>
								<th>手机号码</th>
                                <th>注册日期</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (is_array($output['memberList'])) {
                                foreach ($output['memberList'] as $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $val['id']; ?></td>
                                        <td><?php echo $val['username']; ?></td>
										<td><?php echo $val['name']; ?></td>
										<td><?php echo $val['tel']; ?></td>
                                        <td><?php echo date("Y-m-d H:i", $val['time']); ?></td>
                                        <td>
                                            <?php if ($val['status']) { ?>
                                                已激活
                                            <?php } else { ?>
                                                未激活
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

</script>