$(function() {
    var saveVenueResBtnObj = $('#save_venue_res');

    // 添加规则
    $(document).on('click', '[operation-add]', function(e) {
        var that = $(this);
        var type = that.data('date-type');
        var listId = '#' + type + '_list';
        var count = $(listId + ' .clearfix').length;
        if (count > 24) {
            alert('价格规则最多添加24个');
            return false;
        }
        // 添加新的规则
        var priceTemplate = $('#price-template').html();
        var priceObj = Handlebars.compile(priceTemplate);
        var randomStr = Math.random().toString(36).substr(8);
        var data = {
            type: type,
            randomStr: randomStr
        }
        Handlebars.registerHelper('select_start', function() {
            var select = __renderSelect(data.randomStr, data.type, 'time_start');
            return select;
        });

        Handlebars.registerHelper('select_end', function() {
            var select = __renderSelect(randomStr, type, 'time_end');
            return select;
        });
        console.log(priceTemplate)
            // console.log(priceObj(data))
        $(listId).append(priceObj(data));
    });

    // 删除规则
    $(document).on('click', '[operation-delete]', function(e) {
        $(this).parent().remove();
    });

    //  验证时间是否冲突
    $("#area_res_form #price_list").change(function(e) {
        // var e = e || window.event;
        var target = e.target || e.srcElement;
        var formData = getFormData('area_res_form');
        if (target.nodeName.toLowerCase() == "select") {
            //  选择有id的父元素
            var div = target.parentNode.parentNode.parentNode;
            //  获取id
            var id = $(div).attr('id');
            if (id == 'holidays_list') {
                if (!_timeVerify(formData.rule_detail.holidays)) {
                    target.value = '';
                }
            }
            if (id == 'weekend_list') {
                if (!_timeVerify(formData.rule_detail.weekend)) {
                    target.value = '';
                }
            }
            if (id == 'working_days_list') {
                if (!_timeVerify(formData.rule_detail.working_days)) {
                    target.value = '';
                }
            }
        }
    })

    // 保存信息
    $(document).on('click', '#save_venue_res.save_venue_res', function(e) {
        $("#save_venue_res").removeClass('save_venue_res');
        // 获取form中的数据信息
        var formData = getFormData('area_res_form');
        // 验证数据
        if ($.trim(formData.name) == '') {
            alert('场地名称不能为空');
            $("#save_venue_res").addClass('save_venue_res');
            return false;
        }
        if (!('type' in formData)) {
            alert('场馆项目不能为空');
            $("#save_venue_res").addClass('save_venue_res');
            return false;
        }
        if (!verifyPrice(formData.rule_detail)) {
            $("#save_venue_res").addClass('save_venue_res');
            return false;
        }
        // 判断是添加还是修改
        var saveAreaRes = addAreaRes;
        if (formData.venue_area_res_id) {
            saveAreaRes = editAreaRes;
        }
        saveAreaRes(formData).done(function(result) {
            if (!result.error) {
                alert('保存成功');
                window.location.href = '/venue/area_res/get_list_by_venue?venue_id=' + formData.venue_id;
            } else {
                alert(data.info);
                $("#save_venue_res").addClass('save_venue_res');
            }
        });
    });

    //  场馆信息验证
    function verifyPrice(obj) {
        if (!('working_days' in obj)) {
            alert('场馆价格 工作日不能为空');
            return false;
        }
        if (!('weekend' in obj)) {
            alert('场馆价格 周末不能为空');
            return false;
        }
        if (!('holidays' in obj)) {
            alert('场馆价格 节假日不能为空');
            return false;
        }
        // 再验证价格
        for (var i in obj) {
            if (!__verifyPriceDate(obj[i])) {
                return false;
            }
        }
        return true;
    }
    //  价格表单验证
    function __verifyPriceDate(obj) {
        for (var index in obj) {
            var start = $.trim(obj[index].time_start);
            var end = $.trim(obj[index].time_end);
            var price = $.trim(obj[index].price);
            if (start == '') {
                alert('开始时间不能为空');
                return false;
            }
            if (end == '') {
                alert('结束时间不能为空');
                return false;
            }
            if (price == '' || price <= 0) {
                alert('价格不能为空');
                return false;
            }
        }
        return true;
    }
    //  开放时间段验证
    function _timeVerify(obj) {
        var timeList = [];
        var isTrue = 1;
        for (var index in obj) {
            var s = parseInt(obj[index].time_start);
            var e = parseInt(obj[index].time_end);
            timeList.push(s);
            if (s >= e) {
                alert("结束时间要晚于开始时间");
                return false;
            }
            for (var i = 1; i < e - s; i++) {
                var num = s + i;
                timeList.push(num);
            }
        }
        timeList = timeList.sort();
        for (var i = 0; i < timeList.length; i++) {
            if (timeList[i] == timeList[i + 1]) {
                alert("时间重复了!")
                isTrue = false;
            }
        }
        return isTrue;
    }
    /**
     * 渲染select
     */
    function __renderSelect(randomStr, type, dateNameType) {
        var direction = 'pull-left';
        if (dateNameType == 'time_end') {
            direction = 'pull-right';
        }

        var select = '<select class="form-control ' + direction + ' wah200" name="rule_detail[' + type + '][' + randomStr + '][' + dateNameType + ']">';
        select += '<option value="">请选择时间</option>';
        if (dateNameType == 'time_start') {
            select += '<option value="00:00">' + '00:00</option>';
        }
        for (var i = 1; i < 24; i++) {
            var date_prefix = i;
            if (i < 10) {
                date_prefix = '0' + i;
            }
            select += '<option value="' + date_prefix + ':00">' + date_prefix + ':00</option>';
        };
        if (dateNameType == 'time_end') {
            select += '<option value="23:00">' + '23:59</option>';
        }
        select += '</select>';
        return select;
    }
});
