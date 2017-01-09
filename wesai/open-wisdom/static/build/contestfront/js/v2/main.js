/*commonjs*/
window.alert = function(msg) {
	$(".alert-box").remove();
	var alerthtml = '';
	alerthtml += '<div class="alert-box" role="alert">';
	alerthtml += '<div class="tip-title">' + msg + '</div>';
	alerthtml += '</div>';
	$("body").append(alerthtml);
	setTimeout(function() {
		$(".alert-box").fadeOut();
	}, "2000")
}
$(function() {
		setTimeout(function() {
			$(".loading-toast").remove();
		}, 500)
		jQuery(".contest-list").on("click", 'li', function(e) {
			var linkUrl = jQuery(this).attr("dataurl");
			location.href = linkUrl;
			return false;
		})
	})
	//nav
$(function() {
		// var _referRegx = /^http(s)?\:\/\/((\w|\-|\.)+)\.wesai.com/;
		// var _refer = document.referrer && document.referrer.match( _referRegx )[0];
		// var hastop = $("div").hasClass("sharetop-nav");
		// if(!_refer && hastop)
		// {
		//   $(".sharetop-nav").show();
		//   $(".nav-stretch").hide();
		// }else{
		//   $(".sharetop-nav").hide();
		//   $(".nav-stretch").show();
		// }
		$body = $("body,html");
		$body.on('click', '.ico-more', function(e) {
			e.preventDefault();
			var $nav = $(e.currentTarget).closest('.nav-topnav');
			$nav.toggleClass('current');
			return false;
		});


		$body.on('click', '.nav-stretch h4 a', function(e) {
			e.preventDefault();
			var $nav = $('.nav-stretch');
			$nav.toggleClass('current');
			return false;
		});

		$body.on('click', function(e) {
			var $this = $(e.target);
			var $nav = $('.nav-stretch');

			if (!$this.parents('.nav-stretch').length) {
				$nav.removeClass('current');
			}
		});
	})
	//hideweixinshare
var WxShareMenu = {
	HideWxMenu: function() {
		wx.ready(function() {
			wx.hideMenuItems({
				menuList: ['menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:share:qq', 'menuItem:share:weiboApp', 'menuItem:share:QZone']
			});
		})
	},
	ShowWxMenu: function() {
		wx.ready(function() {
			wx.showMenuItems({
				menuList: ['menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:share:qq', 'menuItem:share:weiboApp', 'menuItem:share:QZone']
			});
		})
	},
	init: function() {
		if (typeof(shareData) == 'undefined') {
			WxShareMenu.HideWxMenu();
		} else {
			WxShareMenu.ShowWxMenu();
		}
	}
}
WxShareMenu.init();