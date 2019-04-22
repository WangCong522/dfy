<?php defined('InShopBN') or exit('Access Invalid!');?>
    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo" style="max-width: 500px;margin-left: 22%;">
            <a href="index.html">
                <img src="/Public/Home/assets/pages/img/login/1616.png" style="width:60%" alt="" /> </a>
        </div>
        <div class="visible-xs-block visible-sm-block mobile"></div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content" style="padding-bottom: 0px;margin-left: 25%;">
            <!-- BEGIN LOGIN FORM -->
            <form>
                <h3 class="form-title font-green">重置密码</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span>输入用户名和密码</span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">新密码</label>
                    <input class="form-control form-control-solid placeholder-no-fix form-group newpwd" type="password" autocomplete="off" placeholder="新密码" name="username"/>
                    </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">重复密码</label>
                    <input class="form-control form-control-solid placeholder-no-fix form-group repwd" type="password" autocomplete="off" placeholder="重复密码" name="pwd"/> </div>
        
                <div class="form-actions">
                    <a  class="btn green uppercase tj">提交</a>
                </div>
            </form>
            <input type="hidden" value="<?php echo $output['tn'];?>" class="token" />
        </div>
        <!--[if lt IE 9]>
<script src="/Public/Home/assets/global/plugins/respond.min.js"></script>
<script src="/Public/Home/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/Public/Home/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="/Public/Home/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="/Public/Home/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/Public/Home/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="/Public/Home/assets/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
</body>
<script type="text/javascript">
$('.tj').click(function(event) {
            var newpwd=$('.newpwd').val();
            var repwd=$('.repwd').val();
            var token=$('.token').val();
            if(newpwd==''){
                    alert('请输入密码');
                    return false;
            }else{
                if(newpwd!=repwd){
                    alert('两次输入的密码不一致');
                    return false;
                }
            }
            $.post("/?act=login&op=doRepwd", {newpwd:newpwd,token:token}, function(data) {
                            if(data.status){
                                alert(data.info);
                                location.href="/";
                            }else{
                                alert(data.info);
                            }
            },'json');
});
</script>