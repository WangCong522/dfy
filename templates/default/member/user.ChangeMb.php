
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{:U('Index/index')}">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>修改密码</span>
            </li>
        </ul>
    </div>
    <div class="row ">
        <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
        </div>
        <ul class="nav nav-tabs" style="margin-bottom:20px;">
            <li><a href="index.php?act=user&op=changePwd">登录密码修改</a></li>
            <li><a href="index.php?act=user&op=ChangeErPwd">二级密码修改</a></li>
            <li class="active"><a href="#">密保修改</a></li>
        </ul>
            <form action="index.php?act=user&op=DoChangeMb" class="form-horizontal mb" method="post">
<div class="form-group form-md-floating-label col-sm-5">
                            <label for="mbwt" class="col-xs-3 new_style_padding1 text_new_style_r new_style_margin1">密保问题</label>
                                <select class="form-control edited new_style_width2" id="mbwt" name="mbwt">
                                    <option value="您高中班主任的名字是?">您高中班主任的名字是?</option>
                                    <option value="您的小学校名是?">您的小学校名是?</option>
                                    <option value="您母亲的生日是?">您母亲的生日是?</option>
                                    <option value="您配偶的姓名是?">您配偶的姓名是?</option>
                                    <option value="您的学号（或工号）是?">您的学号（或工号）是?</option>
                                    <option value="您的出生地是?">您的出生地是?</option>
                                    <option value="您小学班主任的名字是?">您小学班主任的名字是?</option>
                                    <option value="您父亲的姓名是?">您父亲的姓名是?</option>
                                    <option value="您配偶的生日是?">您配偶的生日是?</option>
                                    <option value="您初中班主任的名字是?">您初中班主任的名字是?</option>
                                    <option value="您母亲的姓名是?">您母亲的姓名是?</option>
                                    <option value="您父亲的生日是?">您父亲的生日是?</option>
                                </select>
                                
                            </div>
                            <div style="clear: both"></div>
                            <div class="form-group form-md-floating-label col-sm-5">
                            <label for="mbda" class="col-xs-3 new_style_padding_none text_new_style_r new_style_margin1 new_style_padding1"><span style="color: red">*</span>密保答案</label>
                                <input type="text" class="form-control new_style_width2" id="mbda" name="mbda" value="">
                                
                            </div>

<input class="form-control" id="ReturnUrl" name="ReturnUrl" type="hidden" value="/Vip/user/ChangeL1Pwd">    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2 text-center">
            <button type="button" class="btn btn-primary tj">
                <span class="glyphicon glyphicon-floppy-disk"></span>
                更改密保</button>
        </div>
    </div>

</form>
    </div>
    <script>
    $('.tj').click(function(event) {
        var mbda=$('.mbda').val();
        if(mbda==""){
            alert('请输入密保答案');
            return false;
        }
        $('.mb').submit();
    });
    </script>