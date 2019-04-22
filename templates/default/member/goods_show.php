<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<style>
    .form-horizontal .control-label {
        text-align: center;
    }

    .detail img {
        max-width: 100%;
    }
</style>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>商品详情</span>
        </li>
    </ul>
</div>

<div class="row" style="margin-top: 10px">
    <div class="col-md-3">
        <div class="thumbnail">
            <img src="<?php echo $output['goods']['thumb']; ?>" alt="..." class="img-responsive" alt="Responsive image">
        </div>
    </div>
    <div class="col-md-9">
        <form class="form-horizontal form_info" role="form" onsubmit="return false">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">名称：</label>
                <div class="col-sm-10 col-xs-7 new_style_padding_none">
                    <input type="text" value="<?php echo $output['goods']['goods_name']; ?>" class="form-control"
                           disabled/>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">类型：</label>
                <div class="col-sm-10 col-xs-7 new_style_padding_none">
                    <input type="text" value="<?php echo $output['goods']['typename']; ?>" class="form-control"
                           disabled/>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">价钱：</label>
                <div class="col-sm-10 col-xs-7 new_style_padding_none">
                    <input type="text" value="<?php echo $output['goods']['price']; ?>" class="form-control" disabled/>
                </div>
            </div>
            <div class="form-group">
                <?php if ($output['goods']['typeid'] == 6) { ?>
                    <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">赠送数量：</label>
                    <div class="col-sm-2 col-xs-7 new_style_padding_none">
                        <input type="text" value="<?php echo $output['goods']['give']; ?>" name="num" id="num" class="form-control" disabled/>
                    </div>
                <?php }else{ ?>
                    <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">购买数量：</label>
                    <div class="col-sm-2 col-xs-7 new_style_padding_none new_style_margin3">
                        <input type="text" value="1" name="num" id="num" class="form-control"/>
                    </div>
                <?php  }  ?>
                <label for="inputPassword3" class="col-sm-1 control-label col-xs-4 new_style_padding2 text_new_style_r_1">库存：</label>
                <div class="col-sm-2 col-xs-7 new_style_padding_none new_style_margin3">
                    <input type="text" value="<?php echo $output['goods']['stock']; ?>" class="form-control" disabled/>
                </div>
                <label for="inputPassword3" class="col-sm-1 control-label col-xs-4 new_style_padding2 text_new_style_r_1">运费：</label>
                <div class="col-sm-2 col-xs-7 new_style_padding_none">
                    <input type="text" value="<?php echo $output['goods']['ship_fee']; ?>" class="form-control"
                           disabled/>
                </div>
            </div>
            <?php if ($output['goods']['typeid'] != 1) { ?>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">收货人：</label>
                    <div class="col-sm-2 col-xs-7 new_style_padding_none new_style_margin3">
                        <input type="text" value="<?php echo $output['userInfo']['name']; ?>" name="consignee"
                               id="consignee" class="form-control"/>
                    </div>
                    <label for="inputPassword3" class="col-sm-1 control-label col-xs-4 new_style_padding2 text_new_style_r_1">收货地址：</label>
                    <div class="col-sm-3 col-xs-7 new_style_padding_none new_style_margin3">
                        <input type="text" value="<?php echo $output['userInfo']['address']; ?>" name="address"
                               id="address" class="form-control"/>
                    </div>
                    <label for="inputPassword3" class="col-sm-1 control-label col-xs-4 new_style_padding2 text_new_style_r_1">联系电话：</label>
                    <div class="col-sm-3 col-xs-7 new_style_padding_none">
                        <input type="text" value="<?php echo $output['userInfo']['tel']; ?>" name="tel" id="tel"
                               class="form-control"/>
                    </div>
                </div>
            <?php } ?>

            <?php if ($output['goods']['typeid'] != 6) { ?>
             <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label col-xs-4 new_style_padding2 text_new_style_r_1">选择购买方式：</label>
                <div class="dropup col-sm-6 col-xs-7 new_style_padding_none">
                    <select class="form-control" id="sbuy_type" name='sbuy_type'>
                        <option value="">下拉选择支付方式</option>
                        <option value="gwb">购物币支付</option>
                        <option value="jf">会员积分支付</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-1 col-xs-6 text-right">
                    <input type="hidden" value="<?php echo $output['goods']['gid']; ?>" name="gid">
                    <button type="submit" class="btn btn-default buy_now">立即购买</button>
                </div>
                <div class="col-sm-offset-2 col-sm-1 col-xs-6 text-left">
                    <input type="hidden" value="<?php echo $output['goods']['gid']; ?>" name="gid">
                    <button type="submit" class="btn btn-default add_cart">加入购物车</button>
                </div>
            </div>
            <?php }else{ ?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-1">
                    <input type="hidden" value="<?php echo $output['goods']['gid']; ?>" name="gid">
                    <button type="submit" class="btn btn-default buy_prize">立即领取</button>
                </div>
            </div>
            <?php } ?>

        </form>
    </div>
