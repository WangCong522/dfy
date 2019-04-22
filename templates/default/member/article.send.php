<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>站内信</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <form action="index.php?act=article&op=DoSend" class="form-horizontal" method="post">
        <div class="form-group">
            <label class="col-sm-2 col-xs-4 control-label text-right"><label for="MainTitle">标题</label>：
                <span style="color:red;display:inline-block;margin-left:5px;">*</span>
            </label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none">
                <input class="form-control" id="MainTitle" name="MainTitle" type="text" value="">
                <p class="form-control-static ysys1 new_style_margin_b10">
                    <span class="field-validation-valid"
                          data-valmsg-for="MainTitle"
                          data-valmsg-replace="true">
                    </span>
                </p>
                <p id="_htmlExtension_MainTitle" class="form-control-static ysys1 new_style_margin_b10">
                </p>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static ysys1 new_style_margin_b10" id="__extension__"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label col-xs-4 control-label text-right"><label for="ReceiverType">收信人类型</label>：
                <span style="color:red;display:inline-block;margin-left:5px;">*</span>
            </label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none new_style_width5">
                <select class="form-control" id="ReceiverType" name="ReceiverType" onChange="sjr()">
                    <option selected="selected" value="1">客服</option>
                    <option value="2">会员</option>
                </select>
                <p class="form-control-static ysys1 new_style_margin_b10">
                    <span class="field-validation-valid"
                          data-valmsg-for="ReceiverType"
                          data-valmsg-replace="true">
                    </span>
                </p>
                <p id="_htmlExtension_ReceiverType" class="form-control-static ysys1 new_style_margin_b10"></p>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static ysys1 new_style_margin_b10" id="__extension__"></p>
            </div>
        </div>
        <div class="form-group userid" style="display: none;">
            <label class="col-sm-2 control-label"><label for="ReceiverUserCode">会员名</label>：
                <span style="color:red;display:inline-block;margin-left:5px;">*</span>
            </label>
            <div class="col-sm-5">
                <input class="form-control" id="ReceiverUserCode" name="ReceiverUserCode" type="text" value="">
                <p class="form-control-static ysys1 new_style_margin_b10">
                    <span class="field-validation-valid"
                          data-valmsg-for="ReceiverUserCode"
                          data-valmsg-replace="true">
                    </span>
                </p>
                <p id="_htmlExtension_ReceiverUserCode" class="form-control-static ysys1 new_style_margin_b10"></p>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static ysys1 new_style_margin_b10" id="__extension__"></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label col-xs-4 control-label text-right"><label for="Content">内容</label>：
                <span style="color:red;display:inline-block;margin-left:5px;">*</span>
            </label>
            <div class="col-sm-5 col-xs-7 new_style_padding_none new_style_width5">
                <textarea class="form-control" cols="20" id="Content" name="Content" rows="8"></textarea>
                <p class="form-control-static ysys1 new_style_margin_b10"><span class="field-validation-valid" data-valmsg-for="Content"
                                                     data-valmsg-replace="true"></span></p>
                <p id="_htmlExtension_Content" class="form-control-static ysys1 new_style_margin_b10">

                </p>
            </div>
            <div class="col-sm-5">
                <p class="form-control-static ysys1 new_style_margin_b10" id="__extension__"></p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2 text-center">
                <button type="button" class="btn btn-primary tj">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                    提交
                </button>
            </div>
        </div>

    </form>


</div>
<script>
    function sjr() {
        if ($('#ReceiverType').val() == '2') {
            $('.userid').css('display', '');
        } else {
            $('.userid').css('display', 'none');
        }
    }
    $('.tj').click(function (event) {
        var type = $('#ReceiverType').val();
        var title = $('#MainTitle').val();
        var username = $('#ReceiverUserCode').val();
        var content = $('#Content').val();
        if (title == '') {
            swal("错误", "请填写标题", "warning");
            return false;
        }
        if (content == '') {
            swal("错误", "请填写内容", "warning");
            return false;
        }
        if (type == 2) {
            if (username == "") {
                swal("错误", "请填写用户名", "warning");
                return false;
            }
        }
        swal({
            title: "你确定吗?",
            text: "确定发送此消息吗?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: "取消",
            closeOnConfirm: true
        }, function (confirm) {
            if (confirm) {
                $.post("index.php?act=article&op=DoSend", {
                    type: type,
                    title: title,
                    username: username,
                    content: content
                }, function (data) {
                    if (data.status) {
                        swal("成功", '发送成功!', "success");
                    } else {
                        swal("错误", data.info, "warning");
                    }
                }, 'json');
            }
        });
    });
</script>