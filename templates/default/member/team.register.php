<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>用户注册</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-8 ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-user font-green"></i>
                    <span class="caption-subject bold uppercase">账号基本信息</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" class="form_info">
                    <div class="form-body">
                        <!-- <div class="form-group form-md-floating-label clearfix">
                            <input type="text" class="new_style_width9 form-control" id="hybh" name="hybh" value="">
                            <label for="hybh">会员编号</label>
                            <span class="help-block"></span>
                        </div> -->
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="yhm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>用户名</label>
                            <input type="text" class="new_style_width9 form-control" id="yhm" name="yhm"
                                   value="<?php echo $output['username'] ?>"
                                   placeholder="用于登录系统,不能使用中文"
                                <?php if (C('reg_auto_username')) { ?> readonly<?php } ?>>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="tel_reg" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1" style="display:block;"><span style="color:red;">*</span>手机</label>
                            <input type="text" class="new_style_width10 new_style_width12 form-control tel_style col-md-10" id="tel" name="tel" value="" style="max-width:88%;display:inline-block;">
                            <button type="button" class="btn blue gain new_style_width11 new_style_width13" id="get_verifycode">获取验证码</button>
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="yzm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"></span>验证码</label>
                            <input type="text" class="new_style_width9 form-control" id="验证码" name="验证码" value="">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="dlmm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>登录密码</label>
                            <input type="password" class="form-control new_style_width9 " id="dlmm" name="dlmm" value="">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="qrdlmm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>确认登录密码</label>
                            <input type="password" class="form-control new_style_width9 " id="qrdlmm" name="qrdlmm" value="">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="jymm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>二级密码</label>
                            <input type="password" class="form-control new_style_width9 " id="jymm" name="jymm" value=""
                                   placeholder="用于敏感信息操作时验证">

                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="qrjymm" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>确认交易密码</label>
                            <input type="password" class="form-control new_style_width9 " id="qrjymm" name="qrjymm" value="">

                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="group_id" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">会员级别</label>
                            <select class="form-control edited new_style_width9 " id="group_id" name="group_id">
                                <?php foreach ($output['groupList'] as $item) { ?>
                                    <option value="<?php echo $item['group_id'] ?>">
                                        <?php echo $item['name'] ?>--(￥<?php echo $item['lsk'] ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="name" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>真实姓名:</label>
                            <input type="text" class="new_style_width9 form-control" id="name" name="name" value="">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="sex" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">性别:</label>
                            <label class="radio-inline new_style_padding2">
                                <input type="radio" name="sex" value="1" checked id=""> 男
                            </label>
                            <label class="radio-inline new_style_padding2">
                                <input type="radio" name="sex" value="0"> 女
                            </label>
                        </div>
                        <!--<div class="form-group form-md-floating-label clearfix">
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
                        <!--<div class="form-group form-md-floating-label clearfix">
                            <label for="mbda"><span style="color: red">*</span>密保答案</label>
                            <input type="text" class="new_style_width9 form-control" id="mbda" name="mbda" value="222222">

                        </div>-->
                        <div class="portlet-title">
                            <div class="caption font-green">
                                <i class=" icon-globe font-green"></i>
                                <span class="caption-subject bold uppercase">网络关系</span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="tjr" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>推荐人</label>
                            <input type="text" class="new_style_width9 form-control" id="tjr" name="tjr"
                                   value="<?php echo $output['tjrInfo']['username'] ?>">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="tjr" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>报单中心名称</label>
                            <input type="text" class="new_style_width9 form-control" id="ssname" name="ssname"
                                   value="" required="required">
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="jdr" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>接点人</label>
                            <input type="text" class="new_style_width9 form-control" id="jdr" name="jdr"
                                   value="<?php echo $output['jdrInfo']['username']; ?>" readonly>
                        </div>
                        <div class="form-group form-md-floating-label clearfix">
                            <label for="scwz" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1"><span style="color: red">*</span>市场位置</label>
                            <select class="form-control edited new_style_width9" id="scwz" name="scwz">
                                <option value="1"
                                    <?php if ($output['jdrInfo']['wz'] == 1) { ?>
                                        selected=""<?php } ?>>
                                    左区
                                </option>
                                <option value="2"
                                    <?php if ($output['jdrInfo']['wz'] == 2) { ?>
                                        selected=""<?php } ?>>
                                    右区
                                </option>
                            </select>
                        </div>
                        <div class="portlet-title">
                            <div class="caption font-green">
                                <i class="icon-credit-card"></i>
                                <span class="caption-subject bold uppercase">个人基本信息(选填)</span>
                            </div>
                        </div>
                        <br>
                        <?php if ($output['setting']['idcard']) { ?>
                            <div class="form-group form-md-floating-label clearfix">
                                <label for="sfzh" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">身份证号</label>
                                <input type="text" class="new_style_width9 form-control" id="sfzh" name="sfzh" value="">

                            </div>
                            <?php
                        }
                        if ($output['setting']['addr']) {
                            ?>
                            <div class="form-group form-md-floating-label clearfix">
                                <label for="dz" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">地址</label>
                                <input type="text" class="new_style_width9 form-control" id="dz" name="dz" value="">

                            </div>
                            <?php
                        }
                        if ($output['setting']['tel']) {
                            ?>
                            <div class="form-group form-md-floating-label clearfix">
                                <label for="tel" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">手机</label>
                                <input type="text" class="new_style_width9 form-control" id="tel" name="tel" value="">

                            </div>
                            <?php
                        }
                        if ($output['setting']['qq']) {
                            ?>
                            <div class="form-group form-md-floating-label clearfix">
                                <label for="qq" class="new_style_width8 text_new_style_r new_style_padding2 new_style_margin1">QQ</label>
                                <input type="text" class="new_style_width9 form-control" id="qq" name="qq" value="">

                            </div>
                            <?php
                        }
                        ?>
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

    $('.tj').click(function (event) {
    	
        var yhm = $('#yhm').val();  			//用户名
        var tel_reg=$("#tel_reg").val();  		//注册电话
        var yzm=$("#yzm").val();  		//注册电话
        var dlmm = $('#dlmm').val(); 			//登录密码
        var qrdlmm = $('#qrdlmm').val();  		//确认登录密码
        var jymm = $('#jymm').val();			//交易密码
        var qrjymm = $('#qrjymm').val();		//确认交易密码
        var group_id = $('#group_id').val();	//会员级别
        var name = $('#name').val();  			//真实姓名
        var sex=$('input:radio:checked').val(); //性别
        var ssname = $('#ssname').val();		//报单中心
        var tjr = $('#tjr').val();              //推荐人
        var jdr = $('#jdr').val();				//接点人
        var scwz = $('#scwz').val();			//市场位置
         var dz = $('#dz').val();				//地址
        var tel = $('#tel').val();				//手机
//      var mbwt = $('#mbwt').val();
        //var mbda = $('#mbda').val();
        /*var yhkh = $('#yhkh').val();*/
//      var sfzh = $('#sfzh').val();
        /*var khh = $('#khh').val();*/
//      var qq = $('#qq').val();
//      var email = $('#email').val();
        
        
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
		if (tel == '') {
            alert("手机号必须填写");
            return false;
        }
        if (yzm == '') {
            alert("请填写验证码");
            return false;
        }
        if (jdr == '') {
            sweetAlert("错误!", "请填写接点人编号", "error");
            return false;
        }
        if (tjr == '') {
            sweetAlert("错误!", "请填写推荐人编号", "error");
            return false;
        }
        /*
         if (mbda == '') {
         sweetAlert("错误!", "请填写密保答案", "error");
         return false;
         }
         */
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
        if (ssname == '') {
            sweetAlert("错误!", "请填写服务站", "error");
            return false;
        }
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
        if (doing) {
            sweetAlert("已经提交，请耐心等待", "error");
            return false;
        }
        doing = true;
        $.post("./index.php?act=team&op=do_register", $('.form_info').serialize(), function (data) {
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