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
            <span>申请提现</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div class="portlet box purple">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-gift"></i>提现方式
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
            </div>
        </div>
        <div class="portlet-body" style="display: none;">
            <div class="row ">
                <div class="col-md-4">
                    <dl>
                        <dt>中国工商银行</dt>
                        <dd><?php echo $output['info']['icbc']; ?></dd>
                        <dt>中国农业银行</dt>
                        <dd><?php echo $output['info']['abc']; ?></dd>
                        <dt>中国建设银行</dt>
                        <dd><?php echo $output['info']['ccb']; ?></dd>
                        <dt>中国银行</dt>
                        <dd><?php echo $output['info']['boc']; ?></dd>
                        <br/>
                        <dt style="color:red;">人民币兑换比例1:1</dt>
                    </dl>
                </div>
                <div class="col-md-4">
                    <dt>支付宝</dt>
                    <dd><?php echo $output['info']['alipay']; ?></dd>
                    <img
                        src="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['info']['aliimg']); ?>"
                        height="150px" width="150px">
                </div>
                <div class="col-md-4">
                    <dt>微信</dt>
                    <dd><?php echo $output['info']['wx']; ?></dd>
                    <img
                        src="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['info']['wximg']); ?>"
                        height="150px" width="150px">
                </div>
            </div>
        </div>
    </div>
    <div class="portlet light bordered col-md-8">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark sbold uppercase">提现信息填写</span>
                <span class="caption-subject font-dark sbold uppercase"><a href="index.php?act=fin&op=Txlog">查看提现记录</a></span>
            </div>
        </div>
        <div class="portlet-body form">
            <form class="form-horizontal" role="form" method="post" action="index.php?act=fin&op=doTx"
                  onsubmit="return tosubmit();">
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">提现方式</label>
                        <div class="col-md-9 col-xs-8 ">
                            <select class="form-control type" name="type" onChange="change_type()">
                                <option value="1">银行转账</option>
                                <option value="2">支付宝转账</option>
                                <option value="3">微信转账</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group bank" style="display: ;">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">银行名称</label>
                        <div class="col-md-9 col-xs-8 ">
                            <select class="form-control changebank" name="changebank" onChange="change_bank()">
                                <option value="1">中国银行</option>
                                <option value="2">中国农业银行</option>
                                <option value="3">中国建设银行</option>
                                <option value="4">中国工商银行</option>
                                <option value="5">中国交通银行</option>
                                <option value="6">中国浦发银行</option>
                                <option value="7">中国光业银行</option>
                                <option value="8">中国邮政储蓄</option>
                                <option value="9">中国华夏银行</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group bank_zh">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">收款账号</label>
                        <div class="col-md-9 col-xs-8 ">
                            <input type="text" class="form-control bankzh" name="bankzh"
                                   value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">账户名:</label>
                        <div class="col-md-9 col-xs-8 ">
                            <input type="text" class="form-control zhm" required name="zhm" placeholder="提现开户名">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">提现金额</label>
                        <div class="col-md-9 col-xs-8 ">
                            <input type="text" class="form-control jine" required name="jine" id="Amount"
                                   placeholder="提现金额">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-3 text_new_style_r ">手机</label>
                        <div class="col-md-9 col-xs-8 ">
                            <input type="text" class="form-control tel" required name="tel" placeholder="请填写您的手机号">
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9 col-xs-12  text-center">
                            <button type="submit" class="btn green tj">提交</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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


    function tosubmit() {
        var type = <?php echo $output['info']['tx_jine'];?>;
        var userjifen = <?php echo $output['info']['userjifen'];?>;
        var currentValue = $('#Amount').val();
        if (isNaN(currentValue)) {
            swal("错误", "请输入数字", "warning");
            return false;
        }
        if (userjifen < currentValue) {
            swal("错误", "账户奖金余额不够!", "warning");
            return false;
        }
        if ((currentValue % type) != 0) {
            swal("错误", "转出金额必须是<?php echo $output['info']['tx_jine'];?>的整数倍", "warning");
            return false;
        }
    }
    
    function change_type() {
        var v = $('.type').val();
        if (v == 1) {
            $('.bank').css('display', '');
        } else if (v == 2) {
            $('.bank').css('display', 'none');
        } else if (v == 3) {
            $('.bank').css('display', 'none');
        }
    }
    function change_bank() {
        var b = $('.changebank').val();
    }
</script>