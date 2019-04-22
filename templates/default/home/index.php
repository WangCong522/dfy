<?php defined('InShopBN') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<style type="text/css">
.category { display: block !important;}
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic'];?>
  <div class="right-sidebar">
    <div class="policy">
      <ul>
        <li class="b1">正品保障</li>
        <li class="b2">同城实体</li>
        <li class="b3">闪电发货</li>
      </ul>
    </div>
    <?php if(!empty($output['group_list']) && is_array($output['group_list'])) { ?>
    <div class="groupbuy">
      <div class="title"><i>抢</i>近期抢购</div>
      <ul>
        <?php foreach($output['group_list'] as $val) { ?>
        <li>
          <dl style=" background-image:url(<?php echo gthumb($val['groupbuy_image1'], 'small');?>)">
            <dt><?php echo $val['groupbuy_name']; ?></dt>
            <dd class="price"><span class="groupbuy-price"><?php echo ncPriceFormatForList($val['groupbuy_price']); ?></span><span class="buy-button"><a href="<?php echo urlShop('show_groupbuy','groupbuy_detail',array('group_id'=> $val['groupbuy_id']));?>">立即抢</a></span></dd>
            <dd class="time"><span class="sell">已售<em><?php echo $val['buy_quantity'];?></em></span> <span class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP; ?>"> <em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </span></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <div class="proclamation">
      <ul class="tabs-nav">
        <li class="tabs-selected">
          <h3>招商入驻</h3>
        </li>
        <li>
          <h3><?php echo $output['show_article']['notice']['ac_name'];?></h3>
        </li>
      </ul>
      <div class="tabs-panel"> <a href="<?php echo urlShop('show_joinin', 'index');?>" title="申请商家入驻；已提交申请，可查看当前审核状态。" class="store-join-btn" target="_blank">&nbsp;</a> <a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" class="store-join-help"><i class="icon-cog"></i>登录商家管理中心</a> </div>
      <div class="tabs-panel tabs-hide">
        <ul class="mall-news">
          <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><i></i><a target="_blank" href="<?php echo empty($val['article_url']) ? urlShop('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?> </a>
            <time>(<?php echo date('Y-m-d',$val['article_time']);?>)</time>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--HomeFocusLayout End-->

<div class="home-sale-layout wrapper">
  <div class="left-layout"> <?php echo $output['web_html']['index_sale'];?> </div>
  <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
  <div class="right-sidebar">
    <div class="title">
      <h3><?php echo $lang['nc_xianshi'];?></h3>
    </div>
    <div id="saleDiscount" class="sale-discount">
      <ul>
        <?php foreach($output['xianshi_item'] as $val) { ?>
        <li>
          <dl>
            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
            <dd class="goods-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>"></a></dd>
            <dd class="goods-price"><?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span class="original"><?php echo ncPriceFormatForList($val['goods_price']);?></span></dd>
            <dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>
            <dd class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP;?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </dd>
            <dd class="goods-buy-btn"></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>

<!--StandardLayout Begin-->
<?php echo $output['web_html']['index'];?>
<!--StandardLayout End-->
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/lunbo.js" charset="utf-8"></script>
<div class="wrapper">
  <div class="mt10"><?php echo loadadv(9,'html');?></div>
</div>

<div id="leftSide">
	<div class="leftSideBox">
		<ul>
			<li class="title">快速导航</li>
			<?php
				$floor = 0;
				foreach($output['web_html']['floor'] as $key=>$val){
					$floor ++;
			?>
			<li id="fl_li_c_<?php echo $floor;?>"><a class="floor1" href="javascript:xmove(<?php echo $floor;?>)"><?php echo $val['name'];?></a></li>
			<?php
				}
			?>
			<li class="bottom" id="l_to_top"><a href="javascript:;">返回顶部</a></li>
		</ul>
	</div>	
</div>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script type="text/javascript">
$.fn.xbido_move = function(settings) {//首页切换
		var defaults = {
			time: 5000,
			xn:"xbn"
		};
		var settings = $.extend(defaults, settings);
		return this.each(function(){
			var $this = $(this);
			var sWidth = $this.width();
			var len = $this.find("ul li").length;
			var index = 0;
			var picTimer;
			var btn = "<div class='pagination'>";
			for (var i = 0; i < len; i++) {
				btn += "<span></span>";
			}
			btn += "</div><div class='arrow pre'></div><div class='arrow next'></div>";
			
			var obj = $this.parent().find(".subxtab ul li");
			obj.click(function(){
			    index = $(this).index();
				var ls = $(this).position().left;
				var o_slider = $(this).parent().parent().find(".slider"); 
				o_slider.stop().animate({"left": ls},200);
				showPics(index);
				$(this).siblings().animate({
					color:"#000000"
				}, 200);
				$(this).animate({
					color:"#FFFFFF"
				}, 200);				
			});
			
			obj.mouseover(function(){
			    $(this).click();
			});
			
			$this.append(btn);
			$this.find(".pagination span").css("opacity", 0.4).mouseenter(function() {
				index = $this.find(".pagination span").index(this);
				if(index >= len) index-=len;
				if(index < 0) index = 0;
				showPics(index);
			}).eq(0).trigger("mouseenter");
			$this.find(".arrow").css("opacity", 0.0).hover(function() {
				$(this).stop(true, false).animate({
					"opacity": "0.5"
				},
				300);
			},
			function() {
				$(this).stop(true, false).animate({
					"opacity": "0"
				},
				300);
			});
			$this.find(".pre").click(function() {
				index -= 1;
				if (index == -1) {
					index = len - 1;
				}
				obj[index].click();
				showPics(index);
			});
			$this.find(".next").click(function() {
				index += 1;
				if (index == len) {
					index = 0;
				}
				obj[index].click();
				showPics(index);
			});
			$this.find("ul").css("width", sWidth * (len));
			$this.hover(function() {
				clearInterval(picTimer);
			},
			function() {
				//*
				picTimer = setInterval(function() {
					showPics(index);
					index++;
					if (index >= len) {
						index = 0;
					}
				},
				settings.time);
			}).trigger("mouseleave");
			//*///
			function showPics(index) {
				var nowLeft = -index * sWidth;
				$this.find("ul").stop(true, false).animate({
					"left": nowLeft
				},
				300);
				$this.find(".pagination span").stop(true, false).animate({
					"opacity": "0.4"
				},
				300).eq(index).stop(true, false).animate({
					"opacity": "1"
				},
				300);
			}
		});
	}

$(function(){
	$("#floor_11").xbido_move({time:100000,xn:"subxtab"});
});	

function xmove(id){
    if(id ==0) ppx = 0;
	else ppx = $('#floor_' + id).offset().top - 70;
	$("html, body").animate({ scrollTop: ppx }, 500);
}
backTop('l_to_top');

var floor_height = $('#floor_2').offset().top - $('#floor_1').offset().top;

$(function(){
	$(window).scroll(function(){
          height = $(window).scrollTop();
		  
		  for(i=1;i<=<?php echo $floor;?>;i++){
		      if(height >=  $('#floor_' + i).offset().top - 70 && height <=  $('#floor_' + i).offset().top - 70 + floor_height){
			      $('#fl_li_c_' + i).addClass('hover').siblings().removeClass('hover');
			  }
		  }
		  
		    if(height <  $('#floor_1').offset().top - 70 || height >  $('#floor_<?php echo $floor?>').offset().top - 70 + floor_height){
			    $('#leftSide ul li').removeClass('hover');
			}
	});
});


</script>	