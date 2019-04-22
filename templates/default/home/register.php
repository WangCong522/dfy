<link rel="stylesheet" href="/Public/Home/css/sweetalert.css">
<script src="/Public/Home/js/sweetalert.min.js"></script>
<div class="">
    <div class="col-md-8 new_style1">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-user font-green"></i>
                    <span class="caption-subject bold uppercase">账号基本信息</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" class="form_info" method="post">
                    <div class="form-body">
                        <!-- <div class="form-group form-md-floating-label">
                            <input type="text" class="form-control" id="hybh" name="hybh" value="">
                            <label for="hybh">会员编号</label>
                            <span class="help-block"></span>
                        </div> -->
                        <div class="form-group form-md-floating-label">
                            <label for="yhm"><span style="color: red">*</span>用户名</label>
                            <input type="text" class="form-control" id="yhm" name="yhm"
                                   value="<?php echo $output['username'] ?>"
                                   placeholder="用于登录系统,不能使用中文"
                                   <?php if (C('reg_auto_username')) { ?> readonly<?php } ?>>
                            <span class="help-block"></span>
                        </div>
                        <!-- <div class="form-group form-md-floating-label">
                            <label for="email"><span style="color: red">*</span>邮箱</label>
                            <input type="email" class="form-control" id="email" name="email" value="cs1@qq.com"
                                   placeholder="请填写您的邮箱">

                        </div> -->
                        <div class="form-group form-md-floating-label">
                            <label for="tel" class="style_block">手机</label>
                            <input type="text" class="form-control phone_style1" id="tel" name="tel" value="">
							<button type="button" class="btn blue phone_style2" id="get_verifycode">获取验证码</button>
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="verifycode" class="">短信验证码</label>
                            <input type="text" class="form-control" id="verifycode" name="verifycode" value="" placeholder="验证码">

                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="dlmm"><span style="color: red">*</span>登录密码</label>
                            <input type="password" class="form-control" id="dlmm" name="dlmm" value="">

                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="qrdlmm"><span style="color: red">*</span>确认登录密码</label>
                            <input type="password" class="form-control" id="qrdlmm" name="qrdlmm" value="">
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="jymm"><span style="color: red">*</span>二级密码</label>
                            <input type="password" class="form-control" id="jymm" name="jymm" value=""
                                   placeholder="用于敏感信息操作时验证">

                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="qrjymm"><span style="color: red">*</span>确认交易密码</label>
                            <input type="password" class="form-control" id="qrjymm" name="qrjymm" value="">

                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="name"><span style="color: red">*</span>真实姓名:</label>
                            <input type="text" class="form-control" id="name" name="name" value="">
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="sex">性别:</label>
                            <label class="radio-inline">
                                <input type="radio" name="sex" value="1" checked> 男
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="sex" value="0" id="sex"> 女
                            </label>
                        </div>
                        <!--<div class="form-group form-md-floating-label">
                            <label for="mbwt">密保问题</label>
                            <select class="form-control edited" id="mbwt" name="mbwt">
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
                        </div>-->
                        <!--<div class="form-group form-md-floating-label">
                            <label for="mbda"><span style="color: red">*</span>密保答案</label>
                            <input type="text" class="form-control" id="mbda" name="mbda" value="222222">

                        </div>-->
                    </div>
                    <div class="form-actions noborder text-center">
                        <input type="submit" class="btn blue tj" value="提交信息"></input>
                    </div>
                </form>
                <iframe id="rfFrame" name="rfFrame" src="about:blank" style="display:none;"></iframe> 
            </div>
        </div>
    </div>
