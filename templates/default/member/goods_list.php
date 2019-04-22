<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <small>商品分类</small>
            </h1>
        </div>
    </div>
    <div class="col-md-12">
        <a href="index.php?act=goods&op=shop"
           class="btn btn-info new_style_a1">全部</a>
        <?php if ($output['classInfo']) {
            foreach ($output['classInfo'] as $value) { ?>
                <a href="index.php?act=goods&op=shop&typeid=<?php echo $value['id']; ?>"
                   class="btn btn-info new_style_a1"><?php echo $value['typename']; ?></a>
            <?php }
        } ?>

    </div>
</div>
<div class="row ">
    <div class="col-md-12">
        <div class="page-header">
            <h1>
                <small>商品列表(
                    <?php if ($_GET['typeid']) { ?>
                        <?php echo $output['curClassName'] ?>
                    <?php } else { ?>
                        全部
                    <?php } ?>
                    )
                </small>
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

                        <?php if($value['typeid'] == 6){ ?> 
                            <span style="text-decoration:line-through">￥<?php echo $value['price'] ?></span>
                            <span>￥ 0.00</span>
                        <?php }else{ ?>
                            <p>￥<?php echo $value['price'] ?></p>
                        <?php  }  ?>
                        
                        <p>

                        <?php if($value['typeid'] == 6){ ?> 
                            <a href="index.php?act=goods&op=show&id=<?php echo $value['gid'] ?>"
                               class="btn btn-primary"
                               role="button">立即领取</a>
                        <?php }else{ ?>
                            <a href="index.php?act=goods&op=show&id=<?php echo $value['gid'] ?>"
                               class="btn btn-primary"
                               role="button">立即购买</a>
                            <a href="javascript:;"
                               class="btn btn-primary add_cart"
                               role="button" data-rel="<?php echo $value['gid'] ?>">加入购物车</a>
                        <?php  }  ?>
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