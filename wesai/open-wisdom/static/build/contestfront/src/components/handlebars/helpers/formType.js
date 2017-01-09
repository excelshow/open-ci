module.exports = function(opt) {
	var _htmlCode = '';
	if (this.type == "idcard" || this.type == "text") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><input name="' + this.pk_enrol_form_item + '" placeholder="请输入' + this.title + '" type="text"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "invite_code") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="1" type="' + this.type + '">邀请码</div>';
		_htmlCode += '<div class="item-input"><input class="invite_code" placeholder="请输入邀请码" type="text"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "phone") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><input name="' + this.pk_enrol_form_item + '" placeholder="请输入' + this.title + '" type="tel"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "city") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><input name="' + this.pk_enrol_form_item + '" placeholder="请输入' + this.title + '" class="city-picker" type="text"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "date") {
		function getNowFormatDate() {
			var date = new Date();
			var seperator1 = "-";
			var year = date.getFullYear();
			var month = date.getMonth() + 1;
			var strDate = date.getDate();
			if (month >= 1 && month <= 9) {
				month = "0" + month;
			}
			if (strDate >= 0 && strDate <= 9) {
				strDate = "0" + strDate;
			}
			var currentdate = year + seperator1 + month + seperator1 + strDate;
			return currentdate;
		}
		var dateStr = getNowFormatDate();
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><input name="' + this.pk_enrol_form_item + '" value="' +dateStr +'" type="date"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "email") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><input name="' + this.pk_enrol_form_item + '" placeholder="请输入' + this.title + '" type="email"></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "uploadfile" || this.type == "file") {
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input uploadfile-main"><input name="' + this.pk_enrol_form_item + '" class="uploadfile" type="file">';
		_htmlCode += '<div class="uploadfile-btn"><span>+</span></div></div></div></div>';
		return _htmlCode;
	}
	if (this.type == "radio" || this.type == "checkbox") {
		var _opt = "";
		var optVal = JSON.parse(this.option_values);
		for (var i = 0; i < optVal.length; i++) {
			_opt += '<label class="label-checkbox label-' + this.type + ' item-content">' + optVal[i].labeltxt + '';
			_opt += '<input name="' + this.pk_enrol_form_item + '" value="' + optVal[i].labeltxt + '" type="' + this.type + '">';
			_opt += '<div class="item-media"><i class="icon icon-form-checkbox"></i></div></label>';
		}
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input">' + _opt + '</div></div></div>';
		return _htmlCode;
	}
	if (this.type == "select") {
		var _opt = "";
		var optVal = JSON.parse(this.option_values);
		for (var i = 0; i < optVal.length; i++) {
			_opt += '<option>' + optVal[i].labeltxt + '</option>'
		}
		_htmlCode += '<div class="item-content"><div class="item-inner"><div class="item-title label" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += '<div class="item-input"><select name="' + this.pk_enrol_form_item + '">' + _opt + '</select></div></div></div>';
		return _htmlCode;
	}


	if (this.type == "policy") {
		var _opt = "";
		var optVal = JSON.parse(this.option_values);
		for (var i = 0; i < optVal.length; i++) {
			_opt += '<div class="item-input">' + optVal[i].labeltxt + '</div>'

		}
		_htmlCode += '<div class="policy-content">';
		_htmlCode += '<div class="policy-tit item-title" isrequired="' + this.is_required + '" qid="' + this.pk_enrol_form_item + '" title="' + this.title + '" type="' + this.type + '">' + this.title + '</div>';
		_htmlCode += _opt;
		_htmlCode += '<label class="label-checkbox policy-checkbox">'
		_htmlCode += '<input  name="' + this.pk_enrol_form_item + '" type="checkbox" checked="checked">'
		_htmlCode += '<div class="item-media"><i class="icon icon-form-checkbox"></i><span>我已阅读并同意参赛条款</span></div>'
		_htmlCode += '</label></div>'
		return _htmlCode;
	}
};