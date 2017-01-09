{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="ordersDetails-page">
		<div class="content">
  			<div class="card contest-list">
				<div class="lebal-block">
					<div class="card-content">
						<div class="item-title line-clamp2 font-sise18 line-clamp">{$result->contest_info->name}</div>
						<div class="color-gray mat5"><i class="iconfont mar5">&#xe60f;</i>
							{include file="../_location.tpl"}{$result->contest_info->location}
						</div>
						<div class="color-gray mat5"><i class="iconfont mar5">&#xe607;</i>
							{$result->contest_info->sdate_start|date_format:'%Y年%m月%d日'}－{$result->contest_info->sdate_end|date_format:'%Y年%m月%d日'}
						</div>
						<div class="card-content">
							{foreach from=$result->contest_item_list item=val}
							<div class="mat5 clearfix bottom-solid padb5">
								<div class="item-title ub-flex">
									<span class="ub-f1">{$val->name} </span>
									<span class="red-color mal5">{($val->fee/100)|string_format:"%.2f"} 元</span>
								</div>
								{if $result->type == 2}
								<div class="fr mat10 num_fee">x {$val->copies}</div>
								{else}
								<div class="fr mat10 num_fee">x {$result->copies}</div>
								{/if}
							</div>
							{/foreach}
						</div>
					</div>
				</div>
			</div>
			<div class="card contest-list top-solid padt0">
				<div class="card-content padt10">
					<div class="padb10 mat5">
						<div class="item-title"><span> 订单号</span>
							<span class="fr color66">{$result->out_trade_no}</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 订单时间</span>
							<span class="fr color66">{$result->ctime}</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 订单总价</span>
							<span class="fr color66">{($result->amount/100)|string_format:"%.2f"} 元</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 抵扣金额</span>
							<span class="fr color66">{($result->amount_discount/100)|string_format:"%.2f"} 元</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 实付金额</span>
								<span class="fr color66">{(($result->amount-$result->amount_discount)/100)|string_format:"%.2f"} 元</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 支付方式</span>
							<span class="fr color66">微信支付</span>
						</div>
					</div>
					<div class="padb10 mat5">
						<div class="item-title"><span> 订单状态</span>
							{if $result->state == 1 }
								<span class="fr red-color">开始报名</span> 
							{elseif $result->state == 2}
								<span class="fr red-color">支付中</span> 
							{elseif $result->state == 3 }
								<span class="fr gray-color">支付失败</span> 
							{elseif $result->state == 4 }
								<span class="fr green-color">支付成功</span>
							{elseif $result->state == 5 }
								<span class="fr green-color">支付成功</span> 
							{/if}
						</div>
					</div>
				</div>
				{if $result->state == 2}
				<p><a href="javascript:;" class="button button-fill button-big button-round pay-again" data-oid="{$result->pk_order}">立即支付</a></p>
				{/if}
			</div>
			<div class="writeoff-info mat10"> 
				<div class="writeoff-title padb5">已填资料</div>
				{foreach from=$result->contest_item_list item=val}
					<div class="writeoff-details mat10">
						<div class="padl1"><p><i class="iconfont">&#xe68b;</i> {$val->name}</p></div>
						{foreach from=$val->enrol_data item=v}
							{if $result->state == 5 or $result->state == 4}
								{foreach from=$v->verify_code item=t}
									{if $t->max_verify == $t->verify_number}
										<div class="text-right mat10 mar1 red-color">已核销</div>
									{/if}
									<div class="qr-images">
										<div class="verify_code" data-verify_code="{$t->verify_code_sigin}"></div>
									    <div class="text-center verify-code">{$t->code}</div>
									</div>
								{/foreach}
							{/if}
							<div class="writeoff-from dashed-line-top">
							{foreach from=$v->enrol_data_detail item=f}
								{if $f->type == "uploadfile"}
								<p><span>{$f->title}</span>: <img class="pb-standalone" src="http://img.wesai.com/{$f->value}?imageMogr2/thumbnail/160x100" alt="" data-src="http://img.wesai.com/{$f->value}"></p>
								{else}
								<p><span>{$f->title}</span>: <span>{$f->value}</span></p>
								{/if}

							{/foreach}
							</div>
						{/foreach}
					</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
<script src="{'src/lib/js/qrcode.min.js'|cdnurl}"></script>

<script>
	function getElementsClass(classnames) {
		var classobj = new Array(); //定义数组 
		var classint = 0; //定义数组的下标 
		var tags = document.getElementsByTagName("*"); //获取HTML的所有标签 
		for (var i in tags) { //对标签进行遍历 
			if (tags[i].nodeType == 1) { //判断节点类型 
				if (tags[i].getAttribute("class") == classnames) //判断和需要CLASS名字相同的，并组成一个数组 
				{
					classobj[classint] = tags[i];
					classint++;
				}
			}
		}
		return classobj; //返回组成的数组 
	}
	var verify_code = getElementsClass("verify_code");
	
	for(var i=0;i<verify_code.length;i++){
		var code_val=verify_code[i].getAttribute('data-verify_code');
		console.log(verify_code[i].innerHTML);
		var qrcode = new QRCode(verify_code[i], {
			width: 200,
			height: 200
		});
		qrcode.makeCode(code_val);
	}
</script>
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}
