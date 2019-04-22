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
        <li class="active"><a href="#">二级密码</a></li>
    </ul>
    <form action="index.php?act=user&op=checkJuPwd" class="form-horizontal info" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="refurl" value="<?php echo getReferer(); ?>">
        <div class="form-group">
            <label class="col-sm-2 control-label">二级密码：<span class="zmz-form-row-required" color="red"
                                                             display="inline-block" margin-left="5px">*</span></label>
            <div class="col-sm-5">
                <input class="form-control" id="L2Pwd" name="L2Pwd" type="password" value="">
                <p class="form-control-static"><span class="field-validation-valid" data-valmsg-for="L2Pwd"
                                                     data-valmsg-replace="true"></span></p>
            </div>
            <div class="col-sm-5" id="__extension__">
                <div class="form-control-static"></div>
            </div>
            <div class="zmz-form-row text-center" style="margin-top:80px;"><span class="zmz-form-row-label"></span>
                <button class=" btn-primary btn tj" name="" type="submit"><span
                        class="glyphicon  glyphicon-floppy-disk"></span> 提交
                </button>
            </div>
        </div>
    </form>
</div>