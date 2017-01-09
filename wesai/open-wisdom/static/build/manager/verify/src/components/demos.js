$(function() {
    'use strict';

    var pageSize = 10; //每页条数
    var pageNum = 1; //页数
    var loading = false; //状态
    var refreshStatus = false; //刷新状态
    var listType = 1; //类型
    var states = 1;
    var pagetotal;


    //默认加载当天
    addItems(pageNum, listType, pageSize, states);

    //切换日期
    $(document).on('click', '.external', function() {
        $('.infinite-scroll .list-block').html('');
        $.showPreloader();
        pageNum = 1; //页数
        states = 1;
        var _that = $(this);
        listType = _that.attr('data-type');
        _that.addClass('active').siblings().removeClass('active');
        addItems(pageNum, listType, pageSize, states);
    })

    function addItems(page, listType, pageSize, states) {
        $.ajax({
            type: "GET",
            url: "/contest/verify/ajax_list_verifying_items",
            data: {
                page: page,
                listType: listType,
                pageSize: pageSize
            },
            dataType: "json",
            success: function(data) {
                if (data.error === 0) {
                    pageSize = data.size; //每页条数
                    pageNum = data.page; //页数
                    // alert(pageNum+'asdasd'+pageSize);
                    pagetotal = data.total;
                    var data = data.data;
                    if (!data) {
                        // 加载完毕，则注销无限加载事件，以防不必要的加载
                        $.detachInfiniteScroll($('.infinite-scroll'));
                        // 删除加载提示符
                        $('.infinite-scroll-preloader').hide();
                        loading = true;
                        $.hidePreloader();

                        return;
                    }
                    var html = '';
                    for (var i in data) {
                        html += '<ul class="js_count">'
                        html += '<li class="item-content">'
                        html += '<div class="item-inner">'
                        html += '<div class="item-title">活动名称</div>'
                        html += '<div class="item-after">' + data[i].cname + '</div>'
                        html += '</div>'
                        html += '</li>'
                        html += '<li class="item-content">'
                        html += '<div class="item-inner">'
                        html += '<div class="item-title">项目名称</div>'
                        html += '<div class="item-after">' + data[i].name + '</div>'
                        html += '</div>'
                        html += '</li>'
                        html += '<li class="item-content">'
                        html += '<div class="item-inner">'
                        html += '<div class="item-title">开赛时间</div>'
                        html += '<div class="item-after">' + data[i].start_time + '</div>'
                        html += '</div>'
                        html += '</li>'
                        html += '<li class="item-content">'
                        html += '<div class="item-inner">'
                        html += '<div class="item-title">报名人数</div>'
                        html += '<div class="item-after">' + data[i].order_count + '</div>'
                        html += '</div>'
                        html += '</li>'
                        html += '<li class="item-content">'
                        html += '<div class="item-inner">'
                        html += '<div class="item-title">检票人数</div>'
                        html += '<div class="item-after">' + data[i].order_count_verified + '</div>'
                        html += '</div>'
                        html += '</li>'
                        html += '</ul>'
                        html += '<div class="content-block">'
                        html += '<a href="javascript:;" class="button button-big button-fill button-success start-scan" data-item-id="' + data[i].pk_contest_items + '">开始检票</a>'
                        html += '</div>'
                    }
                    if (states === 1) {
                        $('.infinite-scroll .list-block').html(html);
                    } else if (states === 2) {
                        $('.infinite-scroll .list-block').append(html);
                    }
                    $.hidePreloader();
                    loading = false;

                }
            }
        });
    }
    $(document).on("pageInit", "#page-infinite-scroll-bottom", function(e, id, page) {
        //下拉刷新页面
        var $content = $(page).find(".content").on('refresh', function(e) {
            var pageNum = 1; //页数
            if (refreshStatus) {
                return;
            }
            refreshStatus = true;
            addItems(pageNum, listType, pageSize, states);
            setTimeout(function() {
                // 加载完毕需要重置
                $.pullToRefreshDone($content);
                $.attachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').show();
                refreshStatus = false;
            }, 200);

        });
        //上拉加载更多
        $(page).on('infinite', function() {
            var states = 2;
            // 如果正在加载，则退出   
            if (loading) {
                return;
            }
            // 设置flag
            // 模拟1s的加载过程
            loading = true;
            pageNum++;
            setTimeout(function() {
                addItems(pageNum, listType, pageSize, states);
                $.refreshScroller();

            }, 1000);
        });

    });



    $(document).on('click', '.start-scan', function() {
        if ($(this).attr('data-item-id')) {
            startScan($(this).attr('data-item-id'));
            
        }
    });
    function appendVerifyResult(verifyResult) {
        
        $('.popup-about').html(verifyResult);
        $.popup('.popup-about');
    }
    function doVerify(orderId,itemId) {
        $.post("/contest/verify/ajax_do_verify", 'orderId=' + orderId + '&itemId=' + itemId, function(res) {
            if (res.error == 0) {
                appendVerifyResult(res.html,itemId);
            } else {
                alert(res.msg);
            }
        }, 'json');
    }

    function checkQRCodeData(data,itemId) {
        $.post("/contest/verify/ajax_check_qrcode_data", 'data=' + data, function(res) {
            if (res.error == 0) {
                doVerify(res.orderId,itemId);
            } else {
                alert(res.msg);
            }
        }, 'json');
    }
    function startScan(itemId) {
        wx.scanQRCode({
            desc: '扫描中...',
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function(res) {
                if (res.errMsg == 'scanQRCode:ok') {
                    res.resultStr = 'MTQ3MTMzNjg1MXwxM3w2ZjZkNjM1NDc5MjMwMmVjY2M2OWJhMjZhNzNlZGUxMQ==';
                    checkQRCodeData(res.resultStr,itemId);
                    
                }
            },
            error: function(res) {
                if (res.errMsg.indexOf('function_not_exist') > 0) {
                    alert('版本过低请升级')
                }
            }
        });
    }






    //test demo js

    //多个标签页下的无限滚动
    $(document).on("pageInit", "#page-fixed-tab-infinite-scroll", function(e, id, page) {
        var loading = false;
        // 每次加载添加多少条目
        var itemsPerLoad = 20;
        // 最多可加载的条目
        var maxItems = 100;
        var lastIndex = $('.list-container li')[0].length;

        function addItems(number, lastIndex) {
            // 生成新条目的HTML
            var html = '';
            for (var i = lastIndex + 1; i <= lastIndex + number; i++) {
                html += '<li class="item-content""><div class="item-inner"><div class="item-title">新条目</div></div></li>';
            }
            // 添加新条目
            $('.infinite-scroll.active .list-container').append(html);
        }
        $(page).on('infinite', function() {
            // 如果正在加载，则退出
            if (loading) return;
            // 设置flag
            loading = true;
            var tabIndex = 0;
            if ($(this).find('.infinite-scroll.active').attr('id') == "tab2") {
                tabIndex = 0;
            }
            if ($(this).find('.infinite-scroll.active').attr('id') == "tab3") {
                tabIndex = 1;
            }
            lastIndex = $('.list-container').eq(tabIndex).find('li').length;
            // 模拟1s的加载过程
            setTimeout(function() {
                // 重置加载flag
                loading = false;
                if (lastIndex >= maxItems) {
                    // 加载完毕，则注销无限加载事件，以防不必要的加载
                    //$.detachInfiniteScroll($('.infinite-scroll').eq(tabIndex));
                    // 删除加载提示符
                    $('.infinite-scroll-preloader').eq(tabIndex).hide();
                    return;
                }
                addItems(itemsPerLoad, lastIndex);
                // 更新最后加载的序号
                lastIndex = $('.list-container').eq(tabIndex).find('li').length;
                $.refreshScroller();
            }, 1000);
        });
    });

    //图片浏览器
    $(document).on("pageInit", "#page-photo-browser", function(e, id, page) {
        var myPhotoBrowserStandalone = $.photoBrowser({
            photos: [
                '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
                '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
                '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
            ]
        });
        //点击时打开图片浏览器
        $(page).on('click', '.pb-standalone', function() {
            myPhotoBrowserStandalone.open();
        });
        /*=== Popup ===*/
        var myPhotoBrowserPopup = $.photoBrowser({
            photos: [
                '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
                '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
                '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
            ],
            type: 'popup'
        });
        $(page).on('click', '.pb-popup', function() {
            myPhotoBrowserPopup.open();
        });
        /*=== 有标题 ===*/
        var myPhotoBrowserCaptions = $.photoBrowser({
            photos: [{
                    url: '//img.alicdn.com/tps/i3/TB1kt4wHVXXXXb_XVXX0HY8HXXX-1024-1024.jpeg',
                    caption: 'Caption 1 Text'
                }, {
                    url: '//img.alicdn.com/tps/i1/TB1SKhUHVXXXXb7XXXX0HY8HXXX-1024-1024.jpeg',
                    caption: 'Second Caption Text'
                },
                // 这个没有标题
                {
                    url: '//img.alicdn.com/tps/i4/TB1AdxNHVXXXXasXpXX0HY8HXXX-1024-1024.jpeg',
                },
            ],
            theme: 'dark',
            type: 'standalone'
        });
        $(page).on('click', '.pb-standalone-captions', function() {
            myPhotoBrowserCaptions.open();
        });
    });


    //对话框
    $(document).on("pageInit", "#page-modal", function(e, id, page) {
        var $content = $(page).find('.content');
        $content.on('click', '.alert-text', function() {
            $.alert('这是一段提示消息');
        });

        $content.on('click', '.alert-text-title', function() {
            $.alert('这是一段提示消息', '这是自定义的标题!');
        });

        $content.on('click', '.alert-text-title-callback', function() {
            $.alert('这是自定义的文案', '这是自定义的标题!', function() {
                $.alert('你点击了确定按钮!')
            });
        });
        $content.on('click', '.confirm-ok', function() {
            $.confirm('你确定吗?', function() {
                $.alert('你点击了确定按钮!');
            });
        });
        $content.on('click', '.prompt-ok', function() {
            $.prompt('你叫什么问题?', function(value) {
                $.alert('你输入的名字是"' + value + '"');
            });
        });
    });

    //操作表
    $(document).on("pageInit", "#page-action", function(e, id, page) {
        $(page).on('click', '.create-actions', function() {
            var buttons1 = [{
                text: '请选择',
                label: true
            }, {
                text: '卖出',
                bold: true,
                color: 'danger',
                onClick: function() {
                    $.alert("你选择了“卖出“");
                }
            }, {
                text: '买入',
                onClick: function() {
                    $.alert("你选择了“买入“");
                }
            }];
            var buttons2 = [{
                text: '取消',
                bg: 'danger'
            }];
            var groups = [buttons1, buttons2];
            $.actions(groups);
        });
    });

    //加载提示符
    $(document).on("pageInit", "#page-preloader", function(e, id, page) {
        $(page).on('click', '.open-preloader-title', function() {
            $.showPreloader('加载中...')
            setTimeout(function() {
                $.hidePreloader();
            }, 2000);
        });
        $(page).on('click', '.open-indicator', function() {
            $.showIndicator();
            setTimeout(function() {
                $.hideIndicator();
            }, 2000);
        });
    });


    //选择颜色主题
    $(document).on("click", ".select-color", function(e) {
        var b = $(e.target);
        document.body.className = "theme-" + (b.data("color") || "");
        b.parent().find(".active").removeClass("active");
        b.addClass("active");
    });

    //picker
    $(document).on("pageInit", "#page-picker", function(e, id, page) {
        $("#picker").picker({
            toolbarTemplate: '<header class="bar bar-nav">\
        <button class="button button-link pull-left">\
      按钮\
      </button>\
      <button class="button button-link pull-right close-picker">\
      确定\
      </button>\
      <h1 class="title">标题</h1>\
      </header>',
            cols: [{
                textAlign: 'center',
                values: ['iPhone 4', 'iPhone 4S', 'iPhone 5', 'iPhone 5S', 'iPhone 6', 'iPhone 6 Plus', 'iPad 2', 'iPad Retina', 'iPad Air', 'iPad mini', 'iPad mini 2', 'iPad mini 3'],
                cssClass: 'picker-items-col-normal'
            }]
        });
        $("#picker-name").picker({
            toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">确定</button>\
      <h1 class="title">请选择称呼</h1>\
      </header>',
            cols: [{
                textAlign: 'center',
                values: ['赵', '钱', '孙', '李', '周', '吴', '郑', '王']
            }, {
                textAlign: 'center',
                values: ['杰伦', '磊', '明', '小鹏', '燕姿', '菲菲', 'Baby']
            }, {
                textAlign: 'center',
                values: ['先生', '小姐']
            }]
        });
    });
    $(document).on("pageInit", "#page-datetime-picker", function(e) {
        $("#datetime-picker").datetimePicker({
            toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">确定</button>\
      <h1 class="title">选择日期和时间</h1>\
      </header>'
        });
    });

    $(document).on("pageInit", "#page-city-picker", function(e) {
        $("#city-picker").cityPicker({
            value: ['天津', '河东区']
                //value: ['四川', '内江', '东兴区']
        });
    });

    $.init();
});