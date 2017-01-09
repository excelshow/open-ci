var api_js = require('../_api.js');



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


// 团队
$(document).on("pageInit", "#team-create", function(e, id, page) {
	$(document).on("click", "#team-create-submit", function() {
		var queryString = getQueryString();
		var cid = queryString.cid;
		var item_id = queryString.item_id;
		var params = {
			'cid': cid,
			'item_id': item_id,
			'type': 1,
			'page': 1,
			'size': 1,
			'state': 1,
		}
		api_js.listMyTeam(params).done(function(data) {
			if (data.error == 0) {
				if (data.data.length > 0 && data.data[0].state == 1) {
					$.toast("已创建团队，正在跳转...");

					setTimeout(function() {
						window.location.href = "/team/detail?cid=" + cid + "&item_id=" + item_id + "";
					}, 500);
				} else {
					var teamParams = {
						'cid': cid,
						'item_id': item_id,
					};
					$('#team-create-info input').each(function() {
						teamParams[this.name] = this.value;
					});
					if (teamParams.name == '') {
						$.toast('团队名称不能为空');
						return false;
					}
					if (teamParams.leader_name == '') {
						$.toast('团长姓名不能为空');
						return false;
					}

					if (!iphoneReg.test(teamParams.leader_contact)) {
						$.toast('请输入有效的手机号码！');
						return false;
					}
					api_js.createTeam(teamParams).done(function(data) {
						if (data.error == '0') {
							window.location.href = '/team/detail?cid=' + cid + '&item_id=' + item_id + '&team_id=' + data.lastid;
						}
					});
				}
			}
		});

	});
});

$(document).on("pageInit", "#team-detail", function(e, id, page) {
	$.showPreloader();
	setTimeout(function() {
		$.hidePreloader();
	}, 500);
	$(document).on('click', '#from-submit', function(e) {
		var item_id = $(this).attr('data-item_id') ? $(this).attr('data-item_id') : 0;
		var team_id = $(this).attr('data-team_id') ? $(this).attr('data-team_id') : 0;
		var params = {
			'item_id': item_id,
			'team_id': team_id,
			'type': 3
		};
		api_js.createOrder(params).done(function(order) {
			if (order.error == 0) {
				if (undefined != order.order_pay_url && order.order_pay_url != '') {
					window.location.href = order.order_pay_url;
				}
			} else {
				$.toast(order.info);
			}

		});
	});

});


$(document).on("pageInit", "#team-join", function(e, id, page) {
	var queryString = getQueryString();
	var params = {
		'team_id': queryString.team_id,
		'page': 1,
		'size': 10
	};
	api_js.listEnrolDataByTeam(params).done(function(data) {
		var groupState = $('#enroldata_list').attr('data-state');
		for(var i=0;i<data.data.length;i++){
			data.data[i]['groupState']=groupState;
		}
		var enroldataListTemplate = require('../handlebars/enroldata_list.handlebars');
		var pageHtml = enroldataListTemplate(data.data);
		$('#enroldata_list').html(pageHtml);
	})
	$(document).on('click', '.open-about', function() {
		$.popup('.popup-about-team');
	});
	$(document).on('click', '.submit-order-btn', function() {
		var item_id = $(this).attr('data-item_id') ? $(this).attr('data-item_id') : 0;
		var team_id = $(this).attr('data-team_id') ? $(this).attr('data-team_id') : 0;
		var params = {
			'item_id': item_id,
			'team_id': team_id,
			'type': 3
		};
		api_js.createOrder(params).done(function(order) {
			if (order.error == 0) {
				if (undefined != order.order_pay_url && order.order_pay_url != '') {
					window.location.href = order.order_pay_url;
				}
			} else {
				$.toast(order.info);
			}
		});
	});
});