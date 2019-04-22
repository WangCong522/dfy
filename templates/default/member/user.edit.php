<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>资料修改</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <form action="?act=user&op=doEdit" class="form-horizontal tjinfo" method="post">
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label new_style_padding1">会员编号：</label>
            <div class="col-sm-10  col-xs-7">
                <p class="form-control-static ysys2" id="__UserCode"><?php echo $output['info']['id']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">姓名：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="RealName" name="RealName" type="text"
                       value="<?php echo $output['info']['name']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="RealName"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">邮箱：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="email" name="email" type="email"
                       value="<?php echo $output['info']['email']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="email"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">身份证号码：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="ID_Number" name="ID_Number" type="text"
                       value="<?php echo $output['info']['idcard']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="ID_Number"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">地址：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="Address" name="Address" type="text"
                       value="<?php echo $output['info']['address']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="Address"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">手机号：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="Mobile" name="Mobile" type="text"
                       value="<?php echo $output['info']['tel']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="Mobile"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding1 col-xs-4 text_new_style_r control-label">QQ：</label>
            <div class="col-sm-5 new_style_padding_none col-xs-7">
                <input class="form-control" id="QQ" name="QQ" type="text" value="<?php echo $output['info']['qq']; ?>">
                <p class="form-control-static ysys1"><span class="field-validation-valid" data-valmsg-for="QQ"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5 new_style_padding_none col-xs-7" id="__extension__">
                <div class="form-control-static ysys1"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10 text-center">
                <button class=" btn-primary btn" id="btnSubmit" name="btnSubmit"><span
                        class="glyphicon  glyphicon-floppy-disk"></span> 提交
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    $('#btnSubmit').click(function (event) {
        var NickName = $('#NickName').val();
        var RealName = $('#RealName').val();
        var ID_Number = $('#ID_Number').val();
        var Address = $('#Address').val();
        var Mobile = $('#Mobile').val();
        var QQ = $('#QQ').val();
        var email = $('#email').val();
        var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
        if (!myreg.test(email)) {
            alert('提示\n\n请输入有效的E_mail！');
            return false;
        }
        if (NickName == "" || RealName == "" || email == "" || Mobile == "") {
            sweetAlert("错误!", "请补全提交数据", "error");
            return false;
        }
        $('.tjinfo').submit();
    });
</script>