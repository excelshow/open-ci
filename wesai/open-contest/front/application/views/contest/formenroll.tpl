{include file='../contest/header.tpl'}
<!-- 赛事报名-->
{include file='../contest/_jsconfig.tpl'}
<script>
	var shipping_addr = {};
	var invite_code   = "";
</script>
<style>
.reslect-box .reg-list li{
	padding: 5px 0;
}
.reslect-box .reg-list li .price span{
	font-size: 1.4rem;
}
	.info-style{
		height: 25px;
		margin-bottom: 15px;
	}
	.info-style:last-child{
		margin-bottom: 0;

	}
	.shopping-cart{
		height: 27px;
		width: 50%;
		float: right;
		padding-right: 20px;
		box-sizing: border-box;
	}
	
	/*加减效果 begin*/
	.quantity-btn{
	    float:right;
		height: 25px;
	    border:solid 1px #b3b3b3;

	    /*margin-top: 8px;*/
	}

	.quantity-btn .minus{
	    font-size:15px;
	    font-weight:bold;
	    color:#333;
	    background: #fff;
	    float:left;
	    height:25px;
	    text-align: center;
	    width:35px;
	    /*border-top-left-radius: 3px;
	    border-bottom-left-radius: 3px;*/
	}

	.quantity-btn .plus{
	    font-size:15px;
	    font-weight:bold;
	    color:#333;
	    background: #fff;
	    float:left;
	    height:25px;
	    width:35px;
	    border-left: 0;
	    text-align: center;
	    /*border:solid 1px #b3b3b3;*/
	    /*border-top-right-radius: 3px;
	    border-bottom-right-radius: 3px;*/
	}

	.quantity-btn .quantity-display{
	    float:left;
	    height:25px;
	    width:35px;
	    text-align:center;
	    font-weight:bold;
	    color:#b3b3b3;
	    background: #fff;
	    border-left:solid 1px #b3b3b3;
	    border-right:solid 1px #b3b3b3;

	}
	.text-right{
		text-align: right;
		color: red;
		font-weight: bold;
		float: left;
		width: 50%;
	}
	/*.price{
		visibility: hidden; 
	}*/
</style>
<div class="mian-from">
	<div class="wrap-content gray_bg">
		<div class="head-top white_bg">
			{include file='../contest/_contesttop.tpl'}
			<div class="reslect-box">
				<ul class="reg-list">
					<li>
					<div class="info-style">
						<div class="col title">{$itemInfoData->name}</div>
						<div class="price col bm-act" style="margin-right:15px;"><span class="prices">{if $itemInfoData->fee == 0}免费{else}{($itemInfoData->fee/100)|string_format:"%.2f"}</span><span>元</span>{/if}</div>
					</div>
                    {if $buyMoreOnce}
					<div class="info-style">
						<p class="text-right ">
							总价：<label id="total" class="total">{if $itemInfoData->fee == 0}免费{else}{($itemInfoData->fee/100)|string_format:"%.2f"}<span> 元</span>{/if}</label> </p> 
						<div class="shopping-cart">
							<div class="quantity-btn right">
								<span class="minus" type="button">-</span>
								<span class="quantity-display chc_height" type="text" id="quantity-display" readonly>1</span>
								<span class="plus" type="button">+</span>
							</div>
	
						</div>
					</div>
                    {else}
                        <span style="display:none;" class="quantity-display chc_height" type="text" id="quantity-display" readonly>1</span>
                    {/if}
					

					</li>
				</ul>
			</div>
		</div>
		<!-- 需要邮寄装备才显示 -->
		{if $contestData->deliver_gear == $smarty.const.DELIVER_GEAR_YES}
			{include file='../contest/_mailaddress.tpl'}
		{/if}
		<!-- 邀请码 -->
		 {if $itemInfoData->invite_required =="1"}
         {include file='../contest/_invitecode.tpl'}
         {/if}
		<!-- 报名表 -->
		<div class="pannel  pd-b50" style="background:#efefef">
			<h4 class="h-title">{$industry_name.enrol_info}</h4>
			<ul class="form-list three_color" id="question-list">
			</ul>
			<!-- 问题列表 -->
			{include file='../contest/_formlist.tpl'}
		</div>
	</div>
</div>
<script src="{'js/v2/lib/iscroll.js'|cdnurl}"></script>
<script src="{'js/template.js'|cdnurl}"></script>
<script type="text/javascript" src="{'js/city-picker.js'|cdnurl}"></script>
<script type="text/javascript">
	var cid         = "{$contestData->pk_contest}";
	var itemid      = "{$itemInfoData->pk_contest_items}"
	var state       = "{$contestData->publish_state}";
	var serverPath  = "http://img.wesai.com/";
	var contestInfo = {$contestData|json_encode};
	var fee         = {$itemInfoData->fee};
	{if !empty($formData)}
	var formjson     = {$formqlist|json_encode};
	var formid       = {$formData->pk_enrol_form};
	var formdatalist = {
		isEmpty : formjson.length,
		list    : formjson,
		citylist: CityListData
	};
	var obj_html     = template('formqlist', formdatalist);
	$("#question-list").html(obj_html);
	{/if}
	var deliver_gear    = {$contestData->deliver_gear};
	var invite_required = {$itemInfoData->invite_required};
</script>
<!--组件列表-->
<script src="{'js/formvalid.js'|cdnurl}"></script>
<!-- end -->
{if $itemInfoData->max_stock > 0 && $itemInfoData->cur_stock <=0 }
	<div class="act-pannel act-pannel-disabled">
		<div>满额</div>
	</div>
{elseif strtotime($itemInfoData->end_time) < time()}
	<div class="act-pannel act-pannel-disabled">
		<div>已停止</div>
	</div>
{else}
	<div class="act-pannel enabled hide" id="next-submit">
		<div>提交</div>
	</div>
{/if}
<div class="act-pannel act-pannel-disabled hide" id="yetsubmit">
	<div>处理中请稍后...</div>
</div>



<script>
	$(function() {
		$(".plus").click(function() {
		    var x = $(this).siblings(".quantity-display").text();
		    x++;
		    $(this).siblings(".quantity-display").text(x);
		    setTotal();
		})

		$(".minus").click(function() {
		    var y = $(this).siblings(".quantity-display").text();
		    y--;
		    if (y < 1) {
		        y = 1;
		    }
		    $(this).siblings(".quantity-display").text(y);
		    setTotal();
		})

		$(".quantity-display").blur(function() {
		    var z = $(this).text();
		    if (z < 1) {
		        z = 1;
		    }
		    $(this).text(z);
		})
		function setTotal() {
		    var s = 0;
		        s += parseInt($('.quantity-display').text()) * parseFloat($('.prices').text());
		    $("#total").html(s.toFixed(2));
		}
		setTotal();
	})
</script>
{include file='../contest/footer.tpl'}
