(function(b) {
	b.fn.fullScreen = function(a) {
		a = b.extend({
			time: 5E3,
			css: "full-screen-slides-pagination"
		}, a);
		return this.each(function() {
			function e() {
				var a = f + 1;
				0 == d && (a == g && (a = 0), m.find("li").eq(a).trigger("click"));
				setTimeout(e, l)
			}
			var c = b(this),
				g = c.find("li").size(),
				f = 0,
				d = 0,
				l = a.time;
			c.find("li:gt(0)").hide();
			for (var h = '<ul class="' + a.css + '">', k = 0; k < g; k++) h += '<li><a href="javascript:void(0)">' + (k + 1) + "</a></li>";
			c.after(h + "</ul>");
			var m = c.next();
			m.find("li").first().addClass("current");
			m.find("li").click(function() {
				var a = b(this).index();
				b(this).addClass("current").siblings("li").removeClass("current");
				c.find("li").eq(a).css("z-index", "800").show();
				c.find("li").eq(f).css("z-index", "900").fadeOut(400, function() {
					c.find("li").eq(a).fadeIn(500)
				});
				f = a
			}).mouseenter(function() {
				d = 1
			}).mouseleave(function() {
				d = 0
			});
			setTimeout(e, l)
		})
	};
	b.fn.jfocus = function(a) {
		a = b.extend({
			time: 5E3
		}, a);
		return this.each(function() {
			function e(a) {
				var b = -a * g;
				c.find("ul").stop(!0, !1).animate({
					left: b
				}, 300);
				c.find(".pagination span").stop(!0, !1).animate({
					opacity: "0.4"
				}, 300).eq(a).stop(!0, !1).animate({
					opacity: "1"
				}, 300)
			}
			for (var c = b(this), g = c.width(), f = c.find("ul li").length, d = 0, l, h = "<div class='pagination'>", k = 0; k < f; k++) h += "<span></span>";
			c.append(h + "</div><div class='arrow pre'></div><div class='arrow next'></div>");
			c.find(".pagination span").css("opacity", .4).mouseenter(function() {
				d = c.find(".pagination span").index(this);
				e(d)
			}).eq(0).trigger("mouseenter");
			c.find(".arrow").css("opacity", 0).hover(function() {
				b(this).stop(!0, !1).animate({
					opacity: "0.5"
				}, 300)
			}, function() {
				b(this).stop(!0, !1).animate({
					opacity: "0"
				}, 300)
			});
			c.find(".pre").click(function() {
				--d; - 1 == d && (d = f - 1);
				e(d)
			});
			c.find(".next").click(function() {
				d += 1;
				d == f && (d = 0);
				e(d)
			});
			c.find("ul").css("width", g * f);
			c.hover(function() {
				clearInterval(l)
			}, function() {
				l = setInterval(function() {
					e(d);
					d++;
					d == f && (d = 0)
				}, a.time)
			}).trigger("mouseleave")
		})
	};
	b.fn.jfade = function(a) {
		a = b.extend({
			start_opacity: "1",
			high_opacity: "1",
			low_opacity: ".1",
			timing: "500"
		}, a);
		a.element = b(this);
		b(a.element).css("opacity", a.start_opacity);
		b(a.element).hover(function() {
			b(this).stop().animate({
				opacity: a.high_opacity
			}, a.timing);
			b(this).siblings().stop().animate({
				opacity: a.low_opacity
			}, a.timing)
		}, function() {
			b(this).stop().animate({
				opacity: a.start_opacity
			}, a.timing);
			b(this).siblings().stop().animate({
				opacity: a.start_opacity
			}, a.timing)
		});
		return this
	}
})(jQuery);

function takeCount() {
	setTimeout("takeCount()", 1E3);
	$(".time-remain").each(function() {
		var b = $(this),
			a = b.attr("count_down");
		if (0 < a) {
			var a = parseInt(a) - 1,
				e = Math.floor(a / 86400),
				c = Math.floor(a / 3600) % 24,
				g = Math.floor(a / 60) % 60,
				f = Math.floor(a / 1) % 60;
			0 > e && (e = 0);
			0 > c && (c = 0);
			0 > g && (g = 0);
			0 > f && (f = 0);
			b.find("[time_id='d']").html(e);
			b.find("[time_id='h']").html(c);
			b.find("[time_id='m']").html(g);
			b.find("[time_id='s']").html(f);
			b.attr("count_down", a)
		}
	})
}

