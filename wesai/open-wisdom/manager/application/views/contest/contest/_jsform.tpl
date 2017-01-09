<script>
	var cid ={if !empty($data->pk_contest)} {$data->pk_contest|json_encode}{else}""{/if} ;
</script>


{literal}
	<script type="text/javascript">
		// 封装console.log
		function printLog(title, info) {
			window.console && console.log(title, info);
		}
		// ------- 配置上传的初始化事件 -------
		function uploadInit() {
			// this 即 editor 对象
			var editor      = this;
			// 编辑器中，触发选择图片的按钮的id
			var btnId       = editor.customUploadBtnId;
			// 编辑器中，触发选择图片的按钮的父元素的id
			var containerId = editor.customUploadContainerId;

			//实例化一个上传对象
			var uploader = new plupload.Uploader({
				browse_button      : btnId,  // 选择文件的按钮的id
				url                : '/contest/res/editorupload',  // 服务器端的上传地址
				flash_swf_url      : 'lib/plupload/plupload/Moxie.swf',
				sliverlight_xap_url: 'lib/plupload/plupload/Moxie.xap',
				filters            : {
					mime_types: [
						//只允许上传图片文件 （注意，extensions中，逗号后面不要加空格）
						{title: "图片文件", extensions: "jpg,gif,png,bmp"}
					]
				},
				max_file_size      : '4000kb', //最大只能上传4000kb的文件
				prevent_duplicates : true //不允许选取重复文件
			});

			//存储所有图片的url地址
			var urls = [];
			//初始化
			uploader.init();

			//绑定文件添加到队列的事件
			uploader.bind('FilesAdded', function (uploader, files) {
				//显示添加进来的文件名
				$.each(files, function (key, value) {

				});

				// 文件添加之后，开始执行上传
				uploader.start();
			});

			//单个文件上传之后
			uploader.bind('FileUploaded', function (uploader, file, responseObject) {
				//注意，要从服务器返回图片的url地址，否则上传的图片无法显示在编辑器中
				var url = responseObject.response;

				//先将url地址存储来，待所有图片都上传完了，再统一处理
				urls.push(url);

			});

			//全部文件上传时候
			uploader.bind('UploadComplete', function (uploader, files) {
				// printLog('所有图片上传完成');
				// 用 try catch 兼容IE低版本的异常情况
				try {
					//打印出所有图片的url地址
					$.each(urls, function (key, value) {
						// printLog('即将插入图片' + value);
						// 插入到编辑器中

						var value = JSON.parse(value);
						if (value.error) {
							alert(' 状态异常，上传失败。');
							return;
						}
						var maxRetry = 5;
						alert(' 已上传，检查状态中...');
						var interval = setInterval(
								function () {
									alert("获取状态中，请稍后...")
									if (maxRetry <= 0) {
										alert(' 状态异常，上传失败。');
										clearInterval(interval);
									}
									$.getJSON('/contest/res/check_file_state', 'fileid=' + value.url, function (resData, status) {
										          if (resData.error == 0 && resData.state == 3) {
											          alert('状态正常，上传成功。');
											          editor.command(null, 'insertHtml', '<img src="' + serverPath + value.url + '" style="max-width:100%;"/>');
											          clearInterval(interval);
										          }
									          }
									);

									maxRetry--;
								}, 1000
						);

					});
				} catch (ex) {
					// 此处可不写代码
				} finally {
					//清空url数组
					urls = [];

					// 隐藏进度条
					editor.hideUploadProgress();
				}
			});
			uploader.bind('Error', function (uploader, file) {
				// 显示进度条
				if (file.code == -600) {
					alert(" 上传的图片不能大于4M！")
				}
			});
			// 上传进度条
			uploader.bind('UploadProgress', function (uploader, file) {
				// 显示进度条
				editor.showUploadProgress(file.percent);
			});
		}

		wangEditor.config.printLog = false;
		// ------- 创建编辑器 -------
		var editor                 = new wangEditor('editor');

		editor.config.customUpload = true;  // 配置自定义上传的开关

		editor.config.customUploadInit = uploadInit;  // 配置上传事件，uploadInit方法已经在上面定义了
		editor.create();

		var start = {
			dateCell : '#sdate_start',
			format   : 'YYYY-MM-DD',
			// minDate: jeDate.now(0), //设定最小日期为当前日期
			isinitVal: false,
			festival : true,
			ishmsVal : false,
			maxDate  : '2099-06-30 23:59:59', //最大日期
			choosefun: function (elem, datas) {
				end.minDate = datas; //开始日选好后，重置结束日的最小日期
			}
		};
		var end   = {
			dateCell : '#sdate_end',
			format   : 'YYYY-MM-DD',
			minDate  : jeDate.now(0), //设定最小日期为当前日期
			festival : true,
			maxDate  : '2099-06-16 23:59:59', //最大日期
			choosefun: function (elem, datas) {
				start.maxDate = datas; //将结束日的初始值设定为开始日的最大日期
			}
		};
		jeDate(start);
		jeDate(end);

		$("#savecontest").on('click', function () {
			contestAdd('ajax_update_contest')
		})
		$("#savecontestAdd").on('click', function () {
			contestAdd('ajax_add_contest')
		})
		function contestAdd(conteststatusUrl) {
			var name = $("#name").val();
			if (name == "") {
				alert("活动名称不能为空");
				$("#name").focus();
				return false
			}
			var ename = $("#ename").val();
			var intro = editor.$txt.html();
			var logo  = $("#logo").val();
			if (logo == "") {
				alert("活动logo不能为空");
				$("#logo").parent().find('input[type="button"]').get(0).focus();
				return false
			}
			var poster = $("#poster").val();
			if (poster == "") {
				alert("活动海报图片不能为空");
				$("#poster").parent().find('input[type="button"]').get(0).focus();
				return false
			}
			var banner = $("#banner").val();
			if (banner == "") {
				alert("活动横幅图片不能为空");
				$("#banner").parent().find('input[type="button"]').get(0).focus();
				return false
			}
			var sdate_start = $("#sdate_start").val();
			if (sdate_start == "") {
				alert("请选择活动开始日期");
				$("#sdate_start").focus();
				return false
			}
			var sdate_end = $("#sdate_end").val();
			if (sdate_end == "") {
				alert("请选择活动截止日期");
				$("#sdate_end").focus();
				return false
			}
			var country_scope = $("input[name='country_scope']:checked").val();
			var location      = $("#location").val();
			if (location == "") {
				alert("活动地点不能为空");
				$("#location").focus();
				return false
			}
			//地址位置
			var country  = "中国";
			var province = $("#tmp-province").val();
			var city     = $("#tmp-city").val();
			var district = $("#tmp-district").val();
			//级别
			var level    = $('#level').val();
			//类型
			var gtype    = $('#gtype').val();
			if (gtype == "") {
				alert("请选择活动类型");
				$("#gtype").focus();
				return false
			}
			var deliver_gear = $('input:radio[name="deliver_gear"]:checked').val();
				deliver_gear = deliver_gear ? deliver_gear : 2;
			if (deliver_gear == undefined || deliver_gear == "") {
				alert("请选择是否需要邮寄装备");
				$("input[type='radio'][name='deliver_gear']").focus();
				return false
			}
			var service_tel  = $('#service_tel').val();
			var service_mail = $('#service_mail').val();

			if (service_tel == '' && service_mail == '') {
				alert('请填写客服联系方式');
				$('#service_tel').focus();
				return false;
			}

			var template = $('input:radio[name="template"]:checked').val();
			if (undefined == template || template == '') {
				alert('请选择页面模板');
				return false;
			}

			var show_enrol_data_count = $('input:radio[name="show_enrol_data_count"]:checked').val();
			if (undefined == show_enrol_data_count || show_enrol_data_count == '') {
				alert('请选择是否显示已售数目');
				return false;
			}
			var source   = 1;
			//提交数据
			var postData = {
				"cid"          : cid,
				"name"         : name,
				"ename"        : ename,
				"intro"        : intro,
				"logo"         : logo,
				"poster"       : poster,
				"banner"       : banner,
				"sdate_start"  : sdate_start,
				"sdate_end"    : sdate_end,
				"source"       : source,
				"country_scope": country_scope,
				"location"     : location,
				"gtype"        : gtype,
				"level"        : level,
				"lottery"      : 2,
				"deliver_gear" : deliver_gear,
				"service_tel"  : service_tel,
				'service_mail' : service_mail,
				'template'     : template,
				'show_enrol_data_count'     : show_enrol_data_count
			};
			$("#savecontest").hide();
			$("#savecontestAdd").hide();
			$("#savecontestloging").show().removeClass("hide");
			console.log(conteststatusUrl);
			$.post("/contest/contest/" + conteststatusUrl + "", postData, function (data) {
				if (data.error == '0') {
					if (country_scope == 1) {
						locationAdd(cid, data);
					}else{
						if (cid != '') {
							window.location.href = "/contest/contest/detail_contest?cid=" + cid;
						} else {
							window.location.href = "/contest/contest/detail_contest?cid=" + data.lastid;
						}
					};

				} else {
					$("#savecontestAdd").show();
					$("#savecontest").show();
					$("#savecontestloging").hide();
					alert(data['info']);
				}
			}, 'json');
			function locationAdd(cid, data) {
				//地理位置保存
				var cid = data['lastid'] ? data['lastid'] : cid;
				if (province != "" && city != "") {
					var localData = {
						"cid"     : cid,
						"country" : country,
						"province": province,
						"city"    : city,
						"district": district
					};
					$.post("/contest/contest/ajax_add_location", localData, function (data) {
							if(data.error == '0' ){
								window.location.href = "/contest/contest/detail_contest?cid=" + cid;
							}
					}, 'json');
				} else {
					alert("请选择地址");
					$("#savecontestAdd").show();
					$("#savecontest").show();
					$("#savecontestloging").hide();
				}

			}

			return false;
		}
	</script>
{/literal}
