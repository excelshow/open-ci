//表单的正则验证
var regFormList    = {};
regFormList.text   = {
	'_regExp_'  : '^[\u4e00-\u9fa5A-Za-z0-9-_*]{1,}$',
	'_errortip_': '不能为空'
};
regFormList.idcard = {
	'_regExp_'  : '^[0-9]{15}([0-9]{2}[A-Za-z0-9])?$',
	'_errortip_': '请输入正确的身份证号码'
};
regFormList.phone  = {
	'_regExp_'  : '^[0-9]{11}$',
	'_errortip_': '您输入的手机号码不正确请重新输入'
};
regFormList.email  = {
	'_regExp_'  : '(^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9_-]+$)|(^$)',
	'_errortip_': '请输入正确的邮箱'
};
regFormList.policy = {
	'_regExp_'  : '^[\u4e00-\u9fa5A-Za-z0-9-_*]{1,}$',
	'_errortip_': '你没有同意条款'
};
//页面滚动
// var myScroll;
// function loaded()  {
// 	myScroll = new IScroll('#wrapper', {
// 		    probeType            : 1,
//             mouseWheel           : true,
//             click                : true,
//             scrollbars           : true,
//             fadeScrollbars       : true,
//             interactiveScrollbars: false,
//             keyBindings          : false,
//             deceleration         : 0.0002,
//             startY               : 0,
//             preventDefault: true,
// 		    preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|div)$/ },
// 	});

