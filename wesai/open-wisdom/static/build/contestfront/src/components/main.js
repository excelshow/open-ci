    require('./config.js');
    var api_js = require('./_api.js');

    $.config = {
        router: false
    }
    var iphoneReg = /^1\d{10}$/;

    function getQueryString() {
        var urls = window.location.search.substr(1).split('&');
        var i;
        var result = [];
        for (i = 0; i < urls.length; i++) {
            result[urls[i].split('=')[0]] = urls[i].split('=')[1];
        }
        return result;
    }


    require('../sui_mobile/dist/css/sm.min.css');
    require('../sui_mobile/dist/css/sm-extend.min.css');
    require('../css/common.css');
    require('../css/nav.css');
    require('../css/icon/iconfont.css');

    var api = require('./_api.js');

    require('../sui_mobile/dist/js/sm.js');
    require('../sui_mobile/dist/js/sm-extend.js');
    require('../sui_mobile/dist/js/sm-city-picker.min.js');



    require('./pagejs/contest.js');
    require('./pagejs/form.js');
    require('./pagejs/group.js');
    require('./pagejs/team.js');
    require('./pagejs/user.js');



    var baseUrl = window.location.pathname;
    $('.bar-tab a').each(function() {
        var newUrl = $(this).attr('for');
        var order = $(this).attr("data-order");
        if (baseUrl.indexOf(newUrl) !== -1) {
            $(this).addClass("active").siblings().removeClass("active");

        } else if (baseUrl.indexOf(order) !== -1) {
            $(this).addClass("active").siblings().removeClass("active");
        }
    })


    $(document).on('click', '.ico-more', function(e) {
        e.preventDefault();
        var $nav = $(e.currentTarget).closest('.nav-topnav');
        $nav.toggleClass('current');
        return false;
    });


    $(document).on('click', '.nav-stretch h4 a', function(e) {
        e.preventDefault();
        var $nav = $('.nav-stretch');
        $nav.toggleClass('current');
        return false;
    });

    $(document).on('click', function(e) {
        var $this = $(e.target);
        var $nav = $('.nav-stretch');

        if (!$this.parents('.nav-stretch').length) {
            $nav.removeClass('current');
        }
    });



    //加的效果
    
    $(document).on('click', '.add', function() {
            var n = $(this).siblings('.num').text();
            var cur_stock = parseInt($(this).siblings('.num').attr('data-cur_stock'));
            var num = parseInt(n) + 1;
            if (num > cur_stock) {
                $.toast("库存不足");
                return;
            }
            $(this).siblings('.num').text(num);
            setTotal();
        })
        //减的效果
    $(document).on('click', '.jian', function() {
        var n = $(this).siblings('.num').text();
        var num = parseInt(n) - 1;
        if (num == 0) {
            return
        }
        $(this).siblings('.num').text(num);
        setTotal();
    })

    $(document).on('click', '.verify_your_phone_number_join', function() {
        var _url = $(this).attr('data-href');
        $.get("/user/getuserinfo_by_uid", function(res) {
            if (res.error == 0) {
                if (res.result.mobile) {
                    window.location.href = _url;
                } else {
                    $.popup('.verify_your_phone_number');
                }
            } else {
                $.toast(res.info);
                return false;
            }
        }, "json");
    });
    function setTotal() {
        var s;
        var quantity = parseInt(($('.num').text()));
        if (!quantity) {
            return false;
        }
        var price = parseFloat($('.unit-price').attr('data-price'));
        s = quantity * price;
        $('.unit-price').text(s.toFixed(2));
    }
    setTotal();


    $('.verify-user').on('click', '.captcha-btn', function() {
        document.getElementById('captcha').src = '/user/captcha?' + Math.random();
    })
    $('.verify-user').on('click', '.mobile-btn', function() {
        var queryString = getQueryString();
        var cid = queryString.cid;
        var st;
        var _that = $(this);
        var totalTime = 60;
        var mobile = $(".mobile").val();
        if (!iphoneReg.test(mobile)) {
            $(".mobile").focus();
            $.toast("手机号格式不正确");
            return false;
        }
        var tucode = $(".tucode").val();
        if (tucode == "") {
            $.toast("图形验证码不能为空");
            return false;
        }
        if (_that.hasClass('disabled')) {
            return false;
        }
        _that.addClass('disabled').text(totalTime + "秒");
        $.post("/user/ajax_send_sms_verify_code", {
            mobile: mobile,
            tucode: tucode,
            cid: cid
        }, function(res) {
            if (res.error == 0) {
                st = setInterval(function() {
                    totalTime--;
                    console.log('totalTime')
                    _that.text(totalTime + "秒");
                    if (totalTime < 1) {
                        _that.text("获取验证码");
                        _that.removeClass('disabled');
                        clearInterval(st);
                    }
                }, 1000);
            } else {
                $.toast(res.info);
                _that.text("获取验证码");
                _that.removeClass('disabled');
                document.getElementById('captcha').src = '/user/captcha?' + Math.random();
            }
        }, "json");
        return false;
    });
    $('.verify_your_phone_number').on('click', '.verify-user', function() {
        return false;
    });
    $('.verify-user').on('click', '.userReg', function() {
        var _that = $(this);
        var mobile = $(".mobile").val();
        if (!iphoneReg.test(mobile)) {
            $(".mobile").focus();
            $.toast("手机号格式不正确");
            return false;
        }
        var tucode = $(".tucode").val();
        if (tucode == "") {
            $.toast("图形验证码不能为空");
            return false;
        }
        var vcode = $(".valcode").val();
        if (vcode == "") {
            $.toast("手机验证码不能为空");
            return false;
        }
        if (_that.hasClass('disabled')) {
            return false;
        }
        _that.addClass('disabled');
        $.post("/user/ajax_verify_sms_code", {
            mobile: mobile,
            vcode: vcode,
            tucode: tucode
        }, function(res) {
            if (res.error == 0) {
                $.closeModal('.popup');
            } else {
                _that.removeClass('disabled');
                document.getElementById('captcha').src = '/user/captcha?' + Math.random();
                $.toast(res.info);
            }
        }, "json");
        return false;
    })
    $('.verify-user').on('click', '.protocol', function() {
            window.location.href='/contest/protocol';
    })
    //删除成员
    $(document).on('click', '.del-member-btn', function() {
        var _that = $(this);
        $.confirm('您将删除此成员信息，删除后不可恢复 请谨慎操作', function() {
            var enrol_data_id = _that.attr('data-enrol_data_id');
            var params = {
                enrol_data_id: enrol_data_id
            }
            api_js.enroldata_delete(params).done(function(data) {
                if (data.error == 0) {
                    $.alert('删除成功');
                    location.reload(true);
                }else{
                    $.alert('删除失败');
                }
            })
        });
    });

    $.init();