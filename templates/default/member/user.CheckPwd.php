<?php defined('InShopBN') or exit('Access Invalid!');?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>验证密保</span>
            </li>
        </ul>
    </div>
    <div class="row ">
                    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
                    </div>
                    

<form action="" class="form-horizontal" method="post">
<div class="form-group">
        <label class="col-sm-2 control-label"><label for="MIBaoId">密保问题</label>：</label>
        <div class="col-sm-10">
            <p class="form-control-static"><?php echo $output['info']['problem'];?></p>
        </div>
</div>                
    <div class="form-group">
        <label class="col-sm-2 control-label"><label for="MIBaoKey">密保答案</label>：
            <span style="color:red;display:inline-block;margin-left:5px;">*</span>
        </label>
        <div class="col-sm-5">
            
<input class="form-control" id="MIBaoKey" name="MIBaoKey" type="text" value="">
            <p class="form-control-static"><span class="field-validation-valid" data-valmsg-for="MIBaoKey" data-valmsg-replace="true"></span></p>
            <p id="_htmlExtension_MIBaoKey" class="form-control-static">
                
            </p>
        </div>
        <div class="col-sm-5">
            <p class="form-control-static" id="__extension__"></p>
        </div>
    </div>

<input class="form-control" id="ReturnUrl" name="ReturnUrl" type="hidden" value="/Vip/user/ChangeL1Pwd">    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span>
                验证密保问题</button>
        </div>
    </div>

</form>

                </div>