 {include file="../user/header.tpl"}
 <div id="scroll-wrap"><div id="scroll-content">
 <div class="userInform">
      <div class="userInfo clearfix">
        <strong class="t">{$userinfo->username}</strong></span>
      <span class="face"><img src="{$userinfo->avatar}"></span></div>
     <!--  <dl class="dlForm clearfix">
        <dt class="dt">姓名：</dt>
        <dd class="dd"><input type="text" class="v" disabled="disabled" value="{$userinfo->username}"></dd>
      </dl>
      <dl class="dlForm clearfix">
        <dt class="dt">手机：</dt>
        <dd class="dd"><input type="text" class="v" disabled="disabled" value="{substr_replace($userinfo->mobile,'****',3,4)}"></dd>
      </dl> -->
      <div class="together clearfix" onclick="location.href='/user/addressmaster'">
        <strong class="t">收货地址管理</strong><span class="arror"></span>
      </div>
  </div>
<!--   <div class="userInform">
      <dl class="dlForm clearfix">
        <dt class="dt">性别：</dt>
        <dd class="dd">
          {if $userinfo->sex == 1}<label>男</label>{/if}
          {if $userinfo->sex == 2}<label>女</label>{/if}
        </dd>
      </dl>
  </div> -->
 <!--  <div class="submitButton">提交</div> -->
 </div></div>
{include file="../user/foot.tpl"}