<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<body class="templatemo-bg-image-2">
<div class="container">
    <div class="col-md-12">
        <form class="form-horizontal templatemo-contact-form-1 login-form" action="index.php?act=login&op=login" method="post">
            <?php Security::getToken(); ?>
            <input type="hidden" name="form_submit" value="ok"/>
            <input name="nchash" type="hidden" value="<?php echo getNchash(); ?>"/>
            <div class="form-group">
                <div class="col-md-12">
                    <h1 class="margin-bottom-15"><?php echo $output['html_title'];?></h1></div>
            </div>
            <div class="form-group">
                <div class="col-md-12"><label class="control-label" for="name">用户名</label>
                    <div class="templatemo-input-icon-container"><I class="fa fa-user"></I>
                        <input name="username" class="form-control" id="username" type="text" placeholder="用户名"
                               value="" required></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12"><LABEL class="control-label" for="email">密码</LABEL>

                    <div class="templatemo-input-icon-container"><I class="fa fa-lock"></I>

                        <input name="pwd" class="form-control" id="pwd" type="password" placeholder="密码" value="">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12"><label class="control-label" for="email">验证码</label>

                    <div class="templatemo-input-icon-container"><I class="fa fa-qrcode"></I>

                        <input name="captcha" class="form-control" id="captcha" type="text"
                               placeholder="验证码" value="">
                        <img class="verifyimg reloadverify"
                             style="height: 40px; right: 2px; margin-top: -43px; position: absolute; cursor: pointer;"
                             onclick="changeCode(this)"
                             src="/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash(); ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input name="btnLogin" class="btn btn-dropbox pull-right" id="btnLogin"
                           style="width: 100%; color: white; font-weight: bolder; cursor: pointer; background-color: #20bf55;"
                           type="submit" value="登 录">
                    <input type="hidden" value="<?php echo $_GET['ref_url'] ?>" name="ref_url">
                </div>
                <!--<div class="col-md-12"> 
                	<div class="col-md-6 col-xs-6 text-left">
	                	<a href="./index.php?act=login&op=register">注册</a>
	                </div>
	                <div class="col-md-6 col-xs-6 text-right"> 
	                	<a href="./index.php?act=login&op=index_forgetPWD">忘记密码</a>
	                </div>
                </div>-->
                <div class="col-md-12 min">
                    <div class="form-actions login_regis">
                        <button type="button" id="back_register_btn" class="btn green btn-outline">注册</button>
                        <button type="button" id="register-btn" class="btn green btn-outline">
                           忘记密码?
                        </button>
                    </div>
                </div>
            </div>
            
            <!--<div class="form-group"> 
                <div class="col-md-12">
                    <a href="javascript:;" id="forget-password" class="forget-password">忘记用户名密码?</a>
                </div>
            </div>-->
            <div class="form-group"></div>
        </form>
        <form class="form-horizontal templatemo-contact-form-1 forget-form" action="index.html" method="post" style="display: none">
            <div class="form-group">
                <div class="col-md-12">
                    <h3 class="font-green">注册</h3>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="yhm" class="style_block"><span style="color:red;">*</span>用户名：</label>
                    <input class="form-control placeholder-no-fix yhm" id="yhm" disabled="true" type="text" value="<?php echo $output['username'] ?>" autocomplete="off" placeholder="请输入您的用户名" name="username"/>
                </div>
            </div>			
            <div class="form-group">
                <div class="col-md-12">
                	<!--<lable for="tel">手机：</lable>
                    <input class="form-control placeholder-no-fix tel phone_style" id="tel" type="text" autocomplete="off" placeholder="请输入您的手机号码" name="email"/>
                    <button type="button" class="btn blue gain" id="get_verifycode">获取验证码</button>-->
                    <label for="tel" class="style_block">手机</label>
                    <input type="text" class="form-control phone_style tel new_style_width6" id="tel" name="tel" value="">
					<button type="button" class="btn blue gain new_style_width7" id="get_verifycode">获取验证码</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="verifycode"><span style="color:red;">*</span>验证码：</label>
                    <input class="form-control placeholder-no-fix verifycode" id="verifycode" type="text" autocomplete="off" placeholder="验证码" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="dlmm"><span style="color:red;">*</span>登录密码：</label>
                    <input class="form-control placeholder-no-fix dlmm" id="dlmm" type="password" autocomplete="off" placeholder="请输入登录密码" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="qrdlmm"><span style="color:red;">*</span>确认登录密码：</label>
                    <input class="form-control placeholder-no-fix qrdlmm" id="qrdlmm" type="password" autocomplete="off" placeholder="请输入登录密码" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="jymm"><span style="color:red;">*</span>二次密码：</label>
                    <input class="form-control placeholder-no-fix jymm" id="jymm" type="password" autocomplete="off" placeholder="请输入交易密码" name="email"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="qrjymm"><span style="color:red;">*</span>确认交易密码：</label>
                    <input class="form-control placeholder-no-fix qrjymm" id="qrjymm" type="password" autocomplete="off" placeholder="请确认交易密码" name="email"/>
                </div>
            </div>
             <div class="form-group">
                <div class="col-md-12">
                	<label for="name"><span style="color:red;">*</span>姓名：</label>
                    <input class="form-control placeholder-no-fix name" id="name" type="text" autocomplete="off" placeholder="请输入您的真实姓名" name="email"/>
                </div>
            </div> 
            <div class="form-group sexs">
                <label for="sex" class="sex_sex">性别:</label>
                <label class="radio-inline man">
                    <input type="radio" name="sex" value="1" checked> 男
                </label>
                <label class="radio-inline women">
                    <input type="radio" name="sex" value="0"> 女
                </label>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-actions new_min">
                        <button type="button" id="back-btn" class="btn green btn-outline ">返回</button>
                        <button type="button" class="btn btn-success uppercase  forget_pwd ">
                            提交
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <form class="form-horizontal templatemo-contact-form-1 register-form" action="index.html" method="post" style="display: none">
            <div class="form-group">
                <div class="col-md-12">
                    <h3 class="font-green">忘记密码</h3>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="yhm" class="style_block"><span style="color:red;">*</span>用户名：</label>
                    <input class="form-control placeholder-no-fix yhm1" id="yhm1" type="text" value="" autocomplete="off" placeholder="请输入您的用户名" name="username"/>
                </div>
            </div>			
            <div class="form-group">
                <div class="col-md-12">
                	<!--<lable for="tel">手机：</lable>
                    <input class="form-control placeholder-no-fix tel phone_style" id="tel" type="text" autocomplete="off" placeholder="请输入您的手机号码" name="email"/>
                    <button type="button" class="btn blue gain" id="get_verifycode">获取验证码</button>-->
                    <label for="tel" class="style_block">手机</label>
                    <input type="text" class="form-control phone_style tel1 new_style_width6" id="tel1" name="tel" value="">
					<button type="button" class="btn blue gain new_style_width7" id="get_verifycode1">获取验证码</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="verifycode"><span style="color:red;">*</span>验证码：</label>
                    <input class="form-control placeholder-no-fix verifycode1" id="verifycode1" type="text" autocomplete="off" placeholder="验证码" name="verifycode1"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="dlmm"><span style="color:red;">*</span>登录密码：</label>
                    <input class="form-control placeholder-no-fix dlmm1" id="dlmm1" type="password" autocomplete="off"  name="dlmm1"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="qrdlmm"><span style="color:red;">*</span>确认登录密码：</label>
                    <input class="form-control placeholder-no-fix qrdlmm1" id="qrdlmm1" type="password" autocomplete="off" name="qrdlmm1"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="jymm"><span style="color:red;">*</span>二次密码：</label>
                    <input class="form-control placeholder-no-fix jymm1" id="jymm1" type="password" autocomplete="off"  name="jymm1"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                	<label for="qrjymm"><span style="color:red;">*</span>确认交易密码：</label>
                    <input class="form-control placeholder-no-fix qrjymm1" id="qrjymm1" type="password" autocomplete="off"  name="qrjymm1"/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-actions new_min">
                        <button type="button" id="register-back-btn" class="btn green btn-outline ">返回</button>
                        <button type="button" class="btn btn-success uppercase forget_sublim ">
                            提交
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<link href="/templates/default/home/login/templatemo_style.css" rel="stylesheet" type="text/css">
<script src="/templates/default/home/login/jquery-1.8.3.min.js" type="text/javascript"></script>
<script src="/templates/default/home/login/layer.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/pages/scripts/login.min.js" type="text/javascript"></script>
</body>
<script type="text/javascript">
    $(document).ready(function ($) {
        var display = $('.mobile').css('display');
        if (display == 'block') {
            $('.logo').attr('style', 'max-width: 500px');
            $('.content').attr('style', 'padding-bottom: 0px');
        }
        var verifyimg = $(".verifyimg").attr("src");
        $(".reloadverify").click(function () {
            if (verifyimg.indexOf('?') > 0) {
                $(".verifyimg").attr("src", '/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random());
            } else {
                $(".verifyimg").attr("src", '/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random());
            }
        });
    });
    $("#back_register_btn").click(function(){
    	$(".forget-form").show();
    	$(".login-form").hide();
    });
    
    
//	登录
//	$("#btnLogin").click(function(event){
//		var yhm = $('#username').val();
//		var dlmm = $('#pwd').val();
//		var verifycode = $('#captcha').val();
//		if (yhm == '') {
//          alert("请填写用户名");
//          return false;
//      } else {
//          if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
//              alert("用户名不能使用中文");
//              return false;
//          }
//      }
//      if (dlmm == '') {
//          alert("请填写密码");
//          return false;
//      } 
//      if (verifycode == '') {
//          alert("验证码必须填写");
//          return false;
//      }
//      $.post("index.php?act=login&op=register", {yhm:yhm,dlmm:dlmm,verifycode:verifycode}, function (data) {
//          if (data.status == 0) {
//              alert(data.info);
//          } else {
//              alert(data.info);
//          }
//      }, 'json');
//	})
//	注册
    $('.forget_pwd').click(function (event) {
        var yhm = $('.yhm').val();
        var tel = $('.tel').val();
        var verifycode = $('.verifycode').val();
        var dlmm = $('.dlmm').val();
        var qrdlmm = $('.qrdlmm').val();
        var jymm = $('.jymm').val();
        var qrjymm = $('.qrjymm').val();
        var name = $('.name').val();
        var sex = $('input:radio:checked').val();
        if (yhm == '') {
            alert("请填写用户名");
            return false;
        } else {
            if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
                alert("用户名不能使用中文");
                return false;
            }
        }
        if (tel == '') {
            alert("手机号必须填写");
            return false;
        }
        if (verifycode == '') {
            alert("验证码必须填写");
            return false;
        }
        if (dlmm == '') {
            alert("请填写密码");
            return false;
        } else {
            if (dlmm != qrdlmm) {
                alert("两次输入的密码不一样");
                return false;
            }
        }
        if (jymm == '') {
            alert("请填写交易密码");
            return false;
        } else {
            if (jymm != qrjymm) {
                alert("两次输入的密码不一样");
                return false;
            }
        }
        if (name == '') {
            alert("请填写真实姓名");
            return false;
        } else {
            var reg_name = /[\u4E00-\u9FA5]{2}/;
            if (!(reg_name.test(name))) {
                alert("真实姓名至少为2个中文");
                return false;
            }
        }
        $.post("index.php?act=login&op=register", {yhm:yhm,tel:tel,verifycode:verifycode,dlmm:dlmm,jymm:jymm,name:name,sex:sex}, function (data) {
            if (data.status == 0) {
                alert(data.info);
                $("#username").val(data.user);
                $("#username").css(" background-color","rgb(232, 240, 254) !important");  
                $(".forget-form").hide();
                $(".login-form").show();
            } else {
                alert(data.info);
            }
        }, 'json');
    });
//  忘记密码
    $('.forget_sublim').click(function (event) {
        var yhm = $('.yhm1').val();
        var tel = $('.tel1').val();
        var verifycode = $('.verifycode1').val();
        var dlmm = $('.dlmm1').val();
        var qrdlmm1 = $('.qrdlmm1').val();
        var jymm = $('.jymm1').val();
        var qrjymm1 = $('.qrjymm1').val();
        if (yhm == '') {
            alert("请填写用户名");
            return false;
        } else {
            if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
                alert("用户名不能使用中文");
                return false;
            }
        }
        if (tel == '') {
            alert("手机号必须填写");
            return false;
        }
        if (verifycode == '') {
            alert("验证码必须填写");
            return false;
        }
        if (dlmm == '') {
            alert("请填写密码");
            return false;
        } else {
            if (dlmm != qrdlmm1) {
                alert("两次输入的密码不一样!");
                return false;
            }
        }
        if (jymm == '') {
            alert("请填写交易密码");
            return false;
        } else {
            if (jymm!= qrjymm1) {
                alert("两次输入的密码不一样");
                return false;
            }
        }
        $.post("index.php?act=login&op=indexdo_forget",{yhm:yhm,tel:tel,verifycode:verifycode,dlmm:dlmm,jymm:jymm}, function (data) {
            if (data.status == 0) {
                alert(data.info);
                $("#username").val(data.user);
                $(".register-form").hide();
				$(".login-form").show();
            } else {
                alert(data.info);
            }
        }, 'json');
    });

    // $('#typ').change(function () {
    //     var uname = $('.uname').val();

    //     if ($(this).val() == 'email') {
    //         $("#t_msg").html("请输入您的邮箱");
    //         $(".email").attr('placeholder', '请输入您的邮箱');
    //         return false;
    //     }
    //     if (uname == '') {
    //         alert("请输入用户名");
    //         $(this).val("email");
    //         return false;
    //     }

    //     $(".email").attr('placeholder', '请输入密保答案');
    //     $.post("index.php?act=login&op=registerOp", {uname: uname}, function (data) {
    //         if (data.status) {
    //             $("#t_msg").html(data.info);
    //         } else {
    //             alert(data.info);
    //             $("#t_msg").html("请输入您的邮箱");
    //             $("#typ").val("email");
    //             $(".email").attr('placeholder', '请输入您的邮箱');
    //             return false;
    //         }
    //     }, 'json');
    // });
</script>