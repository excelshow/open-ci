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
