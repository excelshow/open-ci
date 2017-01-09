require('./config.js');
require('./css/icon/iconfont.css');
require('../../SUI-Mobile/dist/css/sm.min.css');
require('../../SUI-Mobile/dist/css/sm-extend.min.css');
require('./css/common.css');
var verifyingItemList = require('./handlebars/verifying_item_list.handlebars');
var api = require('./_api.js');
require('../../SUI-Mobile/dist/js/sm.min.js');
require('../../SUI-Mobile/dist/js/sm-extend.min.js');
$(function() {
    'use strict';

    $(document).on("pageInit", "#page-loose", function(e, id, page) {
        $(document).on('click', '#looseWicket', function() {
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
                        api.postQRCodeData(data).done(function(rs) {

                            if (rs.error == 0) {
                                getVerifyInfo(rs.code);

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

        function getVerifyInfo(code) {
            var params = {
                'code': code
            }
            api.ajax_getOrderById(params).done(function(rs) {
                if (rs.error == 0) {
                    if (rs.result.order_info.owner_fk_corp != rs.pkCorp) {
                        $.toast('订单无效', 1000);

                        $.hidePreloader();
                        return;
                    }
                    var orderInfo = rs.result;
                    $.popup('.popup-loose');
                    var imgArr=[];
                    orderInfo.enrol_data_detail.map(function(item, index) {
                        if (item.type == "uploadfile") {
                            item['uploadfile'] = true;
                            item.value = 'http://img.wesai.com/' + item.value;
                            imgArr.push(item.value);
                        }
                        if (item.type == "policy") {
                            item.value = "同意";
                        }
                    })

                    /*=== 默认为 standalone ===*/
                    var myPhotoBrowserStandalone = $.photoBrowser({
                        photos: imgArr
                    });
                    //点击时打开图片浏览器
                    $(document).on('click', '.pb-standalone', function() {
                        myPhotoBrowserStandalone.open();
                    });

                    var paramsItem = {
                        'item_id': rs.result.fk_contest_items
                    };
                    api.getItemInfo(paramsItem).done(function(contest) {
                        var data = {
                            'contest_info': contest,
                            'order_info': orderInfo
                        }
                        var verifyInfo = verifyingItemList(data);
                        $('#contentItem').html(verifyInfo)
                    });

                    $.hidePreloader();
                } else {
                    requestError(rs)
                };
            })
        }

        $(document).on('click', '#WicketButton', function() {
            $('#verify_result').html('');
            var that = $(this);
            if (that.hasClass("press-btn")) {
                return;
            }
            var code = that.attr('data-pk-order');
            that.addClass('press-btn');
            var params = {
                'code': code
            }
            api.ajax_verifyLoose(params).done(function(verify) {
                if (verify.error == 0) {
                    var verify_number=parseInt($('.verify_number').text());
                    $('.verify_number').text(verify_number+1)
                    $('#verify_result').html('<span style="color:green"><i class="iconfont icon-iconziti55"></i> 成功</span>');

                } else {
                    $('#verify_result').html('<span style="color:red"><i class="iconfont icon-iconziti56"></i> ' + verify.info + '</span>');
                }
                that.removeClass('press-btn');
            })
        })
    });

    function requestError(rs) {
        $.toast(rs.info, 1000);
        $.hidePreloader(); //关闭
    }
    $.init();
});