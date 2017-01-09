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
    name: '订单关闭',
    className: 'bg-white'

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



$(document).on('pageInit', '#fillForm-page', function(e, id, page) {
	$.showPreloader();
	var queryString = getQueryString();
	var params = {
		'item_id': queryString.item_id
	};
	api_js.getFormByItemId(params).done(function(formData) {
		if (formData.error == 0) {
			params = {
				form_id: formData.result.pk_enrol_form
			};
			api_js.listFormItems(params).done(function(formItemList) {
				if (formItemList.error == 0) {
					if (formData.itemInfo.invite_required == 1) {
						formItemList.data.push({
							type: "invite_code"
						})
					}
					var data = {
						formItemList: formItemList.data
					};
					console.log(data);
					var formList = require('../handlebars/form-list.handlebars');
					var pageHtml = formList(data);
					$('#formList').html(pageHtml);
					$(".city-picker").cityPicker({
						toolbarTemplate: '<header class="bar bar-nav">\
					    <button class="button button-link pull-right close-picker">确定</button>\
					    <h1 class="title">选择收货地址</h1>\
					    </header>'
					});
					setTimeout(function() {
						$.hidePreloader();
					}, 500);
				}
			});
		}
	});
	$(document).on('click', '#from-submit', function(e) {
		var info = [];
		info = fillListForms($("#formList .item-title"), info);
		if (!info) {
			return false;
		}
		var invite_code = $('.invite_code').val();
		if (invite_code == "") {
			$.toast('邀请码不能为空！');
			return false;
		}
		var group_id = queryString.group_id ? queryString.group_id : 0;
		var team_id = queryString.team_id ? queryString.team_id : 0;
		var copies = $('#quantity').text(); 

		var params = {
			'item_id': queryString.item_id,
			'info': info,
			'team_id': team_id,
			'group_id': group_id,
			'invite_code': invite_code,
		};
		api_js.postEnroldata(params).done(function(data) {
			if (data.type == 1) {
				if (data.error == 0) {
					params = {
						'enrol_data_id': data.lastid,
						'copies':copies || 1
					};
					api_js.createOrder(params).done(function(order) {
						if (undefined != order.order_pay_url && order.order_pay_url != '') {
							window.location.href = order.order_pay_url;
						}else {
							$.toast(data.info);
						}
					});
				} else {
					$.toast(data.info);
				}
			} else if (data.type == 2) {
				if (data.error == 0) {
					window.location.href = '/group/join?group_id=' + group_id;
				} else {
					$.toast(data.info);
				}
			} else if (data.type == 3) {
				if (data.error == 0) {
					window.location.href = '/team/join?team_id=' + team_id;
				} else {
					$.toast(data.info);
				}
			}
		});
	})

});


var issummit = false;
var anwser;

function fillListForms(ele, info) {
	ele.each(function() {
		var $thisobj = $(this);
		var formtype = $(this).attr("type");
		var formname = $(this).attr("name");
		var formrequired = $(this).attr("isrequired");
		var formitemid = $(this).attr("qid");
		var formtitle = $(this).attr("title");
		if (formtype == "invite_code") {
			return true;
		}


		switch (formtype) {
			case "text":
			case "date":
			case "number":
				anwser = $(this).parent().find("input[name='" + formitemid + "']").val();
				break;
			case "phone":
				anwser = $(this).parent().find("input[name='" + formitemid + "']").val();
				if (formrequired == '1' && !iphoneReg.test(anwser)) {
					$.toast("提示\n\n请输入有效的手机号码！");
					info = false;
					return false;
				}
				break;
			case "email":
				anwser = $(this).parent().find("input[name='" + formitemid + "']").val();
				var myreg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
				if (formrequired == '1' && !myreg.test(anwser)) {
					$.toast('提示\n\n请输入有效的E_mail！');
					info = false;
					return false;
				}
				break;
			case "idcard":
				anwser = $(this).parent().find("input[name='" + formitemid + "']").val();
				var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
				if (formrequired == '1' && reg.test(anwser) === false) {
					$.toast("提示\n\n请输入有效的身份证!");
					info = false;
					return false;
				}
				break;
			case "radio":
			case "checkbox":
				var da_an = [];
				$(this).parent().find("input:checked").each(function() {
					da_an.push($(this).val())
				});
				anwser = da_an.toString();
				break;
			case "select":
				anwser = $(this).parent().find("select").val();
				break;
			case "uploadfile":
			case "file":
				anwser = $(this).parent().find("input.uploadfile").attr('fileid');
				if (!anwser) {
					anwser = "";
				}
				break;
			case "textarea":
				anwser = $(this).parent().find("textarea").val();
				break;
			case "policy":
				anwser = $(this).parent().find("input").is(':checked');
				if (formrequired == '1' && !anwser) {
					$.toast("请点击同意协议！");
					info = false;
					return false;
				}
				if(anwser){
					anwser="同意";
				}else{
					anwser="不同意";

				}
				break;
			case "city":
				var anwser = $(this).parent().find("input[name='" + formitemid + "']").val();
				break;
			default:
				break;
		}



		//验证规则
		if (formrequired == "1" && anwser == "") {
			issummit = true;
			$.toast(formtitle + "必填");
			info = false;
			return false;
		}
		var anwser_obj = {
			type: formtype,
			title: formtitle,
			qid: formitemid,
			value: anwser
		};
		info.push(anwser_obj);
	});
	console.log(info)
	return info;
}

