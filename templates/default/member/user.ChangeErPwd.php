
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
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
            <li  class="active"><a href="#">二级密码修改</a></li>
            <li><a href="index.php?act=user&op=ChangeMb">密保修改</a></li>
        </ul>
        <form action="index.php?act=user&op=doChangeErpwd" class="form-horizontal" method="post">
            <lable style="color: red;" class="new_style_margin_b10">二级密码修改</lable>
            <div class="form-group">
                <label class="col-sm-2 col-xs-5 new_style_padding1 text_new_style_r control-label">
                    <label for="oldpwd">旧二级密码</label>：
                    <span style="color:red;display:inline-block;margin-left:5px;">*</span>
                </label>
                <div class="col-sm-5 col-xs-6">
                    <input class="form-control" id="oldpwd" name="oldpwd" type="text" value="">
                </div>
                <div class="col-sm-5 col-xs-6">
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-xs-5 new_style_padding1 text_new_style_r control-label">
                    <label for="newpwd">新二级密码</label>：
                    <span style="color:red;display:inline-block;margin-left:5px;">*</span>
                </label>
                <div class="col-sm-5 col-xs-6">
                    <input class="form-control" id="newpwd" name="newpwd" type="text" value="">
                </div>
                <div class="col-sm-5 col-xs-6">
                   
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-xs-5 new_style_padding1 text_new_style_r control-label">
                    <label for="repwd">再次输入新密码</label>：
                    <span style="color:red;display:inline-block;margin-left:5px;">*</span>
                </label>
                <div class="col-sm-5 col-xs-6">
                    <input class="form-control" id="repwd" name="repwd" type="text" value="">
                </div>
                <div class="col-sm-5 col-xs-6">
                   
                </div>
            </div>
           
            <input class="form-control" id="ReturnUrl" name="ReturnUrl" type="hidden" value="/Vip/user/ChangeL1Pwd">
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2 text-center">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-disk"></span> 提交修改
                    </button>
                </div>
            </div>
        </form>
    </div>