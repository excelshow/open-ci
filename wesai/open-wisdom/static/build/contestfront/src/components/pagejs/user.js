var api_js = require('../_api.js');
var team_group_state = {
	'1': {
		name: '开始报名',
		className: 'red-color'
	},
	'2': {
		name: '支付中',
		className: 'red-color'
	},
	'3': {
		name: '支付失败',
		className: 'gray-color'

	},
	'4': {
		name: '支付成功',
		className: 'green-color'
	},
	'5': {
		name: '取消',
		className: 'bg-white'

	}
};

var order_state = {
	'1': {
		name: '创建',
		className: 'gray-color'
	},
	'2': {
		name: '支付中',
		className: 'gray-color'
	},
	'3': {
		name: '支付失败',
		className: 'gray-color'

	},
	'4': {
		name: '支付成功',
		className: 'green-color'
	},
	'5': {
		name: '支付成功',
		className: 'green-color'

	}
};


var iphoneReg = /^1\d{10}$/;


function getQueryString() {
	var urls = window.location.search.substr(1).split('&');
	var i;
	var result = [];
	for (i = 0; i < urls.length; i++) {
		result[urls[i].split('=')[0]] = urls[i].split('=')[1];
	}
	return result;
}



$(document).on('pageInit', '#teamList-page', function(e, id, page) {
	var teamList = require('../handlebars/groupList.handlebars');
	var loading = false; //状态
	var refreshStatus = false;
	var listType = 1;
	var params = {
		'page': 1, //默认页数
		'size': 10, //默认每页条数
		'type': 1,
		'contest_required': 1
	}
	$(document).on('click', ".user-orders-tab .user-orders-item", function() {
		var type = $(this).attr('data-type');
		$(this).addClass("activity").siblings().removeClass("activity");
		params['type'] = type;
		params['page'] = 1;
		// 加载完毕需要重置
		loading = false; //状态
		$.pullToRefreshDone($content);
		$.attachInfiniteScroll($('.infinite-scroll'));
		$.refreshScroller();
		refreshStatus = false;
		ajaxList(params);
	});

	function htmlList(result) {
		$('#teamList').html(result);
	}

	function appendList(result) {
		$('#teamList').append(result);
	}

	function dataVerification() {
		$('.infinite-scroll-preloader').hide();
		$.detachInfiniteScroll($('.infinite-scroll'));
		loading = true;
		return;
	}
	//进入页面加载列表
	function ajaxList(params, loadMore) {
		api_js.listMyTeam(params).done(function(rs) {
			console.log(rs);
			if (rs.error == 0) {
				for (var i = 0; i < rs.data.length; i++) {
					rs.data[i].state = team_group_state[rs.data[i].state];
				}
				var pageHtml = teamList(rs);
				params = {
					'page': rs.page,
					'size': rs.size
				}
				if (loadMore) {
					appendList(pageHtml);
				} else {
					htmlList(pageHtml);
				}
				$('.infinite-scroll-preloader').show();
				if (rs.total == 0) {
					var pageHtml = '<div class="nodata">暂无数据</div>';
					htmlList(pageHtml);
					dataVerification();
				}
				if (Math.ceil(rs.total / rs.size) <= rs.page) {
					dataVerification();
				}
			} else {
				var pageHtml = '<div class="nodata">暂无数据</div>';
				htmlList(pageHtml);
				dataVerification();
			}

		})
	}
	ajaxList(params);
	//进入页面加载列表
	//下拉刷新页面
	var $content = $(page).find(".content").on('refresh', function(e) {
		if (refreshStatus) {
			return;
		}
		refreshStatus = true;
		params['page'] = 1;
		loading = false; //状态
		ajaxList(params);
		setTimeout(function() {
			// 加载完毕需要重置
			$.pullToRefreshDone($content);
			$.attachInfiniteScroll($('.infinite-scroll'));
			$.refreshScroller();
			refreshStatus = false;
		}, 500);

	});
	//上拉加载更多
	$(page).on('infinite', function() {
		// 如果正在加载，则退出   
		if (loading) {
			return;
		}
		loading = true;
		params['page'] = params.page + 1
		setTimeout(function() {
			var loadMore = true;
			ajaxList(params, loadMore);
			$.refreshScroller();
			loading = false;
		}, 500);
	});

})


