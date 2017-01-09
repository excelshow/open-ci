   {literal} 
    <script type="text/javascript">
        //编辑器
        um = UM.getEditor(
            'editor', {
			        imageUrl : "/contest/res/editorupload",
			        imagePath: serverPath,
			        lang     : /^zh/.test(navigator.language || navigator.browserLanguage || navigator.userLanguage) ? 'zh-cn' : 'en',
			        langPath : UMEDITOR_CONFIG.UMEDITOR_HOME_URL + "lang/",
			        focus    : true,
			        initialFrameWidth:800, //初始化编辑器宽度,默认500
                    initialFrameHeight:300  //初始化编辑器高度,默认500
		        }
        );
        //时间选择器
        //$('.datetimepicker').datetimepicker();
        jeDate(
            {
	            dateCell : "#date",
	            format   : "YYYY-MM-DD",
	            isinitVal: false,
	            isTime   : false,
	            minDate  : "2014-01-01"
            }
        )
        //更新活动基本信息
        function getQueryString(name) { 
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
        var r = window.location.search.substr(1).match(reg); 
        if (r != null) return unescape(r[2]); return null; 
        } 
        $("#updatecontest").click(

            function () {
	            var cid  = getQueryString('cid');
	            var name = $("#name").val();
	            if (name == "") {
		            alert("活动名称不能为空");
		            $("#name").focus();
		            return false
	            }
	            var ename = $("#ename").val();
	            var intro = UM.getEditor('editor').getContent();
	            var logo  = $("#logo").val();
	            if (logo == "") {
		            alert("活动logo不能为空");
		            $("#logo").focus();
		            return false
	            }
	            var poster = $("#poster").val();
	            if (poster == "") {
		            alert("活动海报图片不能为空");
		            $("#poster").focus();
		            return false
	            }
	            var banner = $("#banner").val();
	            if (banner == "") {
		            alert("活动横幅图片不能为空");
		            $("#banner").focus();
		            return false
	            }
	            var date = $("#date").val();
	            if (date == "") {
		            alert("请选择竞赛日前");
		            $("#date").focus();
		            return false
	            }
	            var country_scope = $("input[name='country_scope']:checked").val();
	            var location = $("#location").val();
	            if (location == "") {
		            alert("活动起始地点不能为空");
		            $("#date").focus();
		            return false
	            }
	            //地址位置
	            var country = "中国";
	            var province = $("#province").val();
	            var city = $("#city").val();
	            var district = $("#district").val();

	            var gtype = $("#gtype").val();
	            if (gtype == "") {
		            alert("请选择活动类型");
		            $("#gtype").focus();
		            return false
	            }
	            //活动级别
	            var deliver_gear = $('input:radio[name="deliver_gear"]:checked').val();
	            if (deliver_gear == undefined || deliver_gear == "") {
		            alert("请选择是否需要邮寄装备");
		            $("input[type='radio'][name='deliver_gear']").focus();
		            return false
	            }
	            var lottery = $('input:radio[name="lottery"]:checked').val();
	            if (lottery == undefined || lottery == "") {
		            alert("请选择是否需要复核");
		            $("input[type='radio'][name='lottery']").focus();
		            return false
	            }
	            var service_tel = $('#service_tel').val();
				if (service_tel == '') {
					alert('请填写客服电话');
					$('#service_tel').focus();
					return false;
				}
	            var source = $("#source").val();
	            if (source == "") {
		            alert("请选择活动来源");
		            $("#source").focus();
		            return false
	            }

				var template = $('input:radio[name="template"]:checked').val();
				if (undefined == template || template == '') {
					alert('请选择页面模板');
					return false;
				}

	            //提交数据
	            var postData = {
		            "cid"         : cid,
		            "name"        : name,
		            "ename"       : ename,
		            "intro"       : intro,
		            "logo"        : logo,
		            "poster"      : poster,
		            "banner"      : banner,
		            "date"        : date,
		            "source"      : source,
		            "country_scope":country_scope,
		            "location"    : location,
		            "gtype"       : gtype,
		            "lottery"     : lottery,
		            "deliver_gear": deliver_gear,
		            "service_tel"  : service_tel,
					'template' : template
	            };
	            $.post("/contest/contest/ajax_update_contest", postData, function (data) {
				            if (data['error'] == '0') {
				            	if(country_scope =="1"){
					            	if(province!=""&&city!=""&&district!=""){
			                            var localData = {
			                                "cid": cid,
			                                "country": country,
			                                "province": province,
			                                "city": city,
			                                "district":district
			                            }
			                            $.post("/contest/contest/ajax_add_location", localData, function (data) {
			                            	
			                                    }, 'json'
			                            );
	                        		}
                        		}
								window.location.href = '/contest/contest/detail_contest?cid=' + cid;
				            } else {
					            alert(data['info']);
				            }
			            }, 'json'
	            );
	            return false;
            }
        )
    </script>
    <script type="text/javascript">
	//新增活动项目
	jeDate(
			{
				dateCell : "#item_start",
				format   : "YYYY-MM-DD hh:mm:ss",
				isinitVal: false,
				isTime   : true,
				minDate  : "2014-01-01 00:00:00"
			}
	);
	jeDate(
			{
				dateCell : "#item_end",
				format   : "YYYY-MM-DD hh:mm:ss",
				isinitVal: false,
				isTime   : true,
				minDate  : "2014-01-01 00:00:00"
			}
	);
	$("#add-item").on('click',
			function () {
				var cid             = _contestConfig.fk_contest;
				var item_name       = $("#item_name");
				var item_name_value = item_name.val();
				var item_fee        = $("#item_fee");
				var item_fee_value  = item_fee.val();
				var item_max_stock  = $("#item_max_stock").val();
				var item_start      = $("#item_start").val();
				var item_end        = $("#item_end").val();
				var itemid          = $("#pk_contest_items").val();
				var invite_required = $("#invite_required").val();
				if (item_name_value == "") {
					item_name.focus();
					return false;
				}
				if (item_fee_value == "") {
					item_fee.focus();
					return false;
				}
				if(invite_required =="1"){
					if(item_max_stock<1 || item_max_stock==""){
                        alert("参赛人数不能为空");
                        $("#item_max_stock").focus();
                        return false;
					}
				}
				var postData = {
					"cid"            : cid,
					"itemid"         : itemid,
					"name"           : item_name_value,
					"max_stock"      : item_max_stock,
					"fee"            : item_fee_value * 100,
					"start"          : item_start,
					"end"            : item_end,
					"invite_required": invite_required
				};
				$.post("/contest/contest/ajax_add_items", postData, function (data) {
							if (data['error'] == '0') {
								window.location.reload();
							} else {
								alert(data['info']);
							}
						}, 'json'
				);
			}
	)
</script>
    {/literal}