// }
// document.addEventListener('onload', loaded(), false);
//表单操作
$(function () {
	//城市选择
	jQuery("select.province").change(function () {
		var pindex  = jQuery(this).find("option:selected").attr("title");
		var cityobj = CityListData[pindex];
		//添加选择项目
		var newopt  = '';
		for (var p = 0; p < cityobj.sub.length; p++) {
			var subobj = cityobj['sub'][p];
			newopt += '<option value="' + subobj.name + '">' + subobj.name + '</option>';
		}
		jQuery(this).parent().next().find("select").html(newopt).show();
	});
	//重新选择
	$(".reselect").change(function () {
		var itemid = $(this).val();
		if (itemid != "") {
			location.href = "/contest/formenroll?cid=" + cid + "&itemid=" + itemid;
		}
	});
	//图片上传
	wx.ready(function () {
		$(document).on("click touchstart", ".uploadImage", function () {
			var $thisobj    = $(this);
			var qid         = $(this).attr("qid");
			var images      = {};
			images.localId  = [];
			images.serverId = [];
			wx.chooseImage({
				               count     : 1,
				               sizeType  : ['original', 'compressed'],
				               sourceType: ['album', 'camera'],
				               success   : function (res) {
					               var localIds = res.localIds;
					               wx.uploadImage({
						                              localId           : localIds.toString(),
						                              isShowProgressTips: 1,
						                              success           : function (res_up) {
							                              $("input[name='" + qid + "file']").val(res_up.serverId);
							                              var image = document.createElement("img");
							                              image.src = localIds;
							                              $thisobj.parent().find(".wx-imglist").html(image);
						                              },
						                              fail              : function (res_up) {
							                              alert(JSON.stringify(res_up));
						                              }
					                              });
				               }
			               });
		});
	});
	var orderid = "";
	//提交订单
	$("#next-submit").click(function () {
		if (state < 3) {
			alert("报名尚未开始");
			return false;
		}
		if (state > 4) {
			alert("报名已经结束");
			return false;
		}
		//地址验证
		if (deliver_gear == 1) {
			if (shipping_addr.name == '' || shipping_addr.name == '点击填写收货地址' || shipping_addr.addr == '' || shipping_addr.mobile == '') {
				alert("请完整填写收货地址");
				return false;
			}
		}
		//邀请码验证
		if (invite_required == "1") {
			invite_code = $("#invite_code").val();
			if (invite_code == '') {
				// goToErrorPos($("#invite_code"));
				alert("请填写正确的邀请码");
				return false;
			}
		}
		var info     = {};
		var issummit = false;
		$("div").removeClass("err-show");
		$("#question-list li").each(function () {
			var $thisobj = $(this);
			var formtype     = $(this).attr("type");
			var formname     = $(this).attr("name");
			var formrequired = $(this).attr("isrequired");
			var formitemid   = $(this).attr("qid");
			var formtitle    = $(this).find(".q-title").text();
			switch (formtype) {
				case "text":
				case "date":
				case "number":
				case "phone":
				case "email":
				case "idcard":
					anwser = $(this).find("input[name='" + formitemid + "']").val();
					break;
				case "radio":
					anwser = $(this).find(".chkon").val();
					break;
				case "checkbox":
					var da_an = [];
					$(this).find(".chkon").each(function () {
						da_an.push($(this).val())
					})
					// if(da_an.length <2){
					// 	issummit    = true;
						// goToErrorPos($thisobj);
					// 	alert(formtitle+"至少选择2项");
					// 	return false;
					// }
					anwser = da_an.toString();
					break;
				case "select":
					anwser = $(this).find("select[name='" + formitemid + "']").val();
					break;
				case "uploadfile":
					anwser = $(this).find("input[name='" + formitemid + "file']").val();
					break;
				case "file":
					anwser = $(this).find("input[name='" + formitemid + "file']").val();
					break;
				case "textarea":
					anwser = $(this).find("textarea[name='" + formitemid + "']").val();
					break;
				case "policy":
					anwser = $(this).find(".chkon").val();
					if (anwser == undefined || anwser == "undefined") {
						anwser = "";
					}
					break;
				case "city":
					var province = $(this).find(".province").val();
					if (province == "请选择" || province == "") {
						issummit    = true;
						// goToErrorPos($thisobj);
						alert("请选择省份");
						return false;
					}
					var city = $(this).find(".city").val();
					if (city == "请选择" || city == "") {
						issummit    = true;
						// goToErrorPos($thisobj);
						alert("请选择城市");
						return false;
					}
					anwser = province + "," + city;
					break;
				default:
					break;
			}
			//验证规则
			if (anwser == undefined || anwser == "undefined") {
				anwser = "";
			}
			//验证规则
			if (formrequired == "1") {
				var reglist = ['phone', 'email', 'policy'];
				var isreg   = $.inArray(formtype, reglist);
				if (isreg > -1) {
					var formreg  = regFormList[formtype]._regExp_;
					var errortip = regFormList[formtype]._errortip_;
					var reg      = new RegExp(formreg);
					if (!reg.test(anwser) || anwser == "") {
						issummit    = true;
						//debugger;
						// goToErrorPos($thisobj);
						alert(errortip);
						return false;
					}
				} else {
					if (anwser == "") {
						issummit    = true;
						//debugger;
						// goToErrorPos($thisobj);
						alert(formtitle+"必填");
						return false;
					}
				}
			}
			var anwser_obj   = {
				type : formtype,
				title: formtitle,
				qid  : formitemid,
				value: anwser
			};
			info[formitemid] = anwser_obj;
		});
		//表单数据
		var count=$('#quantity-display').text();
		var postData = {
			'count'        : count,
			"cid"          : cid,
			"itemid"       : itemid,
			"amount"       : fee,
			"info"         : info,
			"shipping_addr": shipping_addr,
			"invite_code"  : invite_code
		}
		if (!issummit) {
			$("#next-submit").hide();
			$("#yetsubmit").show();
			$.post("/contest/ajax_addorder", postData, function (data) {
				if (data.error == 0) {
					//提交信息
					payUrl        = data.pay_url;
					location.href = payUrl;
				} else {
					alert(data.info);
					$("#next-submit").show();
					$("#yetsubmit").hide();
					if (data.error == -999) {
						location.href = '/contest/detail?cid=' + cid;
					}
				}
			}, "json");
		}
		return false;
	}) 
  //   function goToErrorPos($thisobj){
  //   	var thispos = $thisobj.position();
		// var w_t     = -thispos.top;
		// myScroll.scrollTo(0, w_t, 100);
		// $thisobj.find(".q-content").addClass("err-show");
		// setTimeout(function(){
		// 	$thisobj.find(".q-content").removeClass("err-show");
		// },"1000")
  //   }
	$(document).on("click", "input[type='radio']", function () {
		$(this).parent().parent().find("input[type='radio']").removeClass("chkon");
		$(this).addClass("chkon");
	})
	$(document).on("click", "input[type='checkbox']", function () {
		$(this).toggleClass("chkon");
	})
	$(document).on("focus", ":input", function () {
		$("#next-submit").prop("position", "static");
	})
	//焦点验证数据
	$(document).on("blur", "input.form-control", function () {
		$("#next-submit").prop("position", "fixed");
	})
	$(document).on("touchmove", ".policy", function (e){
		$(this).scrollTop($(this).height())
	})
	$("#next-submit").show();



	// $('input[type="text"]').bind('focus',function(){  
 //        $('.act-pannel').css('position','static');  
 //    }).bind('blur',function(){  
 //        $('.act-pannel').css({'position':'fixed','bottom':'0'});  
 //    });
})
