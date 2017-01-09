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



//小组
$(document).on("pageInit", "#group-create", function(e, id, page) {
	$(document).on("click", "#group-create-submit", function() {
		var queryString = getQueryString();
		var cid = queryString.cid;
		var params = {
			cid: cid,
			type: 1,
			page: 1,
			size: 20,
			state: 1
		}
		api_js.listMyGroup(params).done(function(data) {
			if (data.error == 0) {
				if (data.data.length > 0 && data.data[0].state == 1) {
					$.toast("已创建小组，正在跳转...");
					setTimeout(function() {
						window.location.href = "/group/detail?cid=" + cid + "&group_id=" + data.data[0].pk_group + "";
					}, 500);
				} else {
					var groupParams = {
						'cid': cid
					};
					$('#group-create-info input').each(function() {
						groupParams[this.name] = this.value;
					});
					if (groupParams.name == '') {
						$.toast('小组名称不能为空');
						return false;
					}
					if (groupParams.leader_name == '') {
						$.toast('组长姓名不能为空');
						return false;
					}
					if (!iphoneReg.test(groupParams.leader_contact)) {
						$.toast('请输入有效的手机号码！');
						return false;
					};
					api_js.createGroup(groupParams).done(function(data) {
						if (data.error == 0) {
							window.location.href = '/group/detail?cid=' + cid + '&group_id=' + data.lastid;
						}
					});
				}
			}
		});
	});
});
$(document).on("pageInit", "#group-detail", function(e, id, page) {
	$.showPreloader();
	setTimeout(function() {
		$.hidePreloader();
	}, 500);
	$(document).on('click', '#group-detail-submit', function() {
		var cid = $(this).attr('data-cid');
		var group_id = $(this).attr('data-group_id');
		var params = {
			'group_id': group_id,
			'type': 2,
		};
		api_js.createOrder(params).done(function(order) {
			if (order.error == 0) {
				if (undefined != order.order_pay_url && order.order_pay_url != '') {
					window.location.href = order.order_pay_url;
				}
			} else {
				$.toast(order.info)
			}

		});
	})
});

$(document).on("pageInit", "#group-join", function(e, id, page) {
	var queryString = getQueryString();
	var params = {
		'group_id': queryString.group_id,
		'page': 1,
		'size': 10
	};
	api_js.listEnrolDataByGroup(params).done(function(data) {
		var groupState = $('#group_enroldata_list').attr('data-state');
		for(var i=0;i<data.data.length;i++){
			data.data[i]['groupState']=groupState;
		}
		var enroldataListTemplate = require('../handlebars/enroldata_list.handlebars');
		var pageHtml = enroldataListTemplate(data.data);
		$('#group_enroldata_list').html(pageHtml);
	})
	$(document).on('click', '.open-about', function() {
		$.popup('.popup-about-team');
	});

	$(document).on('click','.submit-order-btn',function(){
		var cid = $(this).attr('data-cid');
		var group_id = $(this).attr('data-group_id');
		var params = {
			'group_id': group_id,
			'type': 2,
		};
		api_js.createOrder(params).done(function(order) {
			if (order.error == 0) {
				if (undefined != order.order_pay_url && order.order_pay_url != '') {
					window.location.href = order.order_pay_url;
				}
			} else {
				$.toast(order.info)
			}

		});
	});
});