$(document).on('pageInit', '#groupList-page', function(e, id, page) {
	var groupList = require('../handlebars/groupList.handlebars');
	var loading = false; //状态
	var refreshStatus = false;
	var listType = 1;
	var params = {
		'page': 1, //默认页数
		'size': 10, //默认每页条数
		'type': 1,
		'contest_required': 1

	}
	$(document).on('click', ".user-orders-tab .user-orders-item", function() {
		var type = $(this).attr('data-type');
		$(this).addClass("activity").siblings().removeClass("activity");
		params['type'] = type;
		params['page'] = 1;
		// 加载完毕需要重置
		loading = false; //状态
		$.pullToRefreshDone($content);
		$.attachInfiniteScroll($('.infinite-scroll'));
		$.refreshScroller();
		refreshStatus = false;
		ajaxList(params);
	});

	function htmlList(result) {
		$('#groupList').html(result);
	}

	function appendList(result) {
		$('#groupList').append(result);
	}

	function dataVerification() {
		$('.infinite-scroll-preloader').hide();
		$.detachInfiniteScroll($('.infinite-scroll'));
		loading = true;
		return;
	}

	//进入页面加载列表
	function ajaxList(params, loadMore) {
		api_js.listMyGroup(params).done(function(rs) {
			if (rs.error == 0) {
				console.log(rs);
				for (var i = 0; i < rs.data.length; i++) {
					rs.data[i].state = team_group_state[rs.data[i].state];
				}
				var pageHtml = groupList(rs);
				params = {
					'page': rs.page,
					'size': rs.size
				}
				if (loadMore) {
					appendList(pageHtml);
				} else {
					htmlList(pageHtml);
				}
				$('.infinite-scroll-preloader').show();
				if (rs.total == 0) {
					var pageHtml = '<div class="nodata">暂无数据</div>';
					htmlList(pageHtml);
					dataVerification();
				}
				if (Math.ceil(rs.total / rs.size) <= rs.page) {
					dataVerification();
				}
			} else {
				var pageHtml = '<div class="nodata">暂无数据</div>';
				htmlList(pageHtml);
				dataVerification();
			}

		})
	}
	ajaxList(params);
	//进入页面加载列表
	//下拉刷新页面
	var $content = $(page).find(".content").on('refresh', function(e) {
		if (refreshStatus) {
			return;
		}
		refreshStatus = true;
		params['page'] = 1;
		loading = false; //状态
		ajaxList(params);
		setTimeout(function() {
			// 加载完毕需要重置
			$.pullToRefreshDone($content);
			$.attachInfiniteScroll($('.infinite-scroll'));
			$.refreshScroller();
			refreshStatus = false;
		}, 500);

	});
	//上拉加载更多
	$(page).on('infinite', function() {
		// 如果正在加载，则退出   
		if (loading) {
			return;
		}
		loading = true;
		params['page'] = params.page + 1
		setTimeout(function() {
			var loadMore = true;
			ajaxList(params, loadMore);
			$.refreshScroller();
			loading = false;
		}, 500);
	});
});



