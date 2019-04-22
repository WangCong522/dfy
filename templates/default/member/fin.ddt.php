<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<link href="/Public/Home/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
      type="text/css"/>
<link href="/Public/Home/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
      type="text/css"/>
<link href="/Public/Home/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"
      type="text/css"/>
<link href="/Public/Home/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"
      rel="stylesheet" type="text/css"/>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>日奖金明细</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <form action="" class="forminfo" method="post" accept-charset="utf-8">
        <div class="input-group date date-picker margin-bottom-5 col-sm-4" style="float: left;"
             data-date-format="yyyy/mm/dd">
        <span class="input-group-btn">
                                                                <button class="btn btn-sm default" type="button"><span
                                                                        class="md-click-circle md-click-animate"
                                                                        style="height: 55px; width: 55px; top: -5px; left: 7.5px;"></span>
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
            </span>
            <input type="text" class="form-control form-filter input-sm from" readonly="" name="order_date_from"
                   placeholder="开始时间" value="<?php echo date('Y/m/d',$output['fromtime'])?>">

        </div>
        <div class="input-group date date-picker  col-sm-4" data-date-format="yyyy/mm/dd" style="float: left;">
        <span class="input-group-btn">
                                                                <button class="btn btn-sm default" type="button">
                                                                    <i class="fa fa-calendar"></i>
                                                                </button>
                                                            </span>
            <input type="text" class="form-control form-filter input-sm to" readonly="" name="order_date_to"
                   placeholder="结束时间" value="<?php echo date('Y/m/d',$output['totime'])?>">

        </div>
        <div class="input-group col-sm-4 new_style_margin2" style="margin-left: 25px;border:white solid 1px;">
            <span class="input-group-btn">
                                                                <button class="btn btn-sm info tj" type="button">
                                                                    <i class="fa fa-search">搜索</i>
                                                                </button>
                                                            </span>
        </div>
    </form>
    <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
        <div class="table-scrollable">
            <table
                class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                id="sample_1" role="grid" aria-describedby="sample_1_info">
                <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">奖金名称</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">金额</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">日期</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">描述</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">相关会员</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($output['list'])) {
                    foreach ($output['list'] as $val) {
                        ?>
                        <tr class="gradeX odd" role="row">
                            <td><?php echo $val['money_type']; ?></td>
                            <td class="sorting_1">
                                <span style="color: red;"><?php echo $val['money']; ?></span>
                            </td>
                            <td class="sorting_1">
                                <?php echo date("Y-m-d H:i:s", $val['time']); ?>
                            </td>
                            <td class="sorting_1">
                                <?php echo $val['intro']; ?>
                            </td>
                            <td class="sorting_1">
                                <?php echo $val['rel_username']; ?>
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
<script src="/Public/Home/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/Home/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Public/Home/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

<script>
    $('.tj').click(function () {
        var fromtime = $('.from').val();
        var totime = $('.to').val();
        if (fromtime == '') {
            alert('请输入开始时间');
            return false;
        }
        if (totime == '') {
            alert('请输入结束时间');
            return false;
        }
        $('.forminfo').submit();
    })
</script>