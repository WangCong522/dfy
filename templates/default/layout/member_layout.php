<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $output['html_title']; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="../../../Public/Home/new_style/css/new_style.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/assets/global/css/css@family=open+sans_3a400,300,600,700&subset=all.css" rel="stylesheet"
          type="text/css"/>
            <link href="../../../Public/Home/new_style/css/new_style.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Home/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/Public/Home/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Home/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/Public/Home/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="/Public/Home/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/Public/Home/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/Home/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="/Public/Home/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/Public/Home/css/sweetalert.css"/>
    <!-- END THEME LAYOUT STYLES -->
    <style type="text/css">
        a:hover {
            text-decoration: none;
        }
    </style>
    <!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md">
<!--[if lt IE 9]>
<script src="/Public/Home/assets/global/plugins/respond.min.js"></script>
<script src="/Public/Home/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/Public/Home/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/Home/assets/global/scripts/app.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/Home/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="/Public/Home/js/sweetalert.min.js"></script>

<!-- END THEME LAYOUT SCRIPTS -->
<script src="/Public/Home/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js"
        type="text/javascript"></script>
<script src="/Public/Home/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/">
                <img src="/Public/Home/assets/pages/img/login/logo.png" alt="logo" class="logo-default"
                     style="margin-top:8px;"/></a>
            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" id="magic_none" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- END QUICK SIDEBAR TOGGLER -->
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<!--蒙版-->
<div class="mengban"></div>
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse" id="magic_class">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200" style="padding-top: 20px">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <li class="sidebar-toggler-wrapper hide">
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler">
                        <span></span>
                    </div>
                    <!-- END SIDEBAR TOGGLER BUTTON -->
                </li>
                <li class="nav-item start <?php echo $output['index_index_active']; ?>" style="margin-top:5px;">
                    <a href="/">
                        <i class="icon-home"></i>
                        <span class="title">首页</span>
                        <span class="<?php echo $output['index_index_selected']; ?>"></span>
                    </a></li>
                <li class="nav-item start <?php echo $output['Zl_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-user"></i>
                        <span class="title">资料管理</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start <?php echo $output['Zl_UserInfo_dian']; ?>">
                            <a href="index.php?act=user&op=view" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">资料查看</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Zl_Edit_dian']; ?>">
                            <a href="index.php?act=user&op=edit" class="nav-link ">
                                <i class="icon-bulb"></i>
                                <span class="title">资料修改</span>
                                <span class="badge badge-success"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Zl_CheckPwd_dian']; ?>">
                            <a href="index.php?act=user&op=changePwd" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">修改密码</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Zl_UPLevel_dian']; ?>">
                            <a href="index.php?act=user&op=UPLevel" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">提升等级</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item start <?php echo $output['Team_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-users"></i>
                        <span class="title">团队管理</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start <?php echo $output['Team_sctj_dian']; ?>">
                            <a href="index.php?act=team&op=sctj" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">市场统计</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Team_register_dian']; ?>">
                            <a href="index.php?act=team&op=register" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">注册会员</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Team_zhituiList_dian']; ?>">
                            <a href="index.php?act=team&op=zhituiList" class="nav-link ">
                                <i class="icon-share"></i>
                                <span class="title">直推列表</span>
                                <span class="badge badge-success"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Team_ss_dian']; ?>">
                            <a href="index.php?act=team&op=ServiceStation" class="nav-link ">
                                <i class="icon-briefcase"></i>
                                <span class="title">报单中心</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Team_activation_dian']; ?>">
                            <a href="index.php?act=team&op=activation" class="nav-link ">
                                <i class="icon-bulb"></i>
                                <span class="title">待激活会员</span>
                                <span class="badge badge-success"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--<li class="nav-item start <?php echo $output['Net_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-sitemap"></i>
                        <span class="title">网络图</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start <?php echo $output['Net_network_dian']; ?>">
                            <a href="index.php?act=net&op=network" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">组织结构图</span>
                            </a>
                        </li>
                    </ul>
                </li>-->
                <li class="nav-item start <?php echo $output['Shop_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-truck"></i> <span class="title">购物中心</span> <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start hesuan <?php echo $output['User_shop_dian']; ?>"><a
                                href="index.php?act=goods&op=shop" class="nav-link "> <i class="icon-bar-chart"></i>
                                <span class="title">购物商城</span> </a></li>
                        <li class="nav-item start hesuan <?php echo $output['User_cart_dian']; ?>"><a
                                href="index.php?act=goods&op=userCart" class="nav-link "> <i class="icon-bar-chart"></i>
                                <span class="title">我的购物车</span> </a></li>
                        <li class="nav-item start hesuan <?php echo $output['User_orders_dian']; ?>"><a
                                href="index.php?act=goods&op=userOrders" class="nav-link "> <i
                                    class="icon-bar-chart"></i> <span class="title">我的订单</span> </a></li>
                       <!--  <li class="nav-item start hesuan <?php //echo $output['User_orders_dian']; ?>"><a
                                href="index.php?act=goods&op=userPrizeOrders" class="nav-link "> <i
                                    class="icon-bar-chart"></i> <span class="title">会员奖品列表</span> </a></li> -->
                    </ul>
                </li>
                <li class="nav-item start <?php echo $output['Money_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="fa fa-dollar"></i>
                        <span class="title">财务中心</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start <?php echo $output['Money_Money_dian']; ?>">
                            <a href="index.php?act=fin&op=change" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">货币兑换</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Hyzz_dian']; ?>">
                            <a href="index.php?act=fin&op=tran" class="nav-link ">
                                <i class="icon-bulb"></i>
                                <span class="title">内部转账</span>
                                <span class="badge badge-success"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Hk_dian']; ?>">
                            <a href="index.php?act=fin&op=hk" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">汇款充值</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Tx_dian']; ?>">
                            <a href="index.php?act=fin&op=tx" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">申请提现</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Cwmx_dian']; ?>">
                            <a href="index.php?act=fin&op=cwmx" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">财务明细</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Jjmx_dian']; ?>">
                            <a href="index.php?act=fin&op=detail" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">奖金明细</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Money_Rjjmx_dian']; ?>">
                            <a href="index.php?act=fin&op=ddt" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">日奖金明细</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item start <?php echo $output['Message_index_active']; ?>">
                    <a href="javascript:;" class="nav-link nav-toggle ">
                        <i class="icon-envelope"></i>
                        <span class="title">站内信</span>
                        <span class="arrow"></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="nav-item start <?php echo $output['Message_index_selected']; ?>">
                            <a href="index.php?act=article&op=send" class="nav-link ">
                                <i class="icon-bar-chart"></i>
                                <span class="title">站内信</span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Message_MessageSj_selected']; ?>">
                            <a href="index.php?act=article&op=MessageSj" class="nav-link ">
                                <i class="icon-bulb"></i>
                                <span class="title">收件箱</span>
                                <span class="badge badge-success"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Message_MessageFj_selected']; ?>">
                            <a href="index.php?act=article&op=MessageFj" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">发件箱</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                        <li class="nav-item start <?php echo $output['Message_Gonggao_selected']; ?>">
                            <a href="index.php?act=article&op=article" class="nav-link ">
                                <i class="icon-graph"></i>
                                <span class="title">公告</span>
                                <span class="badge badge-danger"></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--                     <li class="heading">
                    <h3 class="uppercase">Features</h3>
                </li> -->
                <li class="nav-item start" style="margin-top:5px;">
                    <a href="index.php?act=login&op=logout">
                        <i class="fa fa-sign-out"></i>
                        <span class="title">安全退出</span>
                        <span class="index.php?act=login&op=logout"></span>
                    </a></li>
            </ul>
            <!-- END SIDEBAR MENU -->
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content cont">
            <?php require_once($tpl_file); ?>

        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN QUICK SIDEBAR -->
    <a href="javascript:;" class="page-quick-sidebar-toggler">
        <i class="icon-login"></i>
    </a>
   
<!-- END FOOTER -->


<script>
    jQuery(document).ready(function ($) {
        $('.page-content > .row').css({
            marginLeft: '0px',
            marginRight: '0px'
        });
    });
    
    $('.responsive-toggler').click(function(){
    	$('.navbar-collapse').addClass('navbar-collapse-new-style1');
    	$('.mengban').show();
    });
    $('.mengban').click(function(){
    	$('.navbar-collapse').removeClass('navbar-collapse-new-style1');
    	$('.mengban').hide();
    });
    
    //手机端不执行bootstrap拆叠效果
    if($(window).width()<=480){
		$('#magic_none').attr({'data-toggle':'','data-target':''});
		$('#magic_class').removeClass('collapse');
	}else{
		$('#magic_none').attr({'data-toggle':'collapse','data-target':'.navbar-collapse'});
		$('#magic_class').addClass('collapse');
	}
    $(window).resize(function () {
    	if($(window).width()<=480){
			$('#magic_none').attr({'data-toggle':'','data-target':''});
			$('#magic_class').removeClass('collapse');
		}else{
			$('#magic_none').attr({'data-toggle':'collapse','data-target':'.navbar-collapse'});
			$('#magic_class').addClass('collapse');
		}
	});
</script>

</body>

</html>