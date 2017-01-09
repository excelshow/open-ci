{if !empty($orderInfo)}
	<table>
		<tr>
			<td width="20%">订单编号</td>
			<td>{$orderInfo->out_trade_no}</td>
		</tr>
		<tr>
			<td>订单状态</td>
			<td>{$ORDER_PAY_SATATELIST[$orderInfo->state]}</td>
		</tr>
		<tr>
			<td>支付方式</td>
			<td>{$PAY_CHANNEL_LIST[$orderInfo->channel_id]}</td>
		</tr>
		<tr>
			<td>核销次数</td>
			<td>$orderInfo->verify_number</td>
		</tr>
	</table>
	{if !empty($orderInfo->contest_info)}
		<table>
			<tr>
				<td width="20%">活动名称</td>
				<td>{$orderInfo->contest_info->name}</td>
			</tr>
			<tr>
				<td>活动地点</td>
				<td>{$orderInfo->contest_info->location}</td>
			</tr>
			<tr>
				<td>开始时间</td>
				<td>{$orderInfo->contest_info->sdate}</td>
			</tr>
		</table>
	{/if}

	{if !empty($orderInfo->contest_item_info)}
		<table>
			<tr>
				<td width="20%">项目名称</td>
				<td>{$orderInfo->contest_item_info->name}</td>
			</tr>
			<tr>
				<td>项目费用</td>
				<td>{($orderInfo->contest_item_info->fee/100)|string_format:"%.2f"}元</td>
			</tr>
		</table>
	{/if}
	{if !empty($orderInfo->enrol_info)}
		<table>
			{foreach from=$orderInfo->enrol_info item=item}
				<tr>
					<td width="20%">{$item->title}</td>
					<td>{$item->value}</td>
				</tr>
			{/foreach}
		</table>
	{/if}
	<div>
		{if $orderInfo->verify_state == $smarty.const.ORDER_VERIFY_STATE_UNVERIFIED}
			<input type="button" value="确认订单" onclick="doVerify({$orderInfo->pk_order})"/>
		{/if}
		<input type="button" value="返回" onclick="cancelVerify()"/>
	</div>
{/if}
