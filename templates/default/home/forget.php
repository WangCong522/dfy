<link rel="stylesheet" href="/Public/Home/css/sweetalert.css">
<script src="/Public/Home/js/sweetalert.min.js"></script>
<div class="">
    <div class="col-md-8 new_style1">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-user font-green"></i>
                    <span class="caption-subject bold uppercase">忘记密码？</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" class="form_info">
                    <div class="form-body">
                        <!-- <div class="form-group form-md-floating-label">
                            <input type="text" class="form-control" id="hybh" name="hybh" value="">
                            <label for="hybh">会员编号</label>
                            <span class="help-block"></span>
                        </div> -->
                        <div class="form-group form-md-floating-label">
                            <label for="yhm"><span style="color: red">*</span>用户名</label>
                            <input type="text" class="form-control" id="yhm" name="yhm" value="" placeholder="用于登录系统,不能使用中文">
                            <span class="help-block"></span>
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
                            <input type="password" class="form-control" id="jymm" name="jymm" value="" placeholder="用于敏感信息操作时验证">
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="qrjymm"><span style="color: red">*</span>确认交易密码</label>
                            <input type="password" class="form-control" id="qrjymm" name="qrjymm" value="">
                        </div>
                    	<div class="form-group form-md-floating-label">
                            <label for="tel" class="style_block">手机</label>
                            <input type="text" class="form-control phone_style1" id="tel" name="tel" value="">
							<button type="button" class="btn blue phone_style2" id="get_yzm">获取验证码</button>
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="yzm" class="">短信验证码</label>
                            <input type="text" class="form-control" id="yzm" name="yzm" value="" placeholder="验证码">
                        </div>
                    </div>
                    <div class="form-actions noborder text-center">
                        <button type="button" class="btn blue tj">提交信息</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var doing = false;
	$('#get_yzm').click(function(){
	    var doing = false;
	    var phone = $('#tel').val();
		if((/^1(3|4|5|7|8)\d{9}$/.test(phone))){
			$('#get_yzm').html('已发送');
			return true;
		}else{
			return false;
		}
	});
    $('.tj').click(function (event) {
    	
    	var yhm = $('#yhm').val();
        var dlmm = $('#dlmm').val();
        var qrdlmm = $('#qrdlmm').val();
        var jymm = $('#jymm').val();
        var qrjymm = $('#qrjymm').val();
        var tel = $('#tel').val();
        var yzm = $('#yzm').val();
    	
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
        /* if (tel == '' && qq == '') {
         sweetAlert("错误!", "手机号和QQ号必须填写一项", "error");
         return false;
         }*/
        /*if (dz == '') {
         sweetAlert("错误!", "请填写地址", "error");
         return false;
         } */
        /*if (sfzh == '') {
         sweetAlert("错误!", "请填写身份证号", "error");
         return false;
         }*/
        /* if (yhkh == '') {
         sweetAlert("错误!", "请填写银行卡号", "error");
         return false;
         }*/

//      if (email == '') {
//          sweetAlert("错误!", "请填写邮箱", "error");
//          return false;
//      }
//      var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
//      if (!myreg.test(email)) {
//          alert('提示\n\n请输入有效的E_mail！');
//          return false;
//      }
//      if (jdr == '') {
//          sweetAlert("错误!", "请填写接点人编号", "error");
//          return false;
//      }
//      if (tjr == '') {
//          sweetAlert("错误!", "请填写推荐人编号", "error");
//          return false;
//      }
//      /*
//       if (mbda == '') {
//       sweetAlert("错误!", "请填写密保答案", "error");
//       return false;
//       }
//       */
//      if (name == '') {
//          sweetAlert("错误!", "请填写真实姓名", "error");
//          return false;
//      } else {
//          var reg_name = /[\u4E00-\u9FA5]{2}/;
//          if (!(reg_name.test(name))) {
//              sweetAlert("错误!", "真实姓名至少为2个中文", "error");
//              return false;
//          }
//      }
//      if (ssname == '') {
//          sweetAlert("错误!", "请填写服务站", "error");
//          return false;
//      }
//      if (yhm == '') {
//          sweetAlert("错误!", "请填写用户名", "error");
//          return false;
//      } else {
//          if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
//              sweetAlert("错误!", "用户名不能使用中文", "error");
//              return false;
//          }
//      }
//      if (dlmm == '') {
//          sweetAlert("错误!", "请填写密码", "error");
//          return false;
//      } else {
//          if (dlmm != qrdlmm) {
//              sweetAlert("错误!", "两次输入的密码不一样", "error");
//              return false;
//          }
//      }
//      if (jymm == '') {
//          sweetAlert("错误!", "请填写交易密码", "error");
//          return false;
//      } else {
//          if (jymm != qrjymm) {
//              sweetAlert("错误!", "两次输入的密码不一样", "error");
//              return false;
//          }
//      }
		
		if (yhm == '') {
            sweetAlert("错误!", "请填写用户名", "error");
            return false;
        } else {
            if (/.*[\u4e00-\u9fa5]+.*$/.test(yhm)) {
                sweetAlert("错误!", "用户名不能使用中文", "error");
                return false;
            }
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
        if (tel == '') {
         sweetAlert("错误!", "手机号必须填写", "error");
         return false;
        }
        if (yzm == '') {
         sweetAlert("错误!", "验证码必须填写", "error");
         return false;
        }
        
        
		
        if (doing) {
            sweetAlert("已经提交，请耐心等待", "error");
            return false;
        }

        doing = true;

     $.post("index.php?act=team&op=do_register", $('.form_info').serialize(), function (data) {
         if (data.status) {
             swal("成功!", "激活该用户才能收益", "success");
             window.setTimeout('location.href="index.php?act=team&op=activation"', '1000');
         } else {
             swal("失败!", data.info, "error");
         }
         doing = false;
     }, 'json');

    });
</script>