function update_screen_focus() {
	var b = "";
	$(".full-screen-slides li[ap_id]").each(function() {
		var a = $(this).attr("ap_id");
		b += "&ap_ids[]=" + a
	});
	$(".jfocus-trigeminy a[ap_id]").each(function() {
		var a = $(this).attr("ap_id");
		b += "&ap_ids[]=" + a
	});
	"" != b && $.ajax({
		type: "GET",

		url: SHOP_SITE_URL + "/index.php?act=adv&op=get_adv_list" + b,
		dataType: "jsonp",
		async: !0,
		success: function(a) {
			$(".full-screen-slides li[ap_id]").each(function() {
				var b = $(this),
					c = b.attr("ap_id"),
					g = b.attr("color");
				"undefined" !== typeof a[c] && (c = a[c], b.css("background", g + " url(" + c.adv_img + ") no-repeat center top"), b.find("a").attr("title", c.adv_title), b.find("a").attr("href", c.adv_url))
			});
			$(".jfocus-trigeminy a[ap_id]").each(function() {
				var b = $(this),
					c = b.attr("ap_id");
				"undefined" !== typeof a[c] && (c = a[c], b.attr("title", c.adv_title), b.attr("href", c.adv_url), b.find("img").attr("alt", c.adv_title), b.find("img").attr("src", c.adv_img))
			})
		}
	})
}
$(function() {
	setTimeout("takeCount()", 1E3);
	$(".tabs-nav > li > h3").bind("mouseover", function(a) {
		if (a.target == this) {
			a = $(this).parent().parent().children("li");
			var b = $(this).parent().parent().parent().children(".tabs-panel"),
				c = $.inArray(this, $(this).parent().parent().find("h3"));
			b.eq(c)[0] && (a.removeClass("tabs-selected").eq(c).addClass("tabs-selected"), b.addClass("tabs-hide").eq(c).removeClass("tabs-hide"))
		}
	});
	$(".jfocus-trigeminy > ul > li > a").jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "200"
	});
	$(".fade-img > a").jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
	$(".middle-goods-list > ul > li").jfade({
		start_opacity: "0.9",
		high_opacity: "1",
		low_opacity: ".25",
		timing: "500"
	});
	$(".recommend-brand > ul > li").jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
	$(".full-screen-slides").fullScreen();
	$(".jfocus-trigeminy").jfocus();
	$(".right-side-focus").jfocus();
	$(".groupbuy").jfocus({
		time: 8E3
	});
	$("#saleDiscount").jfocus({
		time: 8E3
	});
	var getyxl = jQuery('#picLBxxl li').eq(0).width();
	(function($) {
		var arartta = window['arartta'] = function(o) {
				return new das(o);
			}
		das = function(o) {
			this.obj = $('#' + o.obj);
			this.bnt = $('#' + o.bnt);
			this.showLi = this.obj.find('li');
			this.current = 0;
			this.myTimersc = '';
			this.init()
		}
		das.prototype = {
			chgPic: function(n) {
				var _this = this;
				for (var i = 0, l = _this.showLi.length; i < l; i++) {
					_this.showLi.eq(i).find(".pic").find('img').eq(n).attr('src', _this.showLi.eq(i).find(".pic").find('img').eq(n).attr('nsrc'));

					$('#picLBxxl dl:not(:animated)').animate({
						left: -(n * getyxl) + "px"
					}, {
						easing: "easeInOutExpo"
					}, 1500, function() {});
				}
			},
			rotate: function() {
				var _this = this;
				clearInterval(_this.myTimersc);
				_this.bnt.children().css({
					'-webkit-transform': 'rotate(0deg)',
					'-moz-transform': 'rotate(0deg)'
				});
				var tt = 0;
				var getBnts = _this.bnt.children();
				_this.myTimersc = setInterval(function() {
					tt += 10;
					if (tt >= 180) {
						clearInterval(_this.myTimersc);
					}
					rotateElement(getBnts, tt);
				}, 25)
			},
			init: function() {
				var _this = this;
				this.bnt.bind("click", function() {
					_this.current++;
					if (_this.current > 4) {
						_this.current = 0;
					}
					_this.chgPic(_this.current);
					_this.rotate();

				})
				this.bnt.mouseenter(function() {
					_this.rotate();
				});

			}
		}
	})(jQuery)

	arartta({
		bnt: 'xxlChg',
		obj: 'picLBxxl'
	});
	
	$("a[href='']").removeAttr("target").attr("href", "javascript:void(0)");
	var b = [];
	window.onscroll = function() {
		800 < $(document).scrollTop() ? $("#nav_box").fadeIn("slow") : $("#nav_box").fadeOut("slow");
		$(".home-standard-layout").each(function(a) {
			var e = $(this);
			e.index = a;
			$(document).scrollTop() + $(window).height() / 2 > e.offset().top && b.push(a)
		});
		b.length && ($("#nav_box li").eq(b[b.length - 1]).addClass("hover").siblings().removeClass("hover"), b = [])
	};
	$("#nav_box li").each(function(a) {
		$(this).click(function() {
			$("html,body").animate({
				scrollTop: $(".home-standard-layout").eq(a).offset().top - 20 + "px"
			}, 500)
		}).mouseover(function() {
			$(this).hasClass("hover") || $(this).css()
		}).mouseout(function() {
			$(this).hasClass("hover") || $(this).css()
		})
	});
	window.onload = window.onresize = function() {
		1300 > $(window).width() || 800 > $(document).scrollTop() ? $("#nav_box").fadeOut("slow") : $("#nav_box").fadeIn("slow")
	}
});