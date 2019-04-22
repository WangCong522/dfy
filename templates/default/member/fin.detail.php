<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>奖金记录</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
        <div class="table-scrollable">
            <table
                class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                id="sample_1" role="grid" aria-describedby="sample_1_info">
                <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">日期</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">直推奖</th>
                    <!-- <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">见点奖</th> -->
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">量碰奖</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">层碰奖</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">分润工资</th>
                    <!--<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">培育奖</th>-->
                    <!--<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">感恩奖</th>-->
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">购物币</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">报单奖</th>
                    <!--<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">服务站推荐</th>-->
                    <!--<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">税费</th>-->
                    <!--<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">慈善基金</th>-->
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">总计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($output['list'])) {
                    foreach ($output['list'] as $val) {
                        ?>
                        <tr class="gradeX odd" role="row">
                            <td><?php echo $val['bdate']; ?></td>
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b1']; ?></span>
                            </td>
<!--                            <td class="sorting_1">-->
<!--                                <span style="color: red;">--><?php //echo $val['b3']; ?><!--</span>-->
<!--                            </td>-->
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b2']; ?></span>
                            </td>
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b11']; ?></span>
                            </td>
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b12']; ?></span>
                            </td>
                            <!--<td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b4']; ?></span>
                            </td>-->
                            <!--<td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b10']; ?></span>
                            </td>-->
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b7']; ?></span>
                            </td>
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b5']; ?></span>
                            </td>
                            <!--<td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b6']; ?></span>
                            </td>-->
                            <!--<td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b8']; ?></span>
                            </td>-->
                            <!--<td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b9']; ?></span>
                            </td>-->
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['b0']; ?></span>
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