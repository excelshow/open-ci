//手机号验证
var userTools = window.userTools || {};
//验证登录
userTools     = {
	gourl         : "",
	showLogin     : function (url) {
		$(".mask-bg").fadeIn();
		$(".loginForm").fadeIn();
		$(".loginForm input").val("");
		this.gourl = url;
	},
	refreshCaptcha: function () {
		document.getElementById('captcha').src = '/user/captcha?' + Math.random();
	},
	sendcode      : function () {
		var that      = this;
		var totalTime = 60;
		var mobile    = $("#mobile").val();
		var mobilereg = /^1\d{10}$/;
		if (!mobilereg.test(mobile)) {
			$("#mobile").focus();
			this.errorTip("手机号格式不正确");
			return false;
		}
		var tucode = $("#tucode").val();
		if (tucode == "") {
			this.errorTip("图形验证码不能为空");
			return false;
		}

		var cid = $('#cid').val();
		$.post("/user/ajax_send_sms_verify_code", {
			mobile: mobile,
			tucode: tucode,
			cid: cid
		}, function (res) {
			if (res.error == 0) {
				$(".vcode").hide();
				$(".timecounter").show().removeClass("hide");
				totalTime--;
				var s_seconds = totalTime;
				$(".timecounter").text(s_seconds + "秒");
				st = setInterval(function () {
					totalTime--;
					s_seconds = totalTime;
					$(".timecounter").text(s_seconds + "秒");
					if (totalTime < 1) {
						$(".vcode").show();
						$(".timecounter").hide().text("59秒");
						clearInterval(st);
					}
				}, "1000");
			} else {
				$(".refesh").click();
				$("#tucode").val("");
				that.errorTip(res.info);
			}
		}, "json");
		return false;

	},
	userReg       : function () {
		var that      = this;
		var mobile    = $("#mobile").val();
		var mobilereg = /^1\d{10}$/;
		if (!mobilereg.test(mobile)) {
			$("#mobile").focus();
			this.errorTip("手机号格式不正确");
			return false;
		}
		var tucode = $("#tucode").val();
		if (tucode == "") {
			this.errorTip("图形验证码不能为空");
			return false;
		}
		var vcode = $("#valcode").val();
		if (vcode == "") {
			this.errorTip("手机验证码不能为空");
			return false;
		}
		$.post("/user/ajax_verify_sms_code", {
			mobile: mobile,
			vcode : vcode,
			tucode: tucode
		}, function (res) {
			if (res.error == 0) {
				that.closeLogin();
				that.errorTip(res.info)
				location.href = that.gourl;
			} else {
				$(".refesh").click();
				$("#tucode").val("");
				that.errorTip(res.info);
			}
		}, "json");
		return false;
	},
	errorTip      : function (msg) {
		debugger;
		$(".error-show").text(msg);
		setTimeout(function () {
			$(".error-show").text("");
		}, "2000");
	},
	closeLogin    : function () {
		$(".mask-bg").fadeOut();
		$(".loginForm").fadeOut();
		$(".loginForm input").val("");
	}
}
