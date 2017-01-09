<div class="mask-bg"></div>
<div class="loginForm">
	<div class="valid-top">
		<h4>手机号验证</h4>
	</div>
	<form>
		<ul class="liform">
			<li>
				<input type="tel" class="inputxt" placeholder="请输入手机号" value="" name="mobile" id="mobile">
			</li>
			<li>
				<input type="tel" class="inputxt short" placeholder="请输入图形验证码" value="" name="tucode" id="tucode">
				<div class="img-valid">
					<img src="/user/captcha" height="30" id="captcha"><a href="javascript:;" class="refesh" onclick="return userTools.refreshCaptcha()" title="点击换一换">换一张</a>
				</div>
			</li>
			<li>
				<input type="tel" class="inputxt short" placeholder="请输入短信验证码" value="" name="valcode" id="valcode">
				<span class="vbtn vcode" id="sendcode" onclick="return userTools.sendcode()">获取验证码</span>
				<span class="vbtn vbtn-done timecounter hide">59s</span>
			</li>
			<li class="error-show">

			</li>
		</ul>
	</form>
	<div class="mg85">
		<button class="submitButton" onclick="return userTools.userReg()">确定</button>
		<button class="cancelButton" onclick="return  userTools.closeLogin()">取消</button>
	</div>
</div>
<script type="text/javascript" src="{'js/v2/userlogin.js'|cdnurl}"></script>
