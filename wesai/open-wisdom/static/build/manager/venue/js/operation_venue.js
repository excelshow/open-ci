$(function() {
    // 获取定义好的数据信息
    var saveVenueBtnObj = $('#save_venue');
    var posterImagesTemplateObj = $("#poster-images-template");
    var posterImageListObj = $('#poster_image_list');

    $('.imagesss').fileupload({
        url: '/contest/res/pre_upload',
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 20092020 // 2 MB
    }).on('fileuploadadd', function(e, data) {
        // console.log(data)
        // $(this).parent().children("label").remove();
        $("<p class='uploadImgLoad'>上传中... 0%</p>").appendTo($(this).parent());
        // $(this).attr("disabled", true);
        // $(this).parent().children("input.btn-blue").addClass("non-point");
    }).on('fileuploadprocessalways', function(e, data) {
        if (data.files.error) {
            // $(this).parent().children("p.uploadImgLoad").remove();
            // $(this).removeAttr("disabled");
            $(this).parent().children("input.btn-blue").removeClass("non-point");
            if (data.files[0].error == 'File type not allowed') {
                alert("图片类型错误");
            }
            if (data.files[0].error == 'File is too large') {
                alert("图片太大");
            }
        }
    }).on('fileuploadprogressall', function(e, data) {
        var $p = $(this).parent().children("p.uploadImgLoad");
        var progress = parseInt(data.loaded / data.total * 100, 10);
        if ($p.length == 0) {
            $("<p class='uploadImgLoad'>上传中... " + progress + "%</p>").appendTo($(this).parent());
        } else {
            // console.info(progress);

            $p.text('上传中... ' + progress + '%');
            if (progress == 100) {
                $p.fadeOut("slow", function() {
                    $(this).remove();
                });
            }
        }
    }).on('fileuploaddone', function(e, data) {
        if (!data.result.error) {
            // var sizeImg=$(this).attr("sizeImg");
            var _that = $(this);
            var maxRetry = 5;
            alert(' 已上传，检查状态中...');
            var interval = setInterval(
                function() {
                    _that.parent().children("p.uploadImgLoad").remove();
                    _that.attr("disabled", true);
                    _that.parent().children("input.btn-blue").addClass("non-point");
                    alert("获取状态中，请稍后...")
                    if (maxRetry <= 0) {
                        alert(' 状态异常，上传失败。');
                        _that.removeAttr("disabled");
                        _that.parent().children("input.btn-blue").removeClass("non-point");
                        clearInterval(interval);
                    }
                    $.getJSON('/contest/res/check_file_state', 'fileid=' + data.result.fileid, function(resData, status) {
                        if (resData.error == 0 && resData.state == 3) {
                            alert('状态正常，上传成功。');
                            renderPosterImages(data.result.fileid);

                            $('#empty_poster').hide();
                            _that.removeAttr("disabled");
                            _that.parent().children("input.btn-blue").removeClass("non-point");
                            clearInterval(interval);
                        }
                    });

                    maxRetry--;
                }, 1000
            );
        } else {
            alert("上传出错！！");
        };
    });



    // 封装console.log
    function printLog(title, info) {
        window.console && console.log(title, info);
    }

    // ------- 配置上传的初始化事件 -------
    function uploadInit() {
        // this 即 editor 对象
        var editor = this;
        // 编辑器中，触发选择图片的按钮的id
        var btnId = editor.customUploadBtnId;
        // 编辑器中，触发选择图片的按钮的父元素的id
        var containerId = editor.customUploadContainerId;

        //实例化一个上传对象
        var uploader = new plupload.Uploader({
            browse_button: btnId, // 选择文件的按钮的id
            url: '/contest/res/editorupload', // 服务器端的上传地址
            flash_swf_url: 'lib/plupload/plupload/Moxie.swf',
            sliverlight_xap_url: 'lib/plupload/plupload/Moxie.xap',
            filters: {
                mime_types: [
                    //只允许上传图片文件 （注意，extensions中，逗号后面不要加空格）
                    {
                        title: "图片文件",
                        extensions: "jpg,gif,png,bmp"
                    }
                ]
            },
            max_file_size: '4000kb', //最大只能上传4000kb的文件
            prevent_duplicates: true //不允许选取重复文件
        });

        //存储所有图片的url地址
        var urls = [];

        //初始化
        uploader.init();

        //绑定文件添加到队列的事件
        uploader.bind('FilesAdded', function(uploader, files) {
            //显示添加进来的文件名
            $.each(files, function(key, value) {
                // printLog('添加文件' + value.name);
            });

            // 文件添加之后，开始执行上传
            uploader.start();
        });

        //单个文件上传之后
        uploader.bind('FileUploaded', function(uploader, file, responseObject) {
            //注意，要从服务器返回图片的url地址，否则上传的图片无法显示在编辑器中
            var url = responseObject.response;
            //先将url地址存储来，待所有图片都上传完了，再统一处理
            urls.push(url);

            // printLog('一个图片上传完成，返回的url是' + url);
        });

        //全部文件上传时候
        uploader.bind('UploadComplete', function(uploader, files) {

            // printLog('所有图片上传完成');

            // 用 try catch 兼容IE低版本的异常情况
            try {
                //打印出所有图片的url地址
                $.each(urls, function(key, value) {
                    // printLog('即将插入图片' + value);

                    // 插入到编辑器中
                    var value = JSON.parse(value);
                    var maxRetry = 5;
                    alert(' 已上传，检查状态中...');
                    var interval = setInterval(
                        function() {
                            alert("获取状态中，请稍后...")
                            if (maxRetry <= 0) {
                                alert(' 状态异常，上传失败。');
                                clearInterval(interval);
                            }
                            $.getJSON('/contest/res/check_file_state', 'fileid=' + value.url, function(resData, status) {
                                if (resData.error == 0 && resData.state == 3) {
                                    alert('状态正常，上传成功。');
                                    editor.command(null, 'insertHtml', '<img src="' + serverPath + value.url + '" style="max-width:100%;"/>');
                                    clearInterval(interval);
                                }
                            });

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

        uploader.bind('Error', function(uploader, file) {
            // 显示进度条
            if (file.code == -600) {
                alert(" 上传的图片不能大于4M！")
            }
        });
        // 上传进度条
        uploader.bind('UploadProgress', function(uploader, file) {
            // 显示进度条
            editor.showUploadProgress(file.percent);
        });
    }

    wangEditor.config.printLog = false;
    // ------- 创建编辑器 -------
    var editor = new wangEditor('editor');
    editor.config.customUpload = true; // 配置自定义上传的开关
    editor.config.customUploadInit = uploadInit; // 配置上传事件，uploadInit方法已经在上面定义了
    editor.create();

    // 设置时间
    jeDate({
        dateCell: ".venue_open_time",
        format: "hh:mm",
        isinitVal: false,
        isToday: false,
        isTime: true
    })

    // 设置关闭按钮
    $(document).on('click', '.close-button', function() {
        var that = $(this);
        layer.confirm('确定删除图片？', {
            btn: ['确认', '取消']
        }, function(index) {
            that.parent().parent().parent().remove()
            layer.close(index);
            var poster_image_list_length = $('#poster_image_list').children().length;
            if (poster_image_list_length < 1) {
                $('#empty_poster').show();
            }
        });
    });

    // 获取保存类型
    var saveType = saveVenueBtnObj.data('save-type');
    var venueId = saveVenueBtnObj.data('venue-id');

    // 保存信息
    $(document).on('click', '#save_venue', function() {
        // 获取form中的数据信息
        var that = $(this);
        var data = getFormData('venue_form');
        data.intro = editor.$txt.html();
        // 判断是添加还是修改
        var saveVenue = addVenue;
        if (saveType == 'edit') {
            saveVenue = editVenue;
            data.venue_id = venueId;
        }

        // 验证数据
        if ($.trim(data.name) == '') {
            alert('场馆名称不能为空');
            return false;
        }

        if (!('types' in data)) {
            alert('场馆项目不能为空');
            return false;
        }

        if ($.trim(data.address) == '') {
            alert('活动的详细地址不能为空');
            return false;
        }

        if (data.locations.district == '' || data.latitude == '') {
            alert('请点击<获取位置>获取位置信息');
            return false;
        }

        if ($.trim(data.open_time.working_days.start) == '') {
            alert('工作日开始时间不能为空');
            return false;
        }

        if ($.trim(data.open_time.working_days.end) == '') {
            alert('工作日结束时间不能为空');
            return false;
        }

        if ($.trim(data.open_time.weekend.start) == '') {
            alert('周末开始时间不能为空');
            return false;
        }

        if ($.trim(data.open_time.weekend.end) == '') {
            alert('周末结束时间不能为空');
            return false;
        }

        if ($.trim(data.open_time.holidays.start) == '') {
            alert('节假日开始时间不能为空');
            return false;
        }

        if ($.trim(data.open_time.holidays.end) == '') {
            alert('节假日结束时间不能为空');
            return false;
        }

        if ($.trim(data.phone) == '') {
            alert('客服电话不能为空');
            return false;
        }

        if (!('images' in data)) {
            alert('活动海报图片不能为空');
            return false;
        }

        if ($.trim(data.intro) == '<p><br></p>') {
            alert('场馆介绍不能为空');
            return false;
        }

        // 发起请求
        saveVenue(data).done(function(result) {
            // 跳转页面先alert一下
            alert('保存成功');
            window.location.href = '/venue/venue/get_list';
        });

        return false;
    });
    //  验证时间是否冲突
    $("#venue_form .choose-date").change(function(e) {
        var e = e || window.event;
        var target = e.target || e.srcElement;
        var formData = getFormData('venue_form');
        var oDiv = target.parentNode;
        var time_start = $(oDiv).children('select').eq(0).val();
        var time_end = $(oDiv).children('select').eq(1).val();
        if (!time_start=='') {
            if (!time_end==''&&time_end <= time_start) {
                alert("结束时间要晚于开始时间");
                target.value = '';
            }
            
        }
    })

    // 编辑页面开始获取图片列表
    if (saveType == 'edit') {
        // 获取场馆ID
        var param = {
            'venue_id': venueId
        };
        getVenueImages(param)
            .done(function(result) {

                // 渲染列表数据
                var images = result.data;
                if (images.length > 0) {
                    for (var i = 0; i < images.length; i++) {
                        renderPosterImages(images[i].fileid);
                    };
                }

            });
    }

    function renderPosterImages(fileid) {
        var posterImagesTemplate = posterImagesTemplateObj.html();
        var posterImagesObj = Handlebars.compile(posterImagesTemplate);
        var imageData = {
            serverPath: serverPath,
            fileid: fileid
        }
        posterImageListObj.append(posterImagesObj(imageData));
    }
});
