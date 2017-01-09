require('./config.js');
require('../../SUI-Mobile/dist/css/sm.min.css');
require('../../SUI-Mobile/dist/css/sm-extend.min.css');
require('../../css/common.css');

var venueList = require('./venueList.handlebars');
var api = require('./_api.js');

require('../../SUI-Mobile/dist/js/sm.min.js');
require('../../SUI-Mobile/dist/js/sm-extend.min.js');

$(function() {
    'use strict';

    $(document).on("pageInit", "#page-infinite-scroll-bottom", function(e, id, page) {

        var loading = false; //状态
        var refreshStatus = false;
        var listType = 1;
        var params = {
            'page': 1, //默认页数
            'listType': 1, //默认类型
            'pageSize': 5 //默认每页条数
        }

        function htmlList(result) {
            $('.infinite-scroll .list-block').html(result);
        }

        function appendList(result) {
            $('.infinite-scroll .list-block').append(result);
        }

        function dataVerification() {
            $('.infinite-scroll-preloader').hide();
            $.detachInfiniteScroll($('.infinite-scroll'));
            loading = true;
            return;
        }
        //进入页面加载列表
        function ajaxList(params) {
            api.getList(params).done(function(rs) {

                if (!rs.error) {
                    var result = itemList(rs)
                    params = {
                        'page': rs.page,
                        'listType': rs.listType,
                        'pageSize': rs.size
                    }
                    htmlList(result);
                    $('.infinite-scroll-preloader').show();
                    if (!rs.data) {
                        var result = '<div class="nodata">暂无数据</div>';
                        htmlList(result);
                        dataVerification();
                    }
                    if (Math.ceil(rs.total / rs.size) <= rs.page) {
                        dataVerification();
                    }
                } else {
                    requestError(rs)
                }

            })
        }
        ajaxList(params);
        //进入页面加载列表
        function ajaxAppendList(params) {
            api.getList(params).done(function(rs) {
                if (!rs.error) {
                    params = {
                        'page': rs.page,
                        'listType': rs.listType,
                        'pageSize': rs.size
                    }
                    var result = itemList(rs)
                    appendList(result);

                    if (Math.ceil(rs.total / rs.size) <= rs.page) {
                        dataVerification();
                    }
                } else {
                    requestError(rs)
                }


            })
        }

        //切换日期
        $(document).on('click', '.external', function() {
            loading = false;
            var _that = $(this);
            listType = _that.attr('data-type');
            params['page'] = 1; //初始页数
            params['listType'] = listType;
            _that.addClass('active').siblings().removeClass('active');
            ajaxList(params);

        })



        //下拉刷新页面
        var $content = $(page).find(".content").on('refresh', function(e) {
            if (refreshStatus) {
                return;
            }
            refreshStatus = true;
            params['page'] = 1;
            ajaxList(params);
            setTimeout(function() {
                // 加载完毕需要重置
                $.pullToRefreshDone($content);
                $.attachInfiniteScroll($('.infinite-scroll'));
                $.refreshScroller();
                refreshStatus = false;
            }, 500);

        });
        //上拉加载更多
        $(page).on('infinite', function() {
            // 如果正在加载，则退出   
            if (loading) {
                return;
            }
            loading = true;
            params['page'] = params.page + 1
            setTimeout(function() {
                ajaxAppendList(params);
                $.refreshScroller();
                loading = false;

            }, 500);
        });


        function startScan(itemId) {
            wx.scanQRCode({
                desc: '扫描中...',
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function(res) {
                    if (res.errMsg == 'scanQRCode:ok') {
                        $.showPreloader(); //加载。
                        var data = {
                            'data': res.resultStr
                        }
                        api.postQRCodeData(data).done(function(rs) {


                            if (!rs.error) {
                                var doVerify = {
                                    'orderId': rs.orderId,
                                    'itemId': itemId
                                }
                                api.postDoVerify(doVerify).done(function(rs) {
                                    if (!rs.error) {
                                        $('.popup-about').html(rs.html);
                                        $.popup('.popup-about');
                                        $.hidePreloader(); //关闭
                                    } else {
                                        requestError(rs)
                                    };

                                })
                            } else {
                                requestError(rs)
                            };

                        })

                    }
                },
                error: function(res) {
                    if (res.errMsg.indexOf('function_not_exist') > 0) {
                        alert('版本过低请升级')
                    }
                }
            });
        }

        $(document).on('click', '.start-scan', function() {
            if ($(this).attr('data-item-id')) {
                startScan($(this).attr('data-item-id'));
            }
        });
        /*=== Popup ===*/
        var imgArr = [];
        $(document).on('click', '.pb-popup', function() {
            var imgList = $('.pb-popup')
            var len = imgList.length;
            for (var i = 0; i < len; i++) {
                var imgUrl = imgList[i].attr('data-src');
                imgArr.push(imgUrl)
            }
            myPhotoBrowserPopup.open();
        });
        var myPhotoBrowserPopup = $.photoBrowser({
            photos: imgArr,
            type: 'popup'
        });

    });

    $(document).on("pageInit", "#page-venue", function(e, id, page) {
        $(document).on('click', '#venueWicket', function() {
            wx.scanQRCode({
                desc: '扫描中...',
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode", "barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function(res) {
                    if (res.errMsg == 'scanQRCode:ok') {
                        var data = {
                            'data': res.resultStr
                        }
                        $.showPreloader();
                        api.postQRCodeDataVenue(data).done(function(rs) {
                            if (!rs.error) {
                                getVerifyInfo(rs.orderId);

                            } else {
                                requestError(rs)
                            };
                        });
                    }
                },
                error: function(res) {
                    if (res.errMsg.indexOf('function_not_exist') > 0) {
                        alert('版本过低请升级')
                    }
                }
            });
        });

        function getVerifyInfo(orderId) {
            var params = {
                'orderId': orderId
            }
            api.ajax_getOrderByIdVenue(params).done(function(rs) {
                if (!rs.error) {
                    $.popup('.popup-loose');
                    var verifyInfo = venueList(rs.result);
                    $('#contentItem').html(verifyInfo)
                    $.hidePreloader();
                } else {
                    requestError(rs)
                };
            })
        }

        $(document).on('click', '#WicketButton', function() {
            var that = $(this);
            if (that.hasClass("press-btn")) {
                return;
            }
            var orderId = that.attr('data-pk-order');
            that.addClass('press-btn');

            var params = {
                'orderId': orderId
            }
            api.ajax_verifyLooseVenue(params).done(function(rs) {
                if (!rs.error) {
                    $.toast('检票成功', 1000);
                    setTimeout(function() {
                        $.closeModal('.popup-loose');
                    }, 1000);

                } else {
                    requestError(rs)
                };
                setTimeout(function() {
                    that.removeClass('press-btn');
                }, 1000);
            })
        })
    });

    function requestError(rs) {
        $.toast(rs.info, 1000);
        $.hidePreloader(); //关闭
    }

    $.init();
});