<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>智慧体育企业公众号管理</title>
    <link rel="shortcut icon" href="{'manager/images/logo.png'|cdnurl}" type="image/x-icon">
    <!-- css -->
    <link rel="stylesheet" type="text/css" href="{'manager/css/bootstrap.min.css'|cdnurl}">
    <link rel="stylesheet" type="text/css" href="{'manager/css/jedate.css'|cdnurl}">


    <link rel="stylesheet" type="text/css" href="{'manager/css/main.css'|cdnurl}">
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="{'manager/lib/jquery.min.js'|cdnurl}"></script>
    <script src="{'manager/lib/layer.js'|cdnurl}"></script>
    <script src="{'manager/js/base.js'|cdnurl}"></script>
    <script src="{'manager_contest/js/main.js'|cdnurl}"></script>
    <script src="{'manager/lib/jedate.min.js'|cdnurl}"></script>
    <script src="{'manager/lib/bootstrap.min.js'|cdnurl}"></script>
    <script src="{'manager/lib/highcharts.js'|cdnurl}"></script>
</head>
<body>
<div class="loading-toast"><div><i></i></div></div>
<nav class="navbar navbar-blue  navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <span class="logo-icon icon"></span>
                <span>智慧体育</span>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li role="/wxapps/manage" class="active"><a href="/wxapps/manage">应用中心</a></li>
                <li role="/operation/market/activity_list" class=""><a href="/operation/market/activity_list">营销中心</a></li>
                <li role="/wxapps/auth"><a href="/wxapps/auth">公众号</a></li>
            </ul>
            <div class="navbar-right">
                <span class="acount-name login_user">~</span>
                <a href="/Index/loginquit" class="drop-out">
                    退出
                </a>
            </div>
        </div>
    </div>
</nav>
<script>
        
    //显示菜单
    $(function(){
        var menu_action = '{$smarty.server.REQUEST_URI}';
        var hashNav=window.location.hash;
        var login_user =  {$smarty.session.userInfo} ;
        var Z_config = {};
        $(".navbar-nav li").each(function () {
            var act_menu = $(this).attr("role");
            if (menu_action.indexOf(act_menu)!== -1) {
                $(this).addClass("active").siblings().removeClass("active");
            }else if(hashNav.indexOf(act_menu)!== -1){
                $(this).addClass("active").siblings().removeClass("active");
            }

        });
        if(login_user.user_name!=""){
            $(".login_user").text(login_user.user_name);
        }else{
            $(".login_user").text("系统管理员");

        }
    });





</script>



