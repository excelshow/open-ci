{include file='../header.tpl'}
<div class="page-group">
	<div class="page" id="payResult-page" data-oid={$oid} data-ORDER_STATE_INIT='{$smarty.const.ORDER_STATE_INIT}' 
	data-ORDER_STATE_PAYING='{$smarty.const.ORDER_STATE_PAYING}'>
		<div class="content">
		</div>
	</div>
</div>
<!-- end -->
{include file='../wxsdk.tpl'}
{include file='../footer.tpl'}
