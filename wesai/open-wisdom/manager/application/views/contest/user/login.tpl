
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>微赛后台管理系统登录</title>
<link rel="icon" href="http://static.wesai.com/front_contest_static/img/wesailogo.png?v=2016032516">
<link rel="stylesheet" href="{'manager_contest/css/bootstrap.min.css'|cdnurl}" />
<script src="{'manager_contest/js/jquery.min.js'|cdnurl}"></script>
{literal}
<style>
*{margin:0;padding:0;}
.container-fluid{padding:0}
.header{height:60px;overflow: hidden;line-height:60px;border-bottom:solid 2px #01ca65;background:#e7e8eb}
.header h1{font-size:18px;padding:0 10px}
.fR{float:right;}
.main{overflow: hidden;height:400px;background-color: #eee;background-image: url(http://static.wesai.com/front_contest_static/img/wesailogo.png?v=2016032516);background-size:200px 200px;background-repeat: no-repeat;background-position: 200px  center;border-bottom:solid 1px #eee}
.login_frame{float:right;width:400px;height:400px;padding:30px 50px;background:#fcfcfc;border-radius:5px}
.footer{padding:20px;margin-top:50px}
.error_tip{color:#a94442;padding-top:10px}
a{padding-left:20px}
{/literal}
</style>
</head>
<body>
<div class="container-fluid">
<div class="wrap">
<div class="header"><h1>微赛后台管理系统</h1></div>
<div class="main">
    <div class="login_frame">
            <h3>登录</h3>
            <form class="login_form" id="id1" action="#" method="post">
                	<div id="info-container" class="feedback infor"></div>
                    <div class="login_input_panel">
                         <div class="form-group">
                            <label>账号</label>
                            <input type="text" placeholder="用户名" id="account" name="account" value="" class="form-control" required>
                        </div>
                         <div class="form-group">
                            <label> 密码</label>
                            <input type="password" placeholder="密码" id="pass" name="pass" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="login_btn_panel">                                
                        <button type="submit" class="btn btn-success" id="loginBt">登 录</button> <!--<a href="#">忘记密码</a>-->
                    </div>
                     <p class="help-block error_tip" id="error_tip"></p>
            </form>
</div> 
</div>
<!-- foot -->
<div class="footer">
	<p class="text-center">Copyright&copy; 北京微赛时代体育科技有限公司</p>
</div>
</div>
</div>
{literal}
<script>
  $(function(){
     $("#loginBt").click(function(){
        var account = $("#account").val();
        var pass= $("#pass").val();
        if(account =="")
        {
            $("#error_tip").html("用户名不能为空");
            return false;
        }
        if(pass =="")
        {
            $("#error_tip").html("密码不能为空");
            return false;
        }
        $("#error_tip").html("");
        $.post("/user/logincheck",{account:account,pass:pass},function(data){
             if(data.error == 0)
             {
                window.location.href = "/";
             }else{
                $("#error_tip").html(data.info);
             }
        },"json")
        return false;
     })
  })
</script>
{/literal}
</body>
</html>
