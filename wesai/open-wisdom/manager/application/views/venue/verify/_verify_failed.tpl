<div class="content-padded">
	<h5 class="ticket-status ">
		<img src="{'manager_contest/img/failure.png'|cdnurl}" alt=""></h5>
	<p class="status-info">{if !empty($msg)}{$msg}{/if}{if !empty($errorno)}({$errorno}){/if}</p>
</div>

<div class="content-block">
	<div class="row">
		<div class="col-50">
			<a href="JavaScript:;" class="button button-big button-fill   close-popup">返回列表</a>
		</div>
		<div class="col-50">
			<a href="javascript:;" class="close-popup button button-big button-fill start-scan button-success" data-item-id="{$itemId}">继续检票</a>
		</div>
		
	</div>
</div>
