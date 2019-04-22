<?php defined('InShopBN') or exit('Access Invalid!');?>
<link href="/Public/Home/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Home/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Home/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Home/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>奖金核算</span>
            </li>
        </ul>
    </div>
    <div class="row ">
        <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
        提示：
		<br />1. 点击下面日历表中需要核对的日期，然后点击查询，如果你的奖金需要补偿，会自动补上。
<br />2. 如果网速较慢，请耐心等待<br />3. 只能查询2016-8-24日之后的
		</div>
        <form action="" class="forminfo" method="post" accept-charset="utf-8">
        <div class="input-group date date-picker margin-bottom-5 col-sm-4" style="float: left;" data-date-format="yyyy-mm-dd">
        <span class="input-group-btn">
                                                                <button class="btn btn-sm default" type="button"><span class="md-click-circle md-click-animate" style="height: 55px; width: 55px; top: -5px; left: 7.5px;"></span>
            <i class="fa fa-calendar"></i>
            </button>
            </span>
            <input type="text" class="form-control form-filter input-sm from" readonly="" name="order_date_from" placeholder="时间">
            
        </div>
        <div class="input-group col-sm-4" style="margin-left: 25px;border:white solid 1px;">
            <span class="input-group-btn">
                                                                <button class="btn btn-sm info tj" type="button">
                                                                    <i class="fa fa-search">查询</i>
                                                                </button>
                                                            </span>
        </div>
        </form>
        <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
            <div class="table-scrollable">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                    <thead>
                        <tr role="row">
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">日期</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">推荐奖</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">层碰奖</th>
							<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">见点奖</th>
							<th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">当日封顶</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">需要补偿</th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr class="gradeX odd" role="row">
                          <td id="d_date"></td>
						  <td id="d_tui"></td>
						  <td id="d_ceng"></td>
						  <td id="d_jian"></td>
						  <td id="d_fd"></td>
						  <td id="d_bu"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script src="/Public/Home/assets/global/plugins/moment.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN THEME GLOBAL SCRIPTS -->
	<script src="/Public/Home/assets/global/scripts/app.min.js" type="text/javascript"></script>
	<!-- END THEME GLOBAL SCRIPTS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="/Public/Home/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>

    <script>
    $('.tj').click(function(){
            var fromtime=$('.from').val();
            if(fromtime==''){
                    alert('请输入日期');
                    return false;
            }
           $.post("/?act=fin&op=query_jiang",{d:fromtime},function(data){
					if(data.status){
						 $("#d_date").html(data.date);
						 
						 var str = "应得:" + data.yd.tui + " ; 实得:" + data.sd.tui;
						 $("#d_tui").html(str);
						 
						str = "应得:" + data.yd.ceng + " ; 实得:" + data.sd.ceng;
						 $("#d_ceng").html(str);
						 
						 str = "应得:" + data.yd.jian + " ; 实得:" + data.sd.jian;
						 $("#d_jian").html(str);
						 
						 str = "是";
						 if(parseInt(data.ma) != parseInt(data.balance)) str = "否";
						 $("#d_fd").html(str);
						 
						 str = "是";
						 if(!data.bu) str = "否";
						 $("#d_bu").html(str);
						 
						 if(data.bu){
							 swal("补偿成功", "已补偿成功", "success");
						 }
						 else{
							 swal("查询结果", "不需要补偿", "success");
						 }
						 
					}else{
						 swal("查询失败", data.msg, "warning");
					}
				},'json')
    })
    </script>