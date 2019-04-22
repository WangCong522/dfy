<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>转账</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <ul class="nav nav-tabs" style="margin-bottom:20px;">
        <li class="active"><a href="#">转账</a></li>
        <li><a href="index.php?act=fin&op=tran_list">记录</a></li>
    </ul>
    <form action="" class="form-horizontal info" method="post">
        <div class="form-group">
            <label class="col-sm-2 col-xs-4 text-right new_style_padding2 control-label">用户名：<span class="zmz-form-row-required" color="red"
                                                            display="inline-block" margin-left="5px">*</span></label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none">
                <input class="form-control" id="ToUserName" name="ToUserName" type="text"
                       value="<?php echo $output['targetUserinfo']['username']; ?>">
                <p class="form-control-static ysys1 "><span class="field-validation-valid" data-valmsg-for="ToUserName"
                                                     data-valmsg-replace="true"></span><span class="uname"
                                                                                             style="color: red"></span>
                </p>
            </div>
            <div class="col-sm-5 col-xs-7 new_style_padding_none" id="__extension__">
                <div class="form-control-static ysys1 "></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-xs-4 text-right new_style_padding2 control-label">货币类型：<span class="zmz-form-row-required" color="red"
                                                             display="inline-block" margin-left="5px">*</span></label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none">
                <select class="form-control" id="CurrencyType" name="CurrencyType">
                    <option value="ji">积分</option>
                    <option value="bao">报单币</option>
                </select>
                <p class="form-control-static ysys1 "><span class="field-validation-valid" data-valmsg-for="CurrencyType"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 col-xs-7 new_style_padding_none" id="__extension__">
                <div class="form-control-static ysys1 "></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-xs-4 text-right new_style_padding2 control-label">转账金额：<span class="zmz-form-row-required" color="red"
                                                             display="inline-block" margin-left="5px">*</span></label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none">
                <input class="form-control" id="Amount" name="Amount" type="text" placeholder="必须为100的倍数" value="">
                <p class="form-control-static  "><span class="field-validation-valid" data-valmsg-for="Amount"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 col-xs-7 new_style_padding_none ysys1" id="__extension__">
                <div class="form-control-static ysys1 "></div>
            </div>
            <div class="zmz-form-row text_new_style_c" style="margin-top:30px;"><span class="zmz-form-row-label"></span>
                <button class=" btn-primary btn tj" name="" type="button"><span
                        class="glyphicon  glyphicon-floppy-disk"></span> 提交
                </button>
            </div>
            <!--<div class="form-group">
                <label class="col-sm-2 col-xs-4 text-right new_style_padding2 control-label">二级密码：<span class="zmz-form-row-required" color="red" display="inline-block" margin-left="5px">*</span></label>
                <div class="col-sm-5 col-xs-7 new_style_padding_none">
                    <input class="form-control"  id="L2Pwd" name="L2Pwd" type="password" value="">
                    <p class="form-control-static ysys1 "><span class="field-validation-valid" data-valmsg-for="L2Pwd" data-valmsg-replace="true"></span></p>
                </div>
                <div class="col-sm-5 col-xs-7 new_style_padding_none" id="__extension__">
                    <div class="form-control-static ysys1 "></div>
                </div>
                 <div class="zmz-form-row" style="margin-top:30px;"><span class="zmz-form-row-label"></span>
                <button class=" btn-primary btn tj" name="" type="button"><span class="glyphicon  glyphicon-floppy-disk"></span> 提交</button>
            </div>
            </div>-->

    </form>
</div>
<script>
    $('.tj').click(function (event) {
        var username = $('#ToUserName').val();
        var jine = $('#Amount').val();
        var r = /^[0-9]*[1-9][0-9]*$/;
        //var pwd = $('#L2Pwd').val();
        var type = '<?php echo $output['ulevel'];?>';
        var tType = $('#CurrencyType').val();
        if (!username) {
            swal("错误", "请输入会员名", "warning");
            return false;
        }
        if (isNaN(jine)) {
            swal("错误", "请输入数字", "warning");
            return false;
        }
        if (jine > 0) {
            if (!r.test(jine)) {
                swal("错误", "转出金额必须为整数", "warning");
                return false;
            }
			if (parseFloat(jine) % 100 > 0) {
                swal("错误", "转出金额必须为100的倍数", "warning");
                return false;
           
			}
        } else {
            swal("错误", "请输入转出金额", "warning");
            return false;
        }
        swal({
            title: "你确定吗?",
            text: "确定需要转账吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: true
        }, function (confirm) {
            if (confirm) {
                $.post("index.php?act=fin&op=CheckInfo", {
                    username: username,
                    type: tType,
                    amount: jine
                }, function (data) {
                    if (data.status) {
                        swal("成功", "转账成功", "success");
                        window.setTimeout('location.href="index.php?act=fin&op=tran_list"', '1000');
                    } else {
                        setTimeout(function () {
                            swal("错误", data.info, "warning");
                        }, 300);
                    }
                }, 'json');
            }
        });
    });

    $('#ToUserName').blur(function (event) {
        var username = $(this).val();
        $.post("index.php?act=user&op=GetName", {username: username}, function (data) {
            if (data.status) {
                $('.uname').text('用户名:' + data.info);
            } else {
                $('.uname').text('该用户不存在,请确认');
            }
        }, 'json');
    });

    $('#ToUserName').focus(function () {
        $('.uname').text('');
    })
</script>