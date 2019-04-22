<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="row ">
    <div class="row">
        <?php if (is_array($output['pic_list'])) { ?>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php foreach ($output['pic_list'] as $key => $value) { ?>
                        <li data-target="#carousel-example-generic"
                            data-slide-to="<?php echo $key; ?>"
                            <?php if ($key == 0) { ?>
                            class="active">
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <?php foreach ($output['pic_list'] as $key => $value) { ?>
                        <div class="item <?php if ($key == 0) { ?>active<?php } ?>">
                            <img src="<?php echo $value; ?>" alt="...">
                        </div>
                    <?php } ?>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        <?php } ?>
        <br/>
        <div class="col-md-2">
            <a class="dashboard-stat dashboard-stat-v2 red" href="index.php?act=fin&op=Hk">
                <div class="visual">
                    <i class="fa fa-credit-card"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <span data-counter="counterup"
                              data-value="<?php echo $output['UserInfo']['bao_balance']; ?>"><?php echo $output['UserInfo']['bao_balance']; ?></span>¥
                    </div>
                    <div class="desc">报单币</div>
                    <div class="desc">立即充值</div>
                </div>
            </a>
        </div>

        <div class="col-md-2">

            <a class="dashboard-stat dashboard-stat-v2 green" href="index.php?act=fin&op=Hk">

                <div class="visual">

                    <i class="fa fa-credit-card"></i>

                </div>

                <div class="details">

                    <div class="number">

                        <span data-counter="counterup"

                              data-value="<?php echo $output['UserInfo']['ji_balance']; ?>"><?php echo $output['UserInfo']['ji_balance']; ?></span>¥

                    </div>

                    <div class="desc">积分</div>

                    <div class="desc">立即充值</div>

                </div>

            </a>

        </div>

        <div class="col-md-2">

            <a class="dashboard-stat dashboard-stat-v2 purple" href="#">

                <div class="visual">

                    <i class="fa fa-credit-card"></i>

                </div>

                <div class="details">

                    <div class="number">

                        <span data-counter="counterup"

                              data-value="<?php echo $output['UserInfo']['zhang_balance']; ?>"><?php echo $output['UserInfo']['zhang_balance']; ?></span>¥

                    </div>

                    <div class="desc">购物币</div>

                </div>

            </a>

        </div>
        <?php if(count($output['UserInfo']['to_receive']) > 0){ ?>
            <div class="col-md-2">

                <a class="dashboard-stat dashboard-stat-v2 blue" href="index.php?act=goods&op=shop&typeid=6">

                    <div class="visual">

                        <i class="fa fa-credit-card"></i>

                    </div>

                    <div class="details">

                        <div class="number">

                            <span data-counter="counterup"

                                  data-value="<?php echo count($output['UserInfo']['to_receive']); ?>"><?php echo count($output['UserInfo']['to_receive']); ?></span>件

                        </div>

                        <div class="desc">待领取商品</div>

                    </div>

                </a>

            </div>
        <?php }?>
       <!-- <div class="col-md-2">

            <a class="dashboard-stat dashboard-stat-v2 purple" href="#">

                <div class="visual">

                    <i class="fa fa-credit-card"></i>

                </div>

                <div class="details">

                    <div class="number">

                        <span data-counter="counterup"

                              data-value="<?php /*echo $output['UserInfo']['jiangjin_total']; */?>"><?php /*echo $output['UserInfo']['jiangjin_total']; */?></span>¥

                    </div>

                    <div class="desc">累计奖金</div>

                </div>

            </a>

        </div>-->

        <div class="col-md-4">

            <a class="dashboard-stat dashboard-stat-v2 red" href="#" style="background-color: #6e633d;">

                <div class="visual">

                    <i class="fa fa-credit-card"></i>

                </div>

                <div class="details">

                    <div class="desc" style="padding:20px;font-size:14px;line-height:25px;text-align:left;">

                        <span style="font-size:20px;font-weight:bolder;">重要提示:</span>

                        如果发现奖金不符，请首先详细了解系统奖金制度，再向管理员反映。

                    </div>

                </div>

            </a>

        </div>

    </div>

    <!-- END DASHBOARD STATS -->

    <div class="clearfix">

    </div>

    <div class="row">


        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

            <div class="mt-element-ribbon bg-grey-steel" style="padding-left: 0px;padding-top: 0px;padding-bottom:5px;">

                <div class="row">

                    <div class="col-xs-3">

                        <img src="/Public/Home/images/header1.jpg" style="" alt="..." class="img-responsive img-circle">

                    </div>

                    <div class="col-xs-9" style="margin-top:20px;">

                        <span>

                           <?php echo $output['UserInfo']['group_name']; ?>

                            ：<?php echo $output['UserInfo']['username']; ?>
                            <span style="color: red">(<?php echo $output['UserInfo']['star']; ?>星)</span>

                        </span><br/>

                        <span style="color: red">欢迎回来</span>

                    </div>

                </div>

                <div style="margin-left: 15px;margin-top:5px;" class="info">
                    <span>PV：<?php echo $output['UserInfo']['pv']; ?></span><br/>
                    <span>报单币：<?php echo $output['UserInfo']['bao_balance']; ?></span><br/>
                    <span>积分：<?php echo $output['UserInfo']['ji_balance']; ?></span><br/>
                    <span>消费币：<?php echo $output['UserInfo']['zhang_balance']; ?></span><br/>
                    <span>报单中心：
                        <?php if ($output['UserInfo']['is_biz']) { ?>
                            是
                        <?php } else { ?>
                            否
                        <?php } ?>
                    </span><br/>
                    <!--<span>左市场业绩：<?php /*echo $output['NetInfo']['all_l_count']; */?></span><br/>
                    <span>右市场业绩：<?php /*echo $output['NetInfo']['all_r_count']; */?></span><br/>
                    <span>左市剩余场业绩：<?php /*echo $output['NetInfo']['l_count']; */?></span><br/>
                    <span>右市剩余场业绩：<?php /*echo $output['NetInfo']['r_count']; */?></span><br/>-->

                    <span>左区总人数：<?php echo $output['NetInfo']['l_num']; ?></span><br/>
                    <span>右区总人数：<?php echo $output['NetInfo']['r_num']; ?></span><br/>
                    <span>左区当天注册人数：<?php echo $output['NetInfo']['l_num_today']; ?></span><br/>
                    <span>右区当注册人数：<?php echo $output['NetInfo']['r_num_today']; ?></span><br/>
                    <span>推荐人数：<?php echo $output['tjr']; ?></span><br/>
                </div>

                <div class="row">

                    <div class="col-xs-4 inbox">

                        <a href="index.php?act=article&op=MessageSj" type="button" class="btn"

                           style="background-color: #20BF55;color:white;margin-top: 5px;">收件箱</a>

                    </div>

                    <div class="col-xs-4 inbox">

                        <a href="index.php?act=user&op=CheckPwd" type="button" class="btn"

                           style="background-color: #20BF55;color:white;margin-top: 5px;">修改密码</a>

                    </div>

                    <div class="col-xs-4 inbox">

                        <a href="index.php?act=user&op=view" type="button" class="btn"

                           style="background-color: #20BF55;color:white;margin-top: 5px;">账户查询</a>

                    </div>

                </div>

                <div

                    class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info uppercase">

                    <div class="ribbon-sub ribbon-clip ribbon-right"></div>

                    会员信息

                </div>


            </div>

        </div>

        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
            <!-- BEGIN PORTLET-->
            <div class="portlet paddingless">
                <div class="portlet-body">
                    <!--BEGIN TABS-->
                    <div class="tabbable tabbable-custom">
                        <ul class="nav nav-tabs">
                            <?php if (is_array($output['artType'])) { ?>
                                <?php foreach ($output['artType'] as $key => $item) { ?>
                                    <li <?php if ($key == 0){ ?>class="active"<?php } ?>>
                                        <a href="#tab_1_<?php echo $key ?>"
                                           data-toggle="tab"><?php echo $item['typename'] ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php
                            if (is_array($output['artType'])) { ?>
                                <?php foreach ($output['artType'] as $key => $item) { ?>
                                    <div class="tab-pane <?php if ($key == 0) { ?>active<?php } ?>"
                                         id="tab_1_<?php echo $key; ?>">
                                        <div class="scroller" style="height: 250px;" data-always-visible="1"
                                             data-rail-visible="0">
                                            <ul class="feeds">
                                                <?php
                                                if (is_array($item['artlist'])) {
                                                    foreach ($item['artlist'] as $itemlist) {
                                                        ?>
                                                        <li>
                                                            <div class="col1" style="width: 95%">
                                                                <div class="cont">
                                                                    <div class="cont-col1">
                                                                        <div class="label label-sm label-success">
                                                                            <i class="fa fa-bell-o"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cont-col2">
                                                                        <div class="desc">
                                                                            <a target="_blank"
                                                                               href="index.php?act=article&op=show&id=<?php echo $itemlist['aid'] ?>">
                                                                                <?php echo $itemlist['title']; ?>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col2">
                                                                <div class="date" style="width: 100px;">
                                                                    <?php echo date("Y-m-d", $itemlist['addtime']); ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <li>
                                                    <div class="col1" style="width: 95%">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-success">
                                                                    <i class="fa fa-bell-o"></i>
                                                                </div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc">
                                                                    <a target="_blank"
                                                                       href="index.php?act=article&op=article&id=<?php echo $item['id'] ?>">
                                                                        更多信息
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date" style="width: 100px;">
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <!--END TABS-->
                </div>

            </div>

            <!-- END PORTLET-->

        </div>

    </div>

    <div class="clearfix">

    </div>

    <div class="row " style="margin-left: 0px; margin-right: 0px;">

        <div class="col-md-12">

            <div class="page-header">

                <h1>

                    <small>商品列表</small>

                </h1>

            </div>

        </div>

        <?php if ($output['goodsList']) {

            foreach ($output['goodsList'] as $value) { ?>

                <div class="col-md-3 col-xs-6 shop_box">

                    <div class="thumbnail">

                        <img src="<?php echo $value['thumb'] ?>" alt="..." class="img-responsive"

                             alt="Responsive image">

                        <div class="caption">

                            <h3 class="shop_box_text"><?php echo $value['goods_name'] ?>

                                <small style="color:red"></small>

                            </h3>

                            <p>￥<?php echo $value['price'] ?></p>

                            <p><a href="index.php?act=goods&op=show&id=<?php echo $value['gid'] ?>"

                                  class="btn btn-primary"

                                  role="button">立即购买</a>
                                <a href="javascript:;"

                                   class="btn btn-primary add_cart"

                                   role="button" data-rel="<?php echo $value['gid'] ?>">加入购物车</a>
                            </p>

                        </div>

                    </div>

                </div>

            <?php }

        } ?>

        <div class="col-md-12">

            <div class="pagination"><?php echo $output['show_page']; ?></div>

        </div>

    </div>

    <div class="clearfix">

    </div>

    <!-- <div class="row ">



               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                   <div class="portlet box purple">

                       <div class="portlet-title">

                           <div class="caption">

                               <i class="fa fa-calendar"></i>投资重点说明

                           </div>

                           <div class="actions">

                               <a href="javascript:;" class="btn btn-sm yellow easy-pie-chart-reload">

                                   <i class="fa fa-repeat"></i>刷新

                               </a>

                           </div>

                       </div>

                       <div class="portlet-body">

                           <div class="row">

                               <div class="col-xs-4">

                                   <div class="easy-pie-chart">

                                       <div class="number transactions" data-percent="55">

                                           <span>+55

                                           </span>

                                           %

                                       </div>

                                       <a class="title" href="#">

                                           互联网金融领域<i class="m-icon-swapright"></i>

                                       </a>

                                   </div>

                               </div>

                               <div class="margin-bottom-10 visible-sm">

                               </div>

                               <div class="col-xs-4">

                                   <div class="easy-pie-chart">

                                       <div class="number visits" data-percent="85">

                                           <span>+85

                                           </span>

                                           %

                                       </div>

                                       <a class="title" href="#">

                                           电商领域 <i class="m-icon-swapright"></i>

                                       </a>

                                   </div>

                               </div>

                               <div class="margin-bottom-10 visible-sm">

                               </div>

                               <div class="col-xs-4">

                                   <div class="easy-pie-chart">

                                       <div class="number bounce" data-percent="46">

                                           <span>-46

                                           </span>

                                           %

                                       </div>

                                       <a class="title" href="#">

                                           新能源领域 <i class="m-icon-swapright"></i>

                                       </a>

                                   </div>

                               </div>

                           </div>

                       </div>

                   </div>

               </div>

           </div>  -->


    <script src="/Public/Home/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>

    <script src="/Public/Home/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>

    <script src="/Public/Home/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>

    <script src="/Public/Home/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js"

            type="text/javascript"></script>

    <script src="/Public/Home/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>

    <style>

        .page-content {

            padding-top: 5px !important;;

        }

        .info span {

            color: #CC6600;

        }

        .number {

            font-size: 25px !important;

        }

    </style>
    <script>

        var doing = false;

        $('.add_cart').click(function (event) {


            var num = 1;

            var gid = $(this).attr('data-rel');

            if (num == '') {

                sweetAlert("错误!", "请填写购买数量", "error");

                return false;

            }

            var mynum = /^\+?[1-9]\d*$/;


            if (!mynum.test(num)) {

                sweetAlert("错误!", "购买数量必须为正整数", "error");

                return false;

            }

            if (doing) {

                sweetAlert("已经提交，请耐心等待", "error");

                return false;

            }

            doing = true;

            $.ajax({

                type: "POST",

                dataType: "json",

                url: "index.php?act=goods&op=add_cart",

                data: {gid: gid},

                success: function (data) {

                    if (data.status) {

                        swal("成功!", "加入购物车成功！", "success");

                    } else {

                        swal("失败!", data.info, "error");

                    }

                    doing = false;

                }

            });

        });

    </script>
    
    
    <!--手机版商城图片高度-->
    <script>
    	$(".shop_box > .thumbnail > img").height($(".shop_box > .thumbnail > img").width());
    	$(window).resize(function () {
    		$(".shop_box > .thumbnail > img").height($(".shop_box > .thumbnail > img").width());
    	});
    </script>