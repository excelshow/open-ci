<div class="content-padded">
	<h5 class="ticket-status ">
		<img src="{'manager_contest/img/success.png'|cdnurl}" alt="">
	</h5>
	<p class="status-info">
			{$msg}
	</p>
</div>
<div class="content-block" style="margin-bottom:0.5rem">
	<div class="row">
		<div class="col-50">
			<a href="JavaScript:;" class="button button-big button-fill   close-popup">返回列表</a>
		</div>
		<div class="col-50">
			<a href="javascript:;" class="close-popup button button-big button-fill start-scan button-success" data-item-id="{$itemId}">继续检票</a>
		</div>
		
	</div>
</div>
<div class="content-block-title tittle-success"><h2>报名信息</h2></div>
<div class="list-block">
	<ul>
	{if !empty($orderInfo->enrol_info)}
		{foreach from=$orderInfo->enrol_info item=item}
		    <li class="item-content">
		    	<div class="item-inner">
			        <div class="item-title">{$item->title}</div>
			        {if $item->type == 'uploadfile'}
			        	<div class="item-after"><img src="{$item->value|cdnurl:'upload'}?imageMogr2/thumbnail/x250" data-src="{$item->value|cdnurl:'upload'}"></div>
			        {else}
			        	<div class="item-after">{$item->value}</div>
			        {/if}
		      	</div>
		    </li>
		    <li class="item-content">
		      	<div class="item-inner">
		        	<div class="item-title">报名时间</div>
		        	<div class="item-after">{$orderInfo->ctime}</div>
		      	</div>
		    </li>
    	{/foreach}
    {/if}
  </ul>
</div>