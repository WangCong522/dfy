<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>币种转换</span>
        </li>
    </ul>
</div>
<div class="row ">

    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <ul class="nav nav-tabs" style="margin-bottom:20px;">
        <li class="active"><a href="#">货币转换</a></li>
        <li><a href="index.php?act=fin&op=change_list">记录</a></li>
    </ul>
    <form action="index.php?act=fin&op=Hbzh" method="post" class="info">
        <div class="zmz-form-row form-group"><span class="zmz-form-row-label new_style_width4 new_style_padding2 text-right">转出货币：</span>
            <select id="FromCurrencyId" name="FromCurrencyId" class="form-control new_style_width3">
                <option value="ji">积分</option>
            </select>
        </div>
        <div class="zmz-form-row form-group"><span class="zmz-form-row-label new_style_width4 new_style_padding2 text-right">转入货币：</span>
            <select id="ToCurrencyId" name="ToCurrencyId" class="form-control new_style_width3">
                <option value="bao">报单币</option>
                <option value="zhang">购物币</option>
            </select>
        </div>
        <div class="zmz-form-row form-group"><span class="zmz-form-row-label new_style_width4 new_style_padding2 text-right">转出金额：</span>
            <input class="form-control new_style_width3" id="Amount" name="Amount" type="text" value="" style=""></div>
        <div class="zmz-form-row-validation"><span class="field-validation-valid" data-valmsg-for="Amount"
                                                   data-valmsg-replace="true"></span></div>
        <div class="zmz-form-row clearfix"><span class="zmz-form-row-label new_style_width4 new_style_padding2 text-right">转入金额：</span> <span class="new_style_padding3" style="color: red;"
                                                                                      id="DisplayAmount">1</span></div>
        <div class="zmz-form-row text-center" style="margin-top:30px;"><span class="zmz-form-row-label"></span>
            <button class=" btn-primary btn tj" name="" type="button"><span
                    class="glyphicon  glyphicon-floppy-disk"></span> 提交
            </button>
        </div>
    </form>
</div>
<script>
    $("#Amount").blur(function () {
        var currentValue = $(this).val();
        $('#DisplayAmount').text('');
        if (/.*[\u4e00-\u9fa5]+.*$/.test(currentValue)) {
            return false;
        }
        if (isNaN(currentValue)) {
            return false;
        }
        $('#DisplayAmount').text(currentValue);
    });
    $('.tj').click(function (event) {
        var currentValue = $('#Amount').val();
        if (isNaN(currentValue)) {
            swal("错误", "请输入数字", "warning");
            return false;
        }
        if (currentValue <= 0) {
            swal("错误", "请输入转出金额", "warning");
            return false;
        }
        swal({
            title: "你确定吗?",
            text: "确定需要转换吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: true
        }, function (confirm) {
            if (confirm) {
                $('.info').submit();
            }
        });
    });
</script>