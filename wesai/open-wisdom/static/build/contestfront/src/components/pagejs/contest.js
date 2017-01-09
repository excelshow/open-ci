
var api_js = require('../_api.js');
var publish_state = {
  '1': {
    name: '未开始'
  },
  '2': {
    name: '未开始'
  },
  '3': {
    name: '进行中'
  },
  '4': {
    name: '下架'
  }
};
function getQueryString() {
  var urls = window.location.search.substr(1).split('&');
  var i;
  var result = [];
  for (i = 0; i < urls.length; i++) {
    result[urls[i].split('=')[0]] = urls[i].split('=')[1];
  }
  return result;
}
// Contest Index
$(document).on("pageInit", "#contest-index", function(e, id, page) {
	var contestIndex = require('../handlebars/contest-index.handlebars');
	var loading = false; //状态
	var refreshStatus = false;
	var listType = 1;
	var params = {
		'page': 1, //默认页数
		'size': 5 //默认每页条数
	}

	function htmlList(result) {
		$('#contest-content').html(result);
	}

	function appendList(result) {
		$('#contest-content').append(result);
	}

	function dataVerification() {
		$('.infinite-scroll-preloader').hide();
		$.detachInfiniteScroll($('.infinite-scroll'));
		loading = true;
		return;
	}
	//进入页面加载列表
	function ajaxList(params, loadMore) {
		api_js.listContest(params).done(function(rs) {
			if (!rs.error) {
				rs['publish_state'] = publish_state;
				var pageHtml = contestIndex(rs);
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
				if(rs.total == 0){
					var pageHtml = '<div class="nodata">暂无数据</div>';
					htmlList(pageHtml);
					dataVerification();
					refreshStatus = false;
				}
				if (Math.ceil(rs.total / rs.size) <= rs.page) {
					dataVerification();
				}
			} else {
				$.toast(rs.info)
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
		ajaxList(params);
		setTimeout(function() {
			// 加载完毕需要重置
			$.pullToRefreshDone($content);
			$.attachInfiniteScroll($('.infinite-scroll'));
			$.refreshScroller();
			loading = false;
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

//详情页
$(document).on("pageInit", "#contest-detail", function(e, id, page) {
	$.showPreloader();
	$(document).on('click', '.team-registration-btn', function() {
		var item_id = $(this).attr('data-item_id');
		var cid = $(this).attr('data-cid');
		var params = {
			item_id: item_id,
			cid: cid,
			type: 1,
			page: 1,
			size: 1,
			state: 1
		}
		$.get("/user/getuserinfo_by_uid", function(res) {
			if (res.error == 0) {
				if (res.result.mobile) {
					api_js.listMyTeam(params).done(function(data) {
						if (data.error == 0) {
							if (data.data.length > 0 && data.data[0].state == 1) {
								window.location.href = "/team/detail?cid=" + cid + "&item_id=" + item_id + "";
							} else {
								window.location.href = "/team/create?cid=" + cid + "&item_id=" + item_id + "";
							}
						}
					});
				} else {
					$.popup('.verify_your_phone_number');
				}
			} else {
				$.toast(res.info);
				return false;
			}
		}, "json");
	});
	$(document).on('click', '.group-registration-btn', function() {
		var cid = $(this).attr('data-cid');
		var params = {
			cid: cid,
			type: 1,
			page: 1,
			size: 20,
			state: 1
		}
		$.get("/user/getuserinfo_by_uid", function(res) {
			if (res.error == 0) {
				if (res.result.mobile) {
					api_js.listMyGroup(params).done(function(data) {
						if (data.error == 0) {
							if (data.data.length > 0 && data.data[0].state == 1) {
								window.location.href = "/group/detail?cid=" + cid + "&group_id=" + data.data[0].pk_group + "";
							} else {
								window.location.href = "/group/create?cid=" + cid + "";
							}
						}
					});
				} else {
					$.popup('.verify_your_phone_number');
				}
			} else {
				$.toast(res.info);
				return false;
			}
		}, "json");
	});
	$(document).on('click', '.personal-registration-btn', function() {
		var cid = $(this).attr('data-cid');
		var item_id = $(this).attr('data-item_id');
		$.get("/user/getuserinfo_by_uid", function(res) {
			if (res.error == 0) {
				if (res.result.mobile) {
					window.location.href = "/form/fillForm?item_id=" + item_id + "&cid=" + cid + "";
				} else {
					$.popup('.verify_your_phone_number');
				}
			} else {
				$.toast(res.info);
				return false;
			}
		}, "json");

	});
	setTimeout(function() {
		$.hidePreloader();
	}, 500);
});
