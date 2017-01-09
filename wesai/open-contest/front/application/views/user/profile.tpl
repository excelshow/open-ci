{include file="../user/header.tpl"}
<div id="scroll-wrap"><div id="scroll-content">
    <!-- <div class="myInfo clearfix">
        <div class="userInfo">
          <span class="face"><img src="{$userinfo->avatar}"></span>
          <strong class="t">{$userinfo->username}</strong>
        </div>
        <div class="uMsg">
          <span class="set"><a href="/user/profilemanage">设置</a></span>
        </div>
    </div> -->
    <div class="myInfoSetting">
      <div class="infoList">
        <dl class="listDl">
          <dt class="ict ict16"><s></s></dt>
          <dd class="d" onclick="window.location.href='/user/contestorderlist'">
            <h4>订单</h4>
            <p class="desc"><a href="/user/contestorderlist">{$industry_name.items_tobuy}订单</a></p>
          </dd>
          <dt class="arror"></dt>
        </dl>
    </div>
    </div></div>
{include file='../user/_fixfoot.tpl'}
{include file="../user/foot.tpl"}
