var api = require('./_api.js');
var getImgCDN = 'http://img.wesai.com/';

$(function() {
    'use strict';
    var Request = new Object();
    Request = GetRequest();
    var VENUE_ID = Request['venue_id'];
    var TYPE = Request['type'];
    var DAY = Request['day'];
    var STATE = Request['state'];
    var ORDER_ID = Request['order_id'];
    //  获取url参数
    function GetRequest() {
        var url = location.search; //获取url中"?"符后的字串 
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            var strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
    //  最近一周时间列表
    function dateArray(today) {
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var week = date.getDay();
        var day = date.getDate();
        var weekAry = [];
        var paramete;
        var dayAry = [];
        var monthAry = [];
        var dateTime = [];
        var className = [];

        for (var i = 1; i < 8; i++) {
            paramete = week;
            if (week > 7) {
                week = (week - 7);
            }
            if (week == 1) { paramete = "周一" }
            if (week == 2) { paramete = "周二" }
            if (week == 3) { paramete = "周三" }
            if (week == 4) { paramete = "周四" }
            if (week == 5) { paramete = "周五" }
            if (week == 6) { paramete = "周六" }
            if (week == 7) { paramete = "周日" }
            if (i == 1) { paramete = "今天" }
            weekAry.push(paramete);
            week++;
        }

        function days(year, month) {
            var days;
            if (month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12) {
                days = 31;
            } else if (month == 4 || month == 6 || month == 9 || month == 11) {
                days = 30;
            } else if (month == 2 && year % 4 == 0) {
                days = 28;
            } else if (month == 2) {
                days = 29;
            } else {
                year += 1;
                month = 1;
                days(year, month);
            }
            return days;
        }
        for (var i = 1; i < 8; i++) {
            if (day > days(year, month)) {
                day = 1;
                month += 1;
            }
            dayAry.push(day);
            monthAry.push(month);
            day++;
        }
        for (var i = 0; i < 7; i++) {
            var time = year + '-' + monthAry[i] + '-' + dayAry[i];
            dateTime.push(time);
            if (time == today) {
                className.push('active');
            }
            className.push('');
        }

        if (today === undefined || today === 'undefined') {
            className[0] = 'active';
        }
        var theDate = { date: [] };
        for (var i = 0; i < weekAry.length; i++) {
            var content = { dateTime: dateTime[i], className: className[i], "week": weekAry[i], "month": monthAry[i], "day": dayAry[i] }
            theDate.date.push(content);
        }
        return theDate;
    }

    //  error 取消加载并提升错误
    function errorTip(info) {
        $.hidePreloader();
        $.alert(info);
    }

    //  获取运动类型
    function getSportsType(tar_id) {
        var arr = {
            1: { tag_id: 1, chsName: "羽毛球", enName: "badminton" },
            2: { tag_id: 2, chsName: "足球", enName: "football" },
            3: { tag_id: 3, chsName: "篮球", enName: "basketball" },
            4: { tag_id: 4, chsName: "网球", enName: "tennis" },
            5: { tag_id: 5, chsName: "游泳", enName: "swim" },
            6: { tag_id: 6, chsName: "乒乓球", enName: "pingpong" },
            7: { tag_id: 7, chsName: "跆拳道", enName: "taekwondo" }
        }
        return arr[tar_id]
    }
    //  添加运动类型
    function addSportType(data) {
        if (data.data) {
            for (var i = 0; i < data.data.length; i++) {
                data.data[i].enName = getSportsType(data.data[i].tag_id).enName;
            }
            data.data[0].className = 'active';
            return data;
        }
        if (data.result) {
            for (var i = 0; i < data.result.types.length; i++) {
                data.result.types[i].chsName = getSportsType(data.result.types[i].tag_id).chsName;
            }
            data.result.types[0].className = 'active';
            data.result.imgCDN = getImgCDN;
            return data;
        }
    }

    //  首页  
    $(document).on("pageInit", "#page_index", function(e, id, page) {
        var loadPage = 1;
        var ifLoad = 0;
        //  页面头部信息
        loadHead();

        function loadHead() {
            if (!ifLoad) {
                $.showPreloader();
            }
            //  设置页面标题
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "首页场馆";
            $("#main_nav .main-nav-venue").addClass('active');
            //  加载运动项目
            getListSports();
            //  加载地区与搜索
            getIndexArea();
            //  加载下拉刷新
            pullToRefresh();
        }

        //  地区与搜索 
        function getIndexArea() {
            var params = {
            }
            api.getUnused(params).done(function(data) {
                if (!data.error) {
                    //  判断是否有未使用订单
                    if (data.result>0) {
                        data.unused = data.result;
                        $("#index_header").addClass('balloon-head');
                    }
                    var pageIndexHeader = require('./handlebars/index/index-header.handlebars');
                    var pageIndexHeader = pageIndexHeader(data);
                    $('#index_balloon').html(pageIndexHeader);
                }
            })
        }

        // 运动项目
        function getListSports() {
            var params = {
                page: 1,
                size: 20
            }
            api.getVenueType(params).done(function(data) {
                if (!data.error) {
                    if (data.data.length < 1) {
                        $.hidePreloader();
                        return;
                    }
                    // 判断运动类型
                    data = addSportType(data);
                    var pageIndexSports = require('./handlebars/index/index-sport.handlebars');
                    pageIndexSports = pageIndexSports(data);
                    $('#type_filter').html(pageIndexSports);
                    //  加载日期
                    getListDate();
                    //  加载场馆列表
                    getListVenues(data.data[0].tag_id);
                } else {
                    errorTip(data.info);
                }
            })
        }
        //  日期
        function getListDate() {
            var pageIndexDate = require('./handlebars/index/index-date.handlebars');
            var data = dateArray();
            for (var i = 0; i < data.date.length; i++) {
                if (data.date[i].week == "周六" || data.date[i].week == "周日") {
                    data.date[i].weeksday = "weeksday";
                }
            }
            var pageIndexDate = pageIndexDate(data);
            $('#area_time').html(pageIndexDate);
        }

        //  场馆列表
        function getListVenues(tag_id, page, name, html) {

            var oldHtml = html ? html : '';
            var click_tag_id = $('.category.active').attr('data-id');
            var name = name ? name : $('#search').val();
            var tag_id = tag_id ? tag_id : click_tag_id;
            var page = page || 1;
            var params = {
                name: name,
                functions: tag_id,
                page: page,
                state:3,
                size: 10
            }
            api.getVenueList(params).done(function(data) {
                if (!data.error) {
                    var dataTotal = data.total;
                    var dataPage = data.page;
                    var currentPage = Math.ceil(dataTotal / data.size);
                    var pageIndexVenues = require('./handlebars/index/index-list.handlebars');
                    pageIndexVenues = pageIndexVenues(data);
                    $('#area_list').html(oldHtml + pageIndexVenues);
                    //  取消加载提示
                    $.hidePreloader();
                    if (currentPage <= dataPage) {
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        $('.infinite-scroll-preloader').addClass("detach-infinite");
                        $('.infinite-scroll-preloader').html("没有更多信息了");
                        if ($('#area_list li').length > 0 && $('#area_list li').length < params.size) {
                            $('.infinite-scroll-preloader').html("");
                        }
                        return;
                    } else {
                        if ($('.infinite-scroll-preloader').hasClass("detach-infinite")) {
                            $.attachInfiniteScroll($('.infinite-scroll'));
                            $('.infinite-scroll-preloader').html('<div class="preloader"></div>');
                            $('.infinite-scroll-preloader').removeClass("detach-infinite");
                        }
                        if (!ifLoad) {
                            //  加载无限滚动
                            infiniteLoading(params.size, dataTotal);
                            ifLoad = 1;
                        }
                    }
                } else {
                    errorTip(data.info);
                }
            })
        }

        //  搜索
        $('#btn-submit-search').bind("submit", function(event) {
            event.preventDefault();
            var val = $(this).val();
            getListVenues(false, false, val);
        })

        //  选择运动类型
        $(document).on("click", "#type_filter .category", function(e) {
            $(this).parent().find('.category').removeClass('active');
            $(this).addClass('active');
            var type = $(this).data('type');
            loadPage = 1;
            getListVenues();
        });

        //  选择日期
        $(document).on("click", "#area_time .ub-f1", function(e) {
            $("#area_time .ub-f1").removeClass("active");
            $(this).addClass("active");
        });

        //  选择场馆
        $(document).on("click", "#area_list .venues-btn", function(e) {
            var venue_id = $(this).attr('data-venue-id');
            var type = $('.category.active').attr('data-id');
            var day = $('.ub-f1.active').attr('data-date');
            window.location.href = "/venue/select?venue_id=" + venue_id + "&type=" + type + "&day=" + day;
        });

        //  下拉刷新
        function pullToRefresh() {
            var $content = $(page).find(".content").on('refresh', function(e) {
                //  加载运动项目
                getListSports();
                setTimeout(function() {
                    //  加载完毕重置
                    getIndexArea();
                    $.pullToRefreshDone($content);
                }, 500);
            });
        }
        //  无限滚动
        function infiniteLoading(size, maxItems) {
            var loading = false;
            // 每次加载添加多少条目
            var itemsPerLoad = size;
            // 最多可加载的条目
            var maxItems = maxItems;
            //  页数
            var lastIndex = $('#area_list li').length;
            $(page).on('infinite', function() {
                // 如果正在加载，则退出
                if (loading) return;
                // 设置flag
                loading = true;
                // 模拟1s的加载过程
                loadPage++;
                setTimeout(function() {
                    if (lastIndex >= maxItems) {
                        // 加载完毕，则注销无限加载事件，以防不必要的加载
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        // 删除加载提示符
                        $('.infinite-scroll-preloader').remove();
                        return;
                    }
                    var html = $("#area_list").html();
                    getListVenues(false, loadPage, false, html);
                    // addItems(itemsPerLoad, lastIndex);
                    // 更新最后加载的序号
                    lastIndex = $('#area_list li').length;
                    $.refreshScroller();
                    // 重置加载flag
                    loading = false;
                }, 200);
            });
        }
    });

    //  场馆详情
    $(document).on("pageInit", "#page_venueDetails", function(e, id, page) {
        var venueIntro;
        var photosAry = [];
        var venueDetailsTitle = require('./handlebars/details/details-name.handlebars');
        var venueDetailsIntro = require('./handlebars/details/details-intro.handlebars');
        var venueDetailsDate = require('./handlebars/details/details-date.handlebars');

        //  页面头部信息
        loadHead();

        function loadHead() {
            //  设置页面标题
            $.showPreloader();
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "场馆详情";
            //  加载场馆信息
            loadDetailsName();
        }

        //  场馆信息
        function loadDetailsName() {
            var params = {
                venue_id: VENUE_ID
            }
            api.getVenueDetails(params).done(function(data) {
                if (!data.error) {
                    // data 数据预处理
                    data = addSportType(data);
                    //  加载日期
                    loadDetailDate();
                    //  加载场馆其他信息
                    loadDetailtIntro(data);
                } else {
                    errorTip(data.info)
                }
            })
        }

        //  场馆日期
        function loadDetailDate() {
            var weeks = dateArray();
            venueDetailsDate = venueDetailsDate(weeks);
            $('#details_date').html(venueDetailsDate);
        }

        //  场馆其他信息
        function loadDetailtIntro(detailsData) {
            var params = {
                venue_id: VENUE_ID
            }
            api.getVenueImgList(params).done(function(data) {
                if (!data.error) {
                    for (var i = 0; i < data.data.length; i++) {
                        data.data[i].imgCDN = getImgCDN;
                        var imgBox = getImgCDN + data.data[i].fileid;
                        photosAry.push(imgBox);
                    }
                    data.intro = detailsData.result.intro;
                    //  渲染场馆信息
                    if (data.data[0]) {
                        detailsData.result.imgSrc = data.data[0].fileid;
                    }
                    venueDetailsTitle = venueDetailsTitle(detailsData);
                    $('#details_name').html(venueDetailsTitle);
                    //  渲染场馆介绍
                    venueDetailsIntro = venueDetailsIntro(data);
                    $('#details_intro').html(venueDetailsIntro);
                    $(".notice-box").html(detailsData.result.intro);
                    $(".notice-box a").removeAttr('href');
                    //  加载图片浏览器
                    photoBrowserLoad();
                } else {
                    errorTip(data.info);
                }
            })
        }

        //  图片浏览器
        function photoBrowserLoad() {
            //  取消加载提示
            $.hidePreloader();
            var myPhotoBrowserStandalone = $.photoBrowser({
                photos: photosAry,
                theme: 'dark'
            });
            $(page).on('click', '.pb-standalone', function() {
                myPhotoBrowserStandalone.open();
            });

        }

        //  选择运动类型
        $(document).on("click", "#details_type .skip-type", function(e) {
            $("#details_type .skip-type").removeClass("active");
            $(this).addClass("active");
        });

        //  跳转预定页面
        $(document).on("click", "#details_date .skip-session", function(e) {
            var newType = $('.skip-type.active').attr('data-id');
            var newDAY = $(this).attr('data-date');
            window.location.href = "/venue/select?venue_id=" + VENUE_ID + "&type=" + newType + "&day=" + newDAY;
        });
    });

    //  预定页
    $(document).on("pageInit", "#page_selectEvent", function(e, id, page) {
        var have = 'event-ticket-have';
        var none = 'event-ticket-none';
        var over = 'event-ticket-over';
        var select = 'event-ticket-select';

        //  页面头部信息
        loadHead()

        function loadHead() {
            //  打开加载提示
            $.showPreloader();
            //  设置页面标题
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "选择场次";
            //  加载日期
            getSelectEventDate(DAY);
        }

        //  日期
        function getSelectEventDate(day) {
            var day = day ? day : undefined;
            var pageSelectEventDate = require('./handlebars/session/session-date.handlebars');
            var data = dateArray(day);
            var pageSelectEventDate = pageSelectEventDate(data);
            $('#selectEvent_week').html(pageSelectEventDate);
            //  加载场地场次
            getSelectEvent(day);
        }

        //  场地场次
        function getSelectEvent(day) {
            var newDay = day ? day : dateArray().date[0].dateTime;
            var params = {
                venue_id: VENUE_ID,
                type: TYPE,
                day: newDay
            }
            api.getVenueEvent(params).done(function(data) {
                if (!data.error) {
                    //  场地场次时间
                    var openTime = when(data.data);
                    //  场地场次数据修改
                    var venue = placeData(data, openTime);
                    var isHave = 0;
                    venue.openTime = openTime;
                    //  判断场地场次是否为空
                    data.data.forEach(function(element, index) {
                        if (element.times.length > 0) {
                            isHave = 1;
                        }
                    });
                    if (!isHave) {
                        venue = {
                            none: "暂无信息"
                        };
                    }
                    var pageSelectEvent = require('./handlebars/session/session-content.handlebars');
                    pageSelectEvent = pageSelectEvent(venue);
                    $('#selectEvent_content').html(pageSelectEvent);
                    var len = $(".selectEvent-area-list").children('li').length;
                    if (len === 1) {
                        $(".selectEvent-area-list li").css('width', '100%');
                        $(".event-item-tit").css('width', '100%');
                    }
                    if (len === 2) {
                        $(".selectEvent-area-list li").css('width', '50%');
                        $(".event-item-tit").css('width', '50%');
                    }
                    if (len === 3) {
                        $(".selectEvent-area-list li").css('width', '33.3%');
                        $(".event-item-tit").css('width', '33.3%');
                    }
                    if (len === 4) {
                        $(".selectEvent-area-list li").css('width', '25%');
                        $(".event-item-tit").css('width', '25%');
                    }
                    //  加载场次区域滚动事件
                    scroll();
                    //  场地场次 加载购买
                    getSelectEventFooter();
                } else {
                    errorTip(data.info);
                }
            })
        }

        //  场地场次选择信息
        function getSelectEventFooter() {
            var pageSelectEventFooter = require('./handlebars/session/session-footer.handlebars');
            pageSelectEventFooter = pageSelectEventFooter();
            $('#selectEvent_footer').html(pageSelectEventFooter);
            //  取消加载提示
            $.hidePreloader();
        }

        //  日期（星期）选择
        $(document).on("click", "#selectEvent_week .ub-f1", function(e) {
            //  打开加载提示
            $.showPreloader();
            $(this).parent().find('.ub-f1').removeClass('active');
            $(this).addClass('active');
            $("#user_choice").empty();
            $(".event-footer-explain").css("display", "flex");
            $("#ticket_pay").html("请选择场次");
            var newDay = $(this).data('date');
            var newUrlState = "?venue_id=" + VENUE_ID + "&type=" + TYPE + "&day=" + newDay;
            window.history.replaceState(newUrlState, 'myOrderList', newUrlState);
            getSelectEvent(newDay);
        });

        //  场地开馆时间筛选
        function when(data) {
            var start = [];
            var end = [];
            for (var i = 0; i < data.length; i++) {
                var timeAry = data[i].times;
                for (var j = 0; j < timeAry.length; j++) {
                    start.push(parseInt(timeAry[j].time_start));
                    if (parseInt(timeAry[j].time_end) == 0 || timeAry[j].time_end == '23:59') {
                        end.push(24);
                    } else {
                        end.push(parseInt(timeAry[j].time_end));
                    }
                }
            }
            var minTime = Math.min.apply(1, start);
            var maxTime = Math.max.apply(1, end);
            var openTime = [];
            for (var i = minTime; i < maxTime + 1; i++) {
                var obj = {};
                if (i < 10) {
                    obj.time = '0' + i + ':00';
                    openTime.push(obj);
                } else {
                    obj.time = i + ':00';
                    openTime.push(obj);
                }
            }
            return openTime;
        }

        //  场地场次数据
        function placeData(data, openTime) {
            var list = {
                venue: []
            }
            for (var i = 0; i < data.data.length; i++) {
                var dataList = [];
                var venue = {
                    data: [],
                    name: data.data[i].name,
                    state: data.data[i].state,
                    type: data.data[i].type,
                    venue_area_res_id: data.data[i].venue_area_res_id
                }
                list.venue.push(venue);
                for (var j = 0; j < openTime.length; j++) {
                    var content = {};
                    var isHave = 0;
                    var text = '';
                    for (var t = 0; t < data.data[i].times.length; t++) {
                        (function(_i, _j, _t, _openTime) {
                            if (parseInt(data.data[_i].times[_t].time_start) == parseInt(_openTime[_j].time)) {
                                isHave = 1;
                                text = data.data[_i].times[_t];
                            }
                        })(i, j, t, openTime)
                    }
                    (function(_i, _j, _t, _is) {
                        if (venue.state == 4 &&text.state) {
                            if (_is && text.is_expired == 0 && text.state == 2 ) {
                                content = {
                                    className: have,
                                    name: text.name,
                                    state: text.state,
                                    venue_area_res_id: text.venue_area_res_id,
                                    venue_area_res_times_id: text.venue_area_res_times_id,
                                    day: text.day,
                                    price: text.price,
                                    gid: text.gid,
                                    start: openTime[_j].time,
                                    end: openTime[_j + 1].time
                                }

                            } else {
                                content.className = over;
                            }
                        } else {
                            content.className = none;
                        }
                        venue.data.push(content);
                    })(i, j, t, isHave);
                }
            }
            return list;
        }

        //  场次区域滚动事件
        function scroll() {
            $(".scroll-con").scroller({
                type: 'js',
                probeType: 3,
                userTransform: 1
            });
            $(document).on('scroll', '.scroll-con', function() {
                var _left = $('.scroll-con .content-inner').css('left');
                var _top = $('.scroll-con .content-inner').css('top');
                $('.event-container-portrait ul').css({
                    top: _top
                })
                $('#lateral ul').css({
                    left: _left
                })
            });
        }

        // 场地场次选择
        $(document).on("click", "#selectEvent_content .scroll-box .event-ticket-have", function(e) {
            var userSelect = "#selectEvent_content .event-ticket-select";
            var sel_name = '';
            var sel_start = '';
            var sel_end = '';
            var sel_price = 0;
            var sel_gid = '';
            if ($(userSelect).length < 4) {
                $(this).toggleClass(select);
                addTicket();
            } else {
                if ($(this).hasClass(select)) {
                    $(this).toggleClass(select);
                    addTicket();
                } else {
                    $.toast("最多选择4场");
                }
            }
            //  所选场次展示
            function addTicket() {
                $("#user_choice").empty();
                for (var i = 0; i < $(".event-item").length; i++) {
                    if ($(".event-item").eq(i).hasClass(select)) {
                        sel_name = $(".event-item").eq(i).parent('li').data('name');
                        sel_gid = $(".event-item").eq(i).data('gid');
                        var txtPrice = $(".event-item").eq(i).html().substring(1);
                        sel_price += parseFloat(txtPrice);
                        sel_start = $(".event-item").eq(i).data('start');
                        sel_end = $(".event-item").eq(i).data('end');
                        addUseChoice(sel_start, sel_end, sel_name, sel_price);
                    }
                }
                if ($(userSelect).length == 0) {
                    $(".event-footer-explain").css("display", "flex");
                    $("#ticket_pay").html("请选择场次");
                }
            }
        });
        //  添加用户选择场次  
        function addUseChoice(timeStart, timeEnd, name, price) {
            price = price.toFixed(2);
            var eventItem = "#selectEvent_container .event-item";
            var div = document.getElementById('user_choice');
            var div1 = document.createElement('div');
            var div2 = document.createElement('div');
            var div3 = document.createElement('div');
            div1.className = 'order-ticket ub-f1 event-states';
            div2.className = 'choice-time';
            div3.className = 'font-tit';
            $(".event-footer-explain").hide();
            div2.innerHTML = timeStart + '-' + timeEnd;
            div3.innerHTML = name;
            $(div1).append(div2).append(div3);
            $(div).append(div1);
            $("#ticket_pay").html("<span class='order-amount'>" + price + "</span>" + "元提交订单");
        }
        //  判断用户是否有手机号
        $(document).on('click', '#ticket_pay', function() {
            api.getUserTel().done(function(data) {
                    if (!data.error && $("#selectEvent_content .event-ticket-select").length > 0) {
                        if (!data.mobile == '') {
                            $(".event-layer").addClass("active");
                            $('.user-tel-default').val(data.mobile);
                            $.popup('.popup-order-default');
                        } else {
                            $(".event-layer").addClass("active");
                            $.popup('.popup-order');
                        }
                    } else {
                        $.toast('请选择场次', 1000);
                    }
                })
                // 用户更换手机
            $(document).on('click', '.button-change', function() {
                $.popup('.popup-order');
                $.closeModal('.popup-order-default');
            });
            //  点击其它区域取消验证
            $(document).on('click', '.event-layer', function() {
                $(".event-order-default").show();
                $(".event-layer").removeClass("active");
            });
        });

        //  获取手机验证码
        $(document).on('click', '#selectEvent_get_code', function() {
            var reg = /^1[34578]\d{9}$/;
            var tel = $('.user-tel').val();
            if (!$(this).hasClass('code-verifie')) {
                if (reg.test(tel)) {
                    codeAjax();
                } else {
                    $.toast('请输入正确的手机号码', 1000);
                    return false;
                }
            }
            // 服务短信发送
            function codeAjax() {
                var params = {
                    mobile: tel
                }
                api.sendTelCode(params).done(function(data) {
                    sendCode(60000);
                    $.toast('已发送', 1000);
                })
            }
            //  短信验证时间间隔
            function sendCode(waitTime) {
                $("#selectEvent_get_code").addClass('code-verifie').html('已发送');
                var waitTime = waitTime || 10000;
                var intervalTime = (waitTime / 1000) - 1;
                var countDown = setInterval(count, 1000);
                setTimeout(overTime, waitTime);

                function count() {
                    $("#selectEvent_get_code").html(intervalTime + 's');
                    intervalTime -= 1;
                }

                function overTime() {
                    clearInterval(countDown);
                    $("#selectEvent_get_code").removeClass('code-verifie').html('获取验证码');
                }
            }
        });

        //  跳转支付页面
        $(document).on('click', '.mobile-vcode', function() {
            var tel = $('.user-tel').val();
            var telVcode = $('.user-tel-vcode').val();
            var params = {
                mobile: tel,
                vcode: telVcode
            }
            if (tel.length < 1) {
                $.toast('请输入手机号', 1000);
                return;
            } else if (telVcode.length < 1) {
                $.toast('请输入验证码', 1000);
            } else {
                api.getTelCode(params).done(function(data) {
                    if (!data.error) {
                        addOrder(tel);
                    } else {
                        $.toast(data.info);
                    }
                })
            }
        });

        //  跳转订单页
        $(document).on("click", ".user-order-skip", function(e, id, page) {
            var tel = $('.user-tel-default').val();
            addOrder(tel);
        });

        //  添加订单
        function addOrder(tel) {
            var times_id_ary = [];
            for (var i = 0; i < $(".event-item.event-ticket-select").length; i++) {
                var timesId = $(".event-item.event-ticket-select").eq(i).data('timesId');
                times_id_ary.push(timesId);
            }
            var times_id_str = times_id_ary.join(",")
            var amount = $(".order-amount").html() * 100;
            var params = {
                venue_id: VENUE_ID,
                venue_area_res_times_id: times_id_str,
                amount: amount,
                mobile: tel
            }
            api.addOrder(params).done(function(data) {
                if (!data.error) {
                    window.location.href = data.pay_url;
                } else {
                    if (data.error == '-409') {
                        $.confirm(data.info,
                            function() {
                                window.location.href = '/order/my?state=1';
                            },
                            function() {
                            }
                        );
                    } else {
                        errorTip(data.info);
                    }
                }
            })
        }

    });

    //  用户页     USER
    $(document).on("pageInit", "#page_user", function(e, id, page) {
        //  页面头部信息
        loadHead();

        function loadHead() {
            //  打开加载提示
            $.showPreloader();
            //  设置页面标题
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "我的";
            $("#main_nav .main-nav-mine").addClass('active');
            //  加载用户信息
            loadUser();
        }

        //  用户信息
        function loadUser() {
            var params = {
                    state: 1,
                    page: 1,
                    size: 1
                }
                //  获取待付款订单
            api.getOrderMyList(params).done(function(data) {
                if (!data.error) {
                    var pageUser = require("./handlebars/user/user-index.handlebars")
                    pageUser = pageUser(data);
                    $("#page_user").html(pageUser);
                    //  取消加载提示
                    $.hidePreloader();
                } else {
                    errorTip(data.info);
                }
            })
        }
    })

    //  订单页
    $(document).on("pageInit", "#page_order_my", function(e, id, page) {
        var pay = 'order-state-pay';
        var used = 'order-state-used';
        var unused = 'order-state-unused';
        var loadPage = 1;
        var ifLoad = 0;
        var order_nav_unused = 3;
        var order_nav_used = 4;
        var order_nav_pay = 1;
        var order_nav_all = '';
        var order_list_pay = 1;
        var order_list_closed = 2;
        var order_list_unused = 3;
        var order_list_used = 4;

        //  页面头部信息
        loadHead()
        function loadHead() {
            if (!ifLoad) {
                //  打开加载提示
                $.showPreloader();
            }
            //  设置页面标题
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "我的订单";
            //  加载导航
            loadOrderNavbar();
            //  加载订单列表
            loadOrderMy(STATE);
        }

        //  订单列表
        function loadOrderMy(state, page, html) {
            var oldHtml = html ? html : '';
            var state = state ? state : '';
            var page = page || 1;
            var params = {
                state: state,
                page: page,
                size: 10
            }
            api.getOrderMyList(params).done(function(data) {
                if (!data.error) {
                    var dataTotal = data.total;
                    var dataPage = data.page;
                    var currentPage = Math.ceil(dataTotal / data.size);
                    //  自定义数据格式
                    data = myOrderListData(data);
                    var pageOredrMy = require("./handlebars/order/order-content.handlebars")
                    pageOredrMy = pageOredrMy(data);
                    $("#order_content").html(oldHtml + pageOredrMy);
                    //  取消加载提示
                    $.hidePreloader();
                    if (currentPage <= dataPage) {
                        //  加载完毕，则注销无限加载事件，以防不必要的加载
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        //  删除加载提示符
                        $('.infinite-scroll-preloader').html("没有更多信息了");
                        if ($('#order_content ul').length > 0 && $('#order_content ul').length < params.size) {
                            $('.infinite-scroll-preloader').html("");
                        }
                        $('.infinite-scroll-preloader').addClass("detach-infinite");
                        return;
                    } else {
                        if ($('.infinite-scroll-preloader').hasClass("detach-infinite")) {
                            //  监听滚动无限加载事件
                            $.attachInfiniteScroll($('.infinite-scroll'))
                                //  增加加载提示符
                            $('.infinite-scroll-preloader').html('<div class="preloader"></div>');
                            $('.infinite-scroll-preloader').removeClass("detach-infinite");
                        }
                        if (!ifLoad) {
                            //      加载无限滚动
                            infiniteLoading(params.size, dataTotal)
                            ifLoad = 1;
                        }
                    }
                } else {
                    errorTip(data.info);
                }
            })
        }
        //无限滚动
        function infiniteLoading(size, maxItems) {
            var loading = false;
            // 每次加载添加多少条目
            var itemsPerLoad = size;
            // 最多可加载的条目
            var maxItems = maxItems;
            var lastIndex = $('#order_content ul').length;
            $(page).on('infinite', function() {
                // 如果正在加载，则退出
                if (loading) return;
                // 设置flag
                loading = true;
                loadPage++;
                setTimeout(function() {
                    var html = $("#order_content").html();
                    loadOrderMy(STATE, loadPage, html);
                    // 更新最后加载的序号
                    lastIndex = $('#order_content ul').length;
                    $.refreshScroller();
                    // 重置加载flag
                    loading = false;
                }, 200);
            });
        }

        //  自定义数据格式
        function myOrderListData(data) {
            var list = []
            for (var i = 0; i < data.data.length; i++) {
                var obj = {
                    amount: data.data[i].amount,
                    venue_name: data.data[i].venue_name,
                    order_id: data.data[i].order_id,
                    code: data.data[i].times[0].code,
                    day: data.data[i].times[0].day,
                    start: data.data[i].times[0].start.slice(0, 5),
                    name: data.data[i].times[0].name,
                    sub_tit_1: "开始时间",
                    sub_tit_2: "核销码",
                    sub_tit_3: "预约场地",
                    sub_tit_4: "金额"
                }
                if (false) {
                    obj.sub_tit_1 = "场馆票";
                    obj.sub_tit_3 = "数量";
                }
                if (data.data[i].times.length > 1) {
                    obj.code = '-';
                    obj.start = '';
                    obj.name = data.data[i].times.length + "个场地";
                }
                if (data.data[i].state == order_list_pay) {
                    obj.className = pay;
                    obj.states = "待付款";
                    obj.url = data.data[i].payurl;
                }
                if (data.data[i].state == order_list_closed) {
                    obj.code = '-';
                    obj.className = used;
                    obj.states = "支付失败";
                }
                if (data.data[i].state == order_list_unused) {
                    obj.className = unused;
                    obj.states = "未使用";
                }
                if (data.data[i].state == order_list_used) {
                    obj.className = used;
                    obj.states = "已使用";
                }
                list.push(obj)
            }
            return list;
        }

        //  订单列表导航
        function loadOrderNavbar() {
            var data = {
                0: {
                    className: '',
                    state: order_nav_unused,
                    name: '未使用'
                },
                1: {
                    className: '',
                    state: order_nav_pay,
                    name: '待付款'
                },
                2: {
                    className: '',
                    state: order_nav_used,
                    name: '已使用'
                },
                3: {
                    className: '',
                    state: order_nav_all,
                    name: '全部'
                }
            }
            if (STATE == 3) {
                data[0].className = 'active';
            }
            if (STATE == 1) {
                data[1].className = 'active';
            }
            if (STATE == 4) {
                data[2].className = 'active';
            }
            if (!STATE) {
                data[3].className = 'active';
            }
            var pageOredrNavbar = require("./handlebars/order/order-navbar.handlebars")
            pageOredrNavbar = pageOredrNavbar(data);
            $("#order_navbar").html(pageOredrNavbar)
        }

        //  导航点击事件
        $(document).on('click', '#order_navbar a', function(e) {
            $("#order_navbar a").removeClass('active');
            $(this).addClass('active');
            var nowState = $(this).data('state');
            var newUrlState = "?state=" + nowState;
            window.history.replaceState(nowState, 'myOrderList', newUrlState);
            loadPage = 1;
            window.location.href = "/order/my?state=" +nowState;
        })

        //  取消订单
        $(document).on('click', '.button-cancel', function() {
            var orderId = $(this).data('pkOrder');
            $.confirm('确定要取消订单吗？', function() {
                orderCancel(orderId);
            });
        });

        //  成功取消订单
        function orderCancel(orderId) {
            var params = {
                order_id: orderId
            }
            api.orderClosed(params).done(function(data) {
                if (!data.error) {
                    window.location.href = "/order/my?state=";
                }
            })
        }
    })

    //  订单详情页
    $(document).on("pageInit", "#page_order_detail", function(e, id, page) {
        //  设置头部标题
        loadHead()
        function loadHead() {
            //  打开加载提示
            $.showPreloader();
            //  设置页面标题
            var htmlTitle = document.getElementsByTagName('title')[0];
            htmlTitle.innerHTML = "订单详情";
            //  加载订单详情
            loadOrderDetail();
        }
        //  订单详情 
        function loadOrderDetail() {
            var params = {
                order_id: ORDER_ID
            }
            api.getOrderInfo(params).done(function(data) {
                if (!data.error) {
                    //  自定义数据格式 -
                    data = myOrderDetailData(data);
                    var pageOredrList = require("./handlebars/order/order-detail.handlebars")
                    pageOredrList = pageOredrList(data);
                    $("#page_order_detail").html(pageOredrList);
                    //  取消加载提示
                    $.hidePreloader();
                } else {
                    errorTip(data.info);
                }
            })
        }

        //  自定义数据格式
        function myOrderDetailData(data) {
            for (var i = 0; i < data.result.times.length; i++) {
                data.result.times[i].time_start = (data.result.times[i].time_start).slice(0, 5);
                data.result.times[i].time_end = (data.result.times[i].time_end).slice(0, 5);
                data.result.times[i].week = weekDay(data.result.times[i].day);
                var code = data.result.times[i].code.toString();
                if (data.result.times[i].code) {
                    data.result.times[i].code = code.slice(0, 4) + ' ' + code.slice(4, 8) + ' ' + code.slice(8, 12)
                }
                if (data.result.times[i].state == 5) {
                    data.result.times[i].states = "已核销";
                }                
                if (data.result.times[i].state == 6) {
                    data.result.times[i].states = "已过期";
                }
            }

            if (data.result.type == '1') {
                data.result.types = '羽毛球';
            }
            if (data.result.type == "2") {
                data.result.types = '足球';
            }
            if (data.result.type == "3") {
                data.result.types = '篮球';
            }
            if (data.result.type == "4") {
                data.result.types = '网球';
            }
            if (data.result.type == "5") {
                data.result.types = '游泳';
            }
            if (data.result.type == "6") {
                data.result.types = '乒乓球';
            }
            if (data.result.type == "7") {
                data.result.types = '跆拳道';
            }
            if (data.result.wx_order) {
                data.result.payment = "微信";
            }
            if (data.result.state == '1') {
                data.result.states = "待付款";
                data.result.className = "order-state-pay";
                data.result.url = data.result.payurl;
            }
            if (data.result.state == '2') {
                data.result.className = "order-state-used";
                data.result.states = "支付失败";
            }
            if (data.result.state == '3') {
                data.result.className = "order-state-unused";
                data.result.states = "未使用";
            }
            if (data.result.state == '4') {
                data.result.className = "order-state-used";
                data.result.states = "已使用";
            }
            return data;
        }
        //  支付方式
        function payMethod(data){
            var arr = {
                1:'微信支付',
                2:'微信支付',
                3:'支付宝',
                4:'支付宝',
                5:'微信支付'
            }
            return arr[data];
        }
        //  日期转换星期
        function weekDay(week) {
            var data = new Date(week);
            var week = data.getDay();
            var paramete = '';
            if (week == 1) { paramete = "周一" }
            if (week == 2) { paramete = "周二" }
            if (week == 3) { paramete = "周三" }
            if (week == 4) { paramete = "周四" }
            if (week == 5) { paramete = "周五" }
            if (week == 6) { paramete = "周六" }
            if (week == 0) { paramete = "周日" }
            return paramete;
        }
    })

    //选择颜色主题
    $(document).on("click", ".select-color", function(e) {
        var b = $(e.target);
        document.body.className = "theme-" + (b.data("color") || "");
        b.parent().find(".active").removeClass("active");
        b.addClass("active");
    });


    $.init();
});