$(document).on('pageInit', '#ordersList-page', function(e, id, page) {
	var ordersList = require('../handlebars/orders-list.handlebars');
	var loading = false; //状态
	var refreshStatus = false;
	var listType = 1;
	var params = {
		'page': 1, //默认页数
		'size': 5, //默认每页条数
		'contest_info': 1,
	}
	function htmlList(result) {
		$('#ordersList-tem').html(result);
	}

	function appendList(result) {
		$('#ordersList-tem').append(result);
	}

	function dataVerification() {
		$('.infinite-scroll-preloader').hide();
		$.detachInfiniteScroll($('.infinite-scroll'));
		loading = true;
		return;
	}
	//进入页面加载列表
	function ajaxList(params, loadMore) {
		api_js.getOrderList(params).done(function(rs) {
			if (rs.error == 0) {
				rs['CDN_URL'] = rs.page_vars.CDN_URL;
				for (var i = 0; i < rs.data.length; i++) {
					if (rs.data[i].state == 2) {
						rs.data[i]['payAgain'] = true;
					};
					if(rs.data[i].state == 4 || rs.data[i].state == 5){
						rs.data[i]['pay_seessen'] = true;
					};
					rs.data[i].state = order_state[rs.data[i].state];
				}
				var pageHtml = ordersList(rs);
				params = {
					'page': rs.page,
					'size': rs.size
				}
				if (loadMore) {
					appendList(pageHtml);
				} else {
					htmlList(pageHtml);
				}
				$('.infinite-scroll-preloader').show();
				if (rs.total == 0) {
					var pageHtml = '<div class="nodata">暂无数据</div>';
					htmlList(pageHtml);
					dataVerification();
				}
				if (Math.ceil(rs.total / rs.size) <= rs.page) {
					dataVerification();
				}
			} else {
				var pageHtml = '<div class="nodata">暂无数据</div>';
				htmlList(pageHtml);
				dataVerification();

			}

		})
	}
	ajaxList(params);
	//进入页面加载列表
	//下拉刷新页面
	var $content = $(page).find(".content").on('refresh', function(e) {
		if (refreshStatus) {
			return;
		}
		refreshStatus = true;
		params['page'] = 1;
		console.log(params);
		ajaxList(params);
		setTimeout(function() {
			// 加载完毕需要重置
			$.pullToRefreshDone($content);
			$.attachInfiniteScroll($('.infinite-scroll'));
			$.refreshScroller();
			refreshStatus = false;
		}, 500);

	});
	//上拉加载更多
	$(page).on('infinite', function() {
		// 如果正在加载，则退出   
		if (loading) {
			return;
		}
		loading = true;
		params['page'] = params.page + 1
		setTimeout(function() {
			var loadMore = true;
			ajaxList(params, loadMore);
			$.refreshScroller();
			loading = false;
		}, 500);
	});


});


$(document).on('pageInit', '#ordersDetails-page', function(e, id, page) {
	$.showPreloader();
	$('.qr-images .text-center').each(function() {
		var verify_code = $(this).text();
		var verify_code = verify_code.replace(/(\d{4})/g, '$1 ').replace(/\s*$/, '');
		$(this).text(verify_code);
	})
	setTimeout(function() {
		$.hidePreloader();
	}, 500);


	//点击时打开图片浏览器
	$(document).on('click', '.pb-standalone', function() {
		var imgArr=[];
		var imgUrl=$(this).attr('data-src');
		imgArr.push(imgUrl);
		var myPhotoBrowserStandalone = $.photoBrowser({

			photos: imgArr
		});
		console.log('aa')
		myPhotoBrowserStandalone.open();
	});
});
$(document).on('pageInit', '#payResult-page', function(e, id, page) {
	var oid = $('#payResult-page').attr('data-oid');
	var ORDER_STATE_INIT = $('#payResult-page').attr('data-ORDER_STATE_INIT');
	var ORDER_STATE_PAYING = $('#payResult-page').attr('data-ORDER_STATE_PAYING');
	var params = {
		oid: oid
	}
	$.showPreloader('处理中请稍后...');
	var t1 = setInterval(
		api_js.ajax_get_order_by_id(params).done(function(msg) {
			if (msg.error == 0) {
				if (msg.error != 0) {
					alert('error');
					return;
				}
				if (msg.orderState != ORDER_STATE_INIT && msg.orderState != ORDER_STATE_PAYING) {
					$.hidePreloader();
					window.clearInterval(t1);
					location.href = '/order/detail?oid=' + oid + '';
				}
			} else {
				return false;
			}
		}), 1000);
});


$(document).on('click', '.pay-again', function() {
	var oid = $(this).attr('data-oid');
	var paid_time = $(this).attr('data-paid_time');
	var channel_id = $(this).attr('data-channel_id');
	var transaction_id = $(this).attr('data-transaction_id');
	var params = {
		'oid': oid,
		'paid_time': paid_time,
		'channel_id': channel_id,
		'transaction_id': transaction_id
	};

	api_js.ajax_get_order_pay_url(params).done(function(order) {
		if(order.error == 0){
			if (undefined != order.order_pay_url && order.order_pay_url != '') {
				console.log(order.order_pay_url)
				window.location.href = order.order_pay_url;
			}
		}else{
			if(order.info){
				$.toast(order.info)
			}
			$.toast("系统繁忙")
		}
		
	});
	return false;
});