</div>
<div class="row detail">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">商品详情</div>
            <div class="panel-body">
                <?php echo $output['goods']['content']; ?>
            </div>
        </div>
    </div>
</div>
<style>
	.mengban_two{
		display: none;
		position: fixed;
		z-index: 998;
		top: 0;
		left: 0;
		width: 100%;
		height: 100vh;
		background: rgba(0,0,0,.3);
	}
	.new_style1{
		top: 50%;
		left: 50%;
		transform:translate(-50%,-50%);
		-ms-transform:translate(-50%,-50%); 	/* IE 9 */
		-moz-transform:translate(-50%,-50%); 	/* Firefox */
		-webkit-transform:translate(-50%,-50%); /* Safari 和 Chrome */
		-o-transform:translate(-50%,-50%); 	/* Opera */
	}
	.new_style1 .portlet{
		position: relative;
	}
	/*.guanbi{
		cursor: pointer;
		width: 30px;
		height: 30px;
		border-radius: 50%;
		text-align: center;
		line-height: 30px;
		background: rgba(0,0,0,.1);
		position: absolute;
		top: 15px;
		right: 20px;
	}
	.guanbi:hover{
		background: rgba(0,0,0,.3);
	}*/
</style>
<div class="mengban_two">
    <div class="col-md-8 new_style1">
        <div class="portlet light bordered">
    		<!--<div class="guanbi">X</div>-->
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-user font-green"></i>
                    <span class="caption-subject bold uppercase">请补充会员资料</span>
                </div>
            </div>
            <div class="portlet-body form">
                <form role="form" class="form_info">
                    <div class="form-body">
                        <div class="portlet-title">
                            <div class="caption font-green">
                                <i class=" icon-globe font-green"></i>
                                <span class="caption-subject bold uppercase">网络关系</span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group form-md-floating-label">
                            <label for="tjr"><span style="color: red">*</span>推荐人</label>
                            <input type="text" class="form-control" id="tjr" name="tjr"
                                   value="<?php echo $output['tjrInfo']['username'] ?>">
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="ssname"><span style="color: red">*</span>报单中心名称</label>
                            <input type="text" class="form-control" id="ssname" name="ssname"
                                   value="" required="required">
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="jdr"><span style="color: red">*</span>接点人</label>
                            <input type="text" class="form-control" id="jdr" name="jdr"
                                   value="<?php echo $output['jdrInfo']['username']; ?>" >
                        </div>
                        <div class="form-group form-md-floating-label">
                            <label for="scwz"><span style="color: red">*</span>市场位置</label>
                            <select class="form-control edited" id="scwz" name="scwz">
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
                        <!--<div class="form-group form-md-floating-label">
                            <label for="dz">地址</label>
                            <input type="text" class="form-control" id="dz" name="dz" value="">
                        </div>-->
                    </div>
                    <div class="form-actions noborder text-center">
                        <button type="button" class="btn blue tj guanbi">提交信息</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var doing = false;

    //
    $('.buy_prize').click(function(){
        var num = $('#num').val();
        var consignee = $('#consignee').val();
        var address = $('#address').val();
        var tel = $('#tel').val();
        var buy_type = $('#sbuy_type').val();

        if (consignee == '') {
            sweetAlert("错误!", "请填写收货人", "error");
            return false;
        }
        if (address == '') {
            sweetAlert("错误!", "请填写收货地址", "error");
            return false;
        }
        if (tel == '') {
            sweetAlert("错误!", "请填写联系电话", "error");
            return false;
        }
        var mytels = /^[\d-]*$/;

        if (!mytels.test(tel)) {
            sweetAlert("错误!", "请输入正确联系电话", "error");
            return false;
        }

        if (doing) {
            sweetAlert("已经提交，请耐心等待", "error");
            return false;
        }
        doing = true;

        $.post("index.php?act=goods&op=buy_prize", $('.form_info').serialize(), function (data) {
            if (data.status) {
                swal("成功!", "本次领取成功！", "success");
                window.setTimeout('location.href="index.php?act=goods&op=userOrders"', '1000');
            } else {
                swal("失败!", data.info, "error");
            }
            doing = false;
        }, 'json');

    });

    $('.buy_now').click(function (event) {

        var num = $('#num').val();
        var consignee = $('#consignee').val();
        var address = $('#address').val();
        var tel = $('#tel').val();
        var buy_type = $('#sbuy_type').val();

        if (num == '') {
            sweetAlert("错误!", "请填写购买数量", "error");
            return false;
        }
        var mynum = /^\+?[1-9]\d*$/;

        if (!mynum.test(num)) {
            sweetAlert("错误!", "购买数量必须为正整数", "error");
            return false;
        }
        <?php if($output['goods']['typeid'] != 1){?>
        if (consignee == '') {
            sweetAlert("错误!", "请填写收货人", "error");
            return false;
        }
        if (address == '') {
            sweetAlert("错误!", "请填写收货地址", "error");
            return false;
        }
        if (tel == '') {
            sweetAlert("错误!", "请填写联系电话", "error");
            return false;
        }
        var mytels = /^[\d-]*$/;

        if (!mytels.test(tel)) {
            sweetAlert("错误!", "请输入正确联系电话", "error");
            return false;
        }

        if (buy_type == '') {
            sweetAlert("错误!", "请选择购买方式后进行支付", "error");
            return false;
        }

        <?php }?>

        if (doing) {
            sweetAlert("已经提交，请耐心等待", "error");
            return false;
        }
        doing = true;

        $.post("index.php?act=goods&op=order_buynot", $('.form_info').serialize(), function (data) {
            if (data.status) {
                swal("成功!", "本次购买成功！", "success");
                $('.sa-confirm-button-container > .confirm').on('click', function(){
				    $('.mengban_two').show();
				});
				$('.guanbi').on('click', function(){
				    $('.mengban_two').hide();

				});
            } else {
                swal("失败!", data.info, "error");
            }
            doing = false;
        }, 'json');
    });

    $('.add_cart').click(function (event) {

        var num = $('#num').val();
        var consignee = $('#consignee').val();
        var address = $('#address').val();
        var tel = $('#tel').val();

        if (num == '') {
            sweetAlert("错误!", "请填写购买数量", "error");
            return false;
        }
        var mynum = /^\+?[1-9]\d*$/;

        if (!mynum.test(num)) {
            sweetAlert("错误!", "购买数量必须为正整数", "error");
            return false;
        }
        <?php if($output['goods']['typeid'] != 1){?>
        if (consignee == '') {
            sweetAlert("错误!", "请填写收货人", "error");
            return false;
        }
        if (address == '') {
            sweetAlert("错误!", "请填写收货地址", "error");
            return false;
        }
        if (tel == '') {
            sweetAlert("错误!", "请填写联系电话", "error");
            return false;
        }
        var mytels = /^[\d-]*$/;

        if (!mytels.test(tel)) {
            sweetAlert("错误!", "请输入正确联系电话", "error");
            return false;
        }
        <?php }?>

        if (doing) {
            sweetAlert("已经提交，请耐心等待", "error");
            return false;
        }
        doing = true;

        $.post("index.php?act=goods&op=member_cartis", $('.form_info').serialize(), function (data) {
            if (data.status) {
                swal("成功!", "加入购物车成功！", "success");
                //window.setTimeout('location.href="index.php?act=goods&op=userOrders"', '1000');
            } else {
                swal("失败!", data.info, "error");
            }
            doing = false;
        }, 'json');
    });
    
//  弹窗提交信息
	$(".tj").click(function(event){
		var tjr=$("#tjr").val();        	  //推荐人
		var ssname=$("#ssname").val();        //报单中心
		var jdr=$("#jdr").val();        	  //接点人
		var scwz=$("#scwz").val();      	  //市场位置
		$.post("index.php?act=goods&op=member_tjr",{tjr:tjr,ssname:ssname,jdr:jdr,scwz:scwz},function(data){
			if(data.status){
				swal("成功!", "填写网络关系成功！！", "success");
				    window.setTimeout('location.href="index.php?act=goods&op=userOrders"', '1000');
			}else{
				swal("失败!", data.info, "error");
			}
            doing = false;
		}, 'json')
	})
</script>