$(document).on('change', '.uploadfile', function(e) {
	var _that = $(this);
	$('.uploadfile').replaceWith('<input class="uploadfile" type="file" name="images">');
	filefujianChange(_that[0])

	function filefujianChange(target) {
		var item;
		for (var i = 0; i < target.files.length; i++) {
			item = {};
			item.file = target.files[i];
			item.size = target.files[i].size;
			item.name = target.files[i].name;
			item.preview = window.URL.createObjectURL(target.files[i]);
			item.isAccept = false;
			item.current = false;
			item.reader = new FileReader();
			item.reader.onerror = function(evt) {
				switch (evt.target.error.code) {
					case evt.target.error.NOT_FOUND_ERR:
						$.toast('本地文件未找到!');
						break;
					case evt.target.error.NOT_READABLE_ERR:
						$.toast('本地文件没有可读权限');
						break;
					case evt.target.error.ABORT_ERR:
						break; // noop
					default:
						$.toast('读取文件时发生错误');
				};
			};
			item.reader.onabort = function(e) {
				$.toast('文件读取被取消');
			};
			item.reader.readAsBinaryString(target.files[i]);
		}

		var fileSize = 0;
		var size = item.size / 1024;
		if (size > 2000) {
			$.toast("附件不能大于2M");
			target.value = "";
			return
		}
		var fileName = item.name.substring(item.name.lastIndexOf(".") + 1).toLowerCase();
		if (fileName != "jpg" && fileName != "jpeg" && fileName != "pdf" && fileName != "png" && fileName != "dwg" && fileName != "gif") {
			$.toast("请选择图片格式文件上传(jpg,png,gif,dwg,pdf,gif等)！");
			return
		}
		var data = new FormData();
		data.append(0, item.file);
		$.ajax({
			url: '/res/pre_upload',
			type: 'POST',
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string
			// request
			success: function(data, textStatus, jqXHR) {
				if (data.error == 0) {
					var maxRetry = 5;
					$.toast(' 已上传，检查状态中...');
					var interval = setInterval(
						function() {
							$.toast("获取状态中，请稍后...")
							if (maxRetry <= 0) {
								$.toast(' 状态异常，上传失败。');
								clearInterval(interval);
							}
							$.getJSON('/res/check_file_state', 'fileid=' + data.fileid, function(resData, status) {
								if (resData.error == 0 && resData.state == 3) {
									var serverPath = resData.page_vars.CDN_URL;
									$.toast('状态正常，上传成功。');
									var img = new Image();
									img.src = serverPath + '/' + data.fileid + '?imageMogr2/thumbnail/100x';
									img.onload = function() {
										$('.uploadfile-main').find('.uploadfile-btn').html(img);
									}
									var fileid = data.fileid.toString();
									$('.uploadfile-main').find('.uploadfile').attr('fileid', data.fileid);
									clearInterval(interval);
								}
							});
							maxRetry--;
						}, 1000
					);
				} else {
					$.toast("上传错误");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$.toast("上传错误");
			}
		});
	}
});