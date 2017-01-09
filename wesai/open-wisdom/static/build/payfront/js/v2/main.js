/*commonjs*/
window.alert = function(msg) {
    $(".alert-box").remove();
    var alerthtml = '';
    alerthtml += '<div class="alert-box" role="alert">';
    alerthtml += '<div class="tip-title">' + msg + '</div>';
    alerthtml += '</div>';
    $("body").append(alerthtml);
    setTimeout(function() {
        $(".alert-box").fadeOut();
    }, 1000)
}
$(function() {
    // setTimeout(function() {
    //     $(".loading-toast").remove();
    // }, 500)
    jQuery(".contest-list").on("click", 'li', function(e) {
        var linkUrl = jQuery(this).attr("dataurl");
        location.href = linkUrl;
        return false;
    })
})


var paying = false;
var prepay_data = undefined;
if (total_time == 0) {
    paying = true;
    alert('订单已失效');
}
//倒计时
var cntime = setInterval(function() {
    var now_time = total_time--;
    var minutes = Math.floor(total_time / 60);
    var seconds = total_time - minutes * 60;
    var time_tip = minutes + '分' + seconds + '秒';
    $("#count_time").text(time_tip);
    if (total_time <= 1) {
        clearInterval(cntime);
        $("#count_time").text("支付时间");
        alert('订单已失效');
    }
}, 1000);

$("#pay").show();
$("#yetpay").hide();

function do_pay() {
    $("#pay").hide();
    $("#yetpay").show();
    get_wx_pay_info();
}

function get_wx_pay_info() {
    $.ajax({
            url: 'ajax_get_wx_pay_info',
            method: 'GET'
        })
        .done(function(msg) {
            msg = eval('(' + msg + ')');
            console.log(msg);
            if (msg.error != 0) {
                if (msg.info) {
                    alert(msg.info);
                } else {
                    alert('error');
                }
                return;
            }
            if (msg.orderState == ORDER_STATE_FAILED) {
                location.href = redirectUrl;
                return;
            }
            if (undefined != msg.prepay_data && '' != msg.prepay_data) {
                prepay_data = msg.prepay_data;
                do_wx_pay(prepay_data);
                if (prepay_data != undefined) {
                    $("#pay").hide();
                    $("#yetpay").show();
                } else {
                    alert("不能发起支付");
                    $("#pay").show();
                    $("#yetpay").hide();
                    return false;
                }
            }

        });
}
function do_wx_pay(prepay_data) {
    if (paying) {
        return false;
    }
    paying = true;
    wx.chooseWXPay({
        timestamp: prepay_data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
        nonceStr: prepay_data.nonceStr, // 支付签名随机串，不长于 32 位
        package: prepay_data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
        signType: prepay_data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
        paySign: prepay_data.paySign, // 支付签名
        success: function(res) {
            paying = false;
            // 支付成功后的回调函数
            alert('支付成功');
            location.href = redirectUrl;
        },
        error: function(err) {
            paying = false;
            alert('支付失败');
        },
        cancel: function(res) {
            $("#pay").show();
            $("#yetpay").hide(); //支付取消
        }
    });
    paying = false;
}
$("body").css("background-color", "#f2f2f2");

$(function() {
    jQuery(".contest-list").on("click", 'li', function(e) {
        var linkUrl = jQuery(this).attr("dataurl");
        location.href = linkUrl;
        return false;
    })
})



// var App = {
//     /**
//      * 设置｜获取 cookie
//      */
//     cookeData: {
//         get: function(c_name) {
//             if (document.cookie.length > 0) {
//                 var c_start = document.cookie.indexOf(c_name + "=");
//                 if (c_start != -1) {
//                     c_start = c_start + c_name.length + 1;
//                     var c_end = document.cookie.indexOf(";", c_start);
//                     if (c_end == -1) {
//                         c_end = document.cookie.length;
//                     }
//                     return decodeURI(document.cookie.substring(c_start, c_end));
//                 }
//             }
//             return "";
//         },
//         /**
//          * @param c_name  String cookie-name
//          * @param value String cookie-value
//          * @param time  String cookie-time [ s20 | m20 | h1 | d1 ] －> 分别设置为[ 20毫秒 ｜ 20分钟 ｜ 1小时 ｜ 1天 ]
//          * @param domian String domain
//          * @param path   String domain-path
//          */
//         set: function(c_name, value, time, domain, path) {

//             var getSec = function(_t) {
//                 var str1 = _t.substring(1, _t.length) * 1;
//                 var str2 = _t.substring(0, 1);
//                 if (str2 == 's') {
//                     return str1 * 1000;
//                 } else if (str2 == 'm') {
//                     return str1 * 60 * 1000;
//                 } else if (str2 == 'h') {
//                     return str1 * 60 * 60 * 1000;
//                 } else if (str2 == 'd') {
//                     return str1 * 24 * 60 * 60 * 1000;
//                 }
//             };

//             var expTime = typeof time == 'string' ? getSec(time) : null;
//             if (expTime) {
//                 var exdate = new Date();
//                 exdate.setTime(exdate.getTime() + expTime);
//             }

//             domain = domain || '';
//             path = path || '/';
//             document.cookie = c_name + "=" + encodeURI(value) +
//                 ((expTime == null) ? "" : ";expires=" + exdate.toGMTString()) + ';domain=' + domain + ';path=' + path;
//         },
//         clear: function(c_name, domain) {
//             domain = domain || '';
//             var date = new Date();
//             date.setTime(date.getTime() - 10000);
//             document.cookie = c_name + "=''; expires=" + date.toGMTString() + ';domain=' + domain;
//         }
//     }
// }

//hideweixinshare
var WxShareMenu = {
    HideWxMenu: function() {
        wx.ready(function() {
            wx.hideMenuItems({
                menuList: ['menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:share:qq', 'menuItem:share:weiboApp', 'menuItem:share:QZone']
            });
        })
    },
    ShowWxMenu: function() {
        wx.ready(function() {
            wx.showMenuItems({
                menuList: ['menuItem:share:appMessage', 'menuItem:share:timeline', 'menuItem:share:qq', 'menuItem:share:weiboApp', 'menuItem:share:QZone']
            });
        })
    },
    init: function() {
        if (typeof(shareData) == 'undefined') {
            WxShareMenu.HideWxMenu();
        } else {
            WxShareMenu.ShowWxMenu();
        }
    }
}
WxShareMenu.init();