require('../lib/js/callbacks.js');
require('../lib/js/deferred.js');

function getDataCreator(url) {
	return function(params) {
		params = params || {};
		var def = $.Deferred();
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'json',
			data: params,
			success: function(rs) {
				def.resolve(rs);
			}
		});
		return def;
	};
}

function postDataCreator(url) {
	return function(params) {
		params = params || {};
		var def = $.Deferred();
		$.ajax({
			url: url,
			type: 'post',
			dataType: 'json',
			data: params,
			success: function(rs) {
				def.resolve(rs);
			}
		});
		return def;
	};
}

exports.listContest = getDataCreator('/contest/ajax_list');
exports.getContest = getDataCreator('/contest/ajax_get');

exports.listContestItem = getDataCreator('/contestitem/ajax_list');
exports.getContestItem = getDataCreator('/contestitem/ajax_get');

exports.createGroup = postDataCreator('/group/ajax_create');
exports.getGroup = getDataCreator('/group/ajax_get');
exports.listMyGroup = getDataCreator('/group/ajax_list_my');

exports.createTeam = postDataCreator('/team/ajax_create');
// exports.getTeam           = getDataCreator('/team/ajax_get');
exports.listMyTeam = getDataCreator('/team/ajax_list_my');

exports.listEnrolDataByGroup = getDataCreator('/enroldata/ajax_list_by_group');
exports.listEnrolDataByTeam = getDataCreator('/enroldata/ajax_list_by_team');
exports.getEnrolData = getDataCreator('/enroldata/ajax_get');

exports.getOrderList = getDataCreator('/order/list_by_uid');

// exports.getOrderDetails = getDataCreator('/order/get');

exports.createOrder = postDataCreator('/order/ajax_create');

exports.getFormByItemId = getDataCreator('/form/getByItemId');
exports.listFormItems = getDataCreator('/form/listFormItems');

exports.postEnroldata = postDataCreator('/enroldata/ajax_create');


exports.ajax_get_order_by_id = getDataCreator('/order/ajax_get_order_by_id');
exports.ajax_get_order_pay_url = postDataCreator('/order/ajax_get_order_pay_url');


exports.enroldata_delete = postDataCreator('/enroldata/delete');