</div>
<script>
	document.forms[0].target="rfFrame";
	$('#get_verifycode').click(function(){
	    var doing = false;
	    var phone = $('#tel').val();
		if((/^1(3|4|5|7|8)\d{9}$/.test(phone))){
			$('#get_verifycode').html('已发送');
			
			$.post("./index.php?act=login&op=get_verifycode&phone="+phone,function (data) {
	         	console.log(data);
	     	});
			
			return true;
		}else{
			return false;
		}
	});
    $('.tj').click(function (event) {
        /*       var hybh= $('#hybh').val();*/
//      var yhm = $('#yhm').val();
//      var dlmm = $('#dlmm').val();
//      var qrdlmm = $('#qrdlmm').val();
//      var jymm = $('#jymm').val();
//      var qrjymm = $('#qrjymm').val();
//      var hyjb = $('#hyjb').val();
//      var mbwt = $('#mbwt').val();
//      //var mbda = $('#mbda').val();
//      var tjr = $('#tjr').val();
//      var jdr = $('#jdr').val();
//      var scwz = $('#scwz').val();
//      /*var yhkh = $('#yhkh').val();*/
//      var sfzh = $('#sfzh').val();
//      /*var khh = $('#khh').val();*/
//      var dz = $('#dz').val();
//      var tel = $('#tel').val();
//      var qq = $('#qq').val();
//      var email = $('#email').val();
//      var name = $('#name').val();
//      var ssname = $('#ssname').val();
        
        
        var yhm = $('#yhm').val();
        var tel = $('#tel').val();
        var verifycode = $('#verifycode').val();
        var dlmm = $('#dlmm').val();
        var qrdlmm = $('#qrdlmm').val();
        var jymm = $('#jymm').val();
        var qrjymm = $('#qrjymm').val();
        var name = $('#name').val(); 
        var sex=$("#sex").val();
        if (yhm == '') {
            sweetAlert("错误!", "请填写用户名", "error");
            return false;
        } else {
            if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
                sweetAlert("错误!", "用户名不能使用中文", "error");
                return false;
            }
        }
        if (tel == '') {
         sweetAlert("错误!", "手机号必须填写", "error");
         return false;
        }
        if (verifycode == '') {
         sweetAlert("错误!", "验证码必须填写", "error");
         return false;
        }
        if (dlmm == '') {
            sweetAlert("错误!", "请填写密码", "error");
            return false;
        } else {
            if (dlmm != qrdlmm) {
                sweetAlert("错误!", "两次输入的密码不一样", "error");
                return false;
            }
        }
        if (jymm == '') {
            sweetAlert("错误!", "请填写交易密码", "error");
            return false;
        } else {
            if (jymm != qrjymm) {
                sweetAlert("错误!", "两次输入的密码不一样", "error");
                return false;
            }
        }
        if (name == '') {
            sweetAlert("错误!", "请填写真实姓名", "error");
            return false;
        } else {
            var reg_name = /[\u4E00-\u9FA5]{2}/;
            if (!(reg_name.test(name))) {
                sweetAlert("错误!", "真实姓名至少为2个中文", "error");
                return false;
            }
        }
        
        
        
//      doing = true;
//   	$.post("index.php?act=login&op=indexdo_register", $('.form_info').serialize(), function (data) {
//   		
//       	if (data.status == 1) {
////           	swal("成功!", "激活该用户才能收益", "success");
////           	window.setTimeout('location.href="http://rrshop.gsxewl.com"', '1');
//       	} else {
//           	swal("失败!", data.info, "error");
//       	}
//       	doing = false;
//   	}, 'json');
		$.ajax({
			url: "index.php?act=login&op=indexdo_register",
			type: "post",
			data:{ 	yhm:yhm,
					tel:tel, 
					verifycode:verifycode, 
					dlmm:dlmm,
					jymm:jymm,
					name:name   
			},
			dataType: "json",
			async: true,
			success: function(data) {
				console.log(data)
				if (data.status == 1) {
	////           	swal("成功!", "激活该用户才能收益", "success");
	////           	window.setTimeout('location.href="http://rrshop.gsxewl.com"', '1');
		       	} else {
		           	swal("失败!", data.info, "error");
		       	}
			}
		});
    });
</script>