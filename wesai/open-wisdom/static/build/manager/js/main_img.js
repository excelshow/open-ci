$(function() {
    uploadImageAjaxDelete = function(url, obj) {
        $.ajax({
            url: url,
            async: false,
            dataType: "text",
            success: function(data) {
                if (data == '1') {
                    //如果删除成功，恢复file的使用，同时是图片渐变消失
                    $(obj).parent().children("input[type='file']").removeAttr("disabled");
                    $(obj).parent().children("img").fadeOut("slow", function() {
                        $(this).add($(obj).parent().children("a")).add($(obj).parent().children("input:hidden")).add($(obj).parent().children("br")).remove();
                    });
                } else {
                    alert('图片删除失败！');
                }
            }
        });
    }

    $('.imagesss').fileupload({
        url: '/contest/res/pre_upload',
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 4000000 // 2 MB
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
                $("p.uploadImgLoad").remove();
                alert("上传图片不能大于4M！");
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
            if(!data.result.error){
                var sizeImg=$(this).attr("sizeImg");
                var _that=$(this);
                var maxRetry = 5;
                alert(' 已上传，检查状态中...');
                var interval = setInterval(
                        function () {
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
                            $.getJSON('/contest/res/check_file_state', 'fileid=' + data.result.fileid, function (resData, status) {
                                        if (resData.error == 0 && resData.state == 3) {
                                            alert('状态正常，上传成功。');
                                            var imageUrl = '<img src="' + serverPath + data.result.fileid+ sizeImg+'"/>';
                                            _that.parent().find('.img-thump').addClass('img-thump-bg').html(imageUrl);
                                            _that.parent().find(".hiddenImg").val(data.result.fileid);
                                            _that.removeAttr("disabled");
                                            _that.parent().children("input.btn-blue").removeClass("non-point");
                                            clearInterval(interval);
                                        }
                                    }
                            );

                            maxRetry--;
                        }, 1000
                );
            }else{
                alert("上传出错！！");
            };
